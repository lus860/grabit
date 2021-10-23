<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlertMessages;
use App\Models\Cuisine;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Services\SendPushNotificationFromFirebase;
use App\Services\SmsService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlertMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Create alert message";
        $data['alerts'] = AlertMessages::paginate(10);
        return view('admin.alert_messages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Create alert message";

        return view('admin.alert_messages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'type' => ['required'],
            'target' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $function_name = $request->type;
        return $this->$function_name($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlertMessages  $alertMessages
     * @return \Illuminate\Http\Response
     */
    public function show(AlertMessages $alertMessages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AlertMessages  $alertMessages
     * @return \Illuminate\Http\Response
     */
    public function edit(AlertMessages $alertMessages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AlertMessages  $alertMessages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlertMessages $alertMessages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlertMessages  $alertMessages
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlertMessages $alertMessages)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_data_for_alert_message(Request $request){
        $validatedData = Validator::make($request->all(),[
            'alert_type' => ['required'],
            'custom_select' => ['required','integer'],
        ]);
        if ($validatedData->fails()) {
            return response()->json(['message'=>'Choose Two select, than you can send alert message'],400);
        }
        $users='';
        if ($request->alert_type && $request->alert_type != 'notification'){
            $users = User::where(['is_activated'=>1])->where($request->alert_type,'!=',null)->get()->toArray();
        }else{
            $users = User::where(['is_activated'=>1])->get()->toArray();
        }
        return response()->json(['data'=>$users],200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function phone(Request $request){
        if (isset($request->sms) && !$request->sms || !isset($request->sms)){
            return redirect()->back()
                ->withErrors(['flash_message'=>'write sms content'])
                ->withInput();
        }
        if ($request->target == 2){
            $users = User::where(['is_activated'=>1])->where('phone','!=',null)->get();
            foreach ($users as $user){
                SmsService::sendSmsForm($request->sms,$user->phone);
            }
            $data=[
                'users'=>'All users',
                'type'=>'phone',
                'message'=>$request->sms,
                'title'=>'',
            ];
            AlertMessages::_save($data);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }else{
            $names='';
            foreach ($request->users as $user){
                $user_data = User::find($user);
                SmsService::sendSmsForm($request->sms,$user_data->phone);
                $names .=isset($user_data->name)?$user_data->name.', ':'No name that user, ';
                $data=[
                    'users'=>$user_data->name??'No name that user',
                    'type'=>'phone',
                    'message'=>$request->sms,
                    'title'=>'',
                ];
                AlertMessages::_save($data);
            }
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()
            ->withErrors(['flash_message'=>'Something is wrong. Please send again'])
            ->withInput();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function email(Request $request){
        if (!isset($request->email_title)  || !isset($request->email_message)){
            return redirect()->back()
                ->withErrors(['flash_message'=>'write email content'])
                ->withInput();
        }
        if ($request->target == 2){
            $users = User::where(['is_activated'=>1])->where('email','!=',null)->get();
            foreach ($users as $user){
                EmailService::sendEmailFromAdminToUsers(['title'=>$request->email_title,'message'=>$request->email_message],$user->email);
            }
            $data=[
                'users'=>"All users",
                'type'=>'email',
                'message'=>$request->email_message,
                'title'=>$request->email_title,
            ];
            AlertMessages::_save($data);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }else{
            $names='';
            foreach ($request->users as $user){
                $user_data=User::find($user);
                EmailService::sendEmailFromAdminToUsers(['title'=>$request->email_title,'message'=>$request->email_message],$user_data->email);
                $names .=isset($user_data->name)?$user_data->name.', ':'No name that user, ';
            }
            $data=[
                'users'=>$names,
                'type'=>'email',
                'message'=>$request->email_message,
                'title'=>$request->email_title,
            ];
            AlertMessages::_save($data);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()
            ->withErrors(['flash_message'=>'Something is wrong. Please send again'])
            ->withInput();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function notification(Request $request){
        if (!isset($request->notification_title)  || !isset($request->notification_message)){
            return redirect()->back()
                ->withErrors(['flash_message'=>'write notification content'])
                ->withInput();
        }
        if ($request->target == 2){
            $users = User::get();
            foreach ($users as $user){
               SendPushNotificationFromFirebase::sendUsersFromAdmin(['title'=>$request->notification_title,'message'=>$request->notification_message],$user);
            }
            $data=[
                'users'=>"All users",
                'type'=>'notification',
                'message'=>$request->notification_message,
                'title'=>$request->notification_title,
            ];
            AlertMessages::_save($data);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }else{
            $names='';
            foreach ($request->users as $user){
                $user_data=User::find($user);
                $names .=isset($user_data->name)?$user_data->name.', ':'No name that user, ';
                SendPushNotificationFromFirebase::sendUsersFromAdmin(['title'=>$request->notification_title,'message'=>$request->notification_message],$user);
            }
            $data=[
                'users'=>$names,
                'type'=>'notification',
                'message'=>$request->notification_message,
                'title'=>$request->notification_title,
            ];
            AlertMessages::_save($data);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()
            ->withErrors(['flash_message'=>'Something is wrong. Please send again'])
            ->withInput();
    }
}
