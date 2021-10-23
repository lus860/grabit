<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function add_phones_form(Request $request){
        $old_data = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();
        $data=[];
        if ($old_data){
            $data = unserialize($old_data->description);
            $data=$data['phones'];
        }
        $title = 'Phones for notification';
        return view('admin.admin.profile.add_phone',compact('title','data'));
    }

    public function add_emails_form(Request $request){
        $old_data = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();
        $data=[];
        if ($old_data){
            $data = unserialize($old_data->description);
            $data=$data['emails'];
        }
        $title = 'Emails for notification';
        return view('admin.admin.profile.add_email',compact('title','data'));
    }

    public function add_phones(Request $request){
        $validatedData = Validator::make($request->all(),[
            'phone' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $old_data = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();
        if ($old_data){
            $data = unserialize($old_data->description);
            foreach ($request->phone as $phone){
                if ($phone){
                    $phones[]=$phone;
                }
            }
            $data['phones']=$phones??[];
            $old_data->update(['description'=>serialize($data)]);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        $data['emails']=[];
        foreach ($request->phone as $phone){
            if ($phone){
                $phones[]=$phone;
            }
        }
        $data['phones']=$phones??[];
        $response = Setting::_save(
            ['title'=>'admin',
                'keyword'=>'notification',
                'description'=>serialize($data)
            ]);
        if ($response){
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()->withErrors(['flash_message'=>'Error saving admin information']);
    }

    public function add_emails(Request $request){
        $validatedData = Validator::make($request->all(),[
            'email' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $old_data = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();
        if ($old_data){
            $data = unserialize($old_data->description);
            foreach ($request->email as $email){
                if ($email){
                    $emails[]=$email;
                }
            }
            $data['emails']=$emails??[];
            $old_data->update(['description'=>serialize($data)]);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        $data['phones']=[];
        foreach ($request->email as $email){
            if ($email){
                $emails[]=$email;
            }
        }
        $data['emails']=$emails??[];
        $response = Setting::_save(
            ['title'=>'admin',
                'keyword'=>'notification',
                'description'=>serialize($data)
            ]);
        if ($response){
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()->withErrors(['flash_message'=>'Error saving admin information']);
    }
}
