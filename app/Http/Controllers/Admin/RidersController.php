<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Riders;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RidersController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = "Riders";
        $data['users'] = Riders::paginate(10);
        return view('admin.manage.riders.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['title'] = "Add rider";
        return view('admin.manage.riders.create',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'plate' => ['required'],
            'phone' => ['required','numeric'],
            'username' => ['required','unique:riders,username'],
            'password' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        Riders::_save($request);
        return redirect('/backend/manage/riders')->with(['flash_message' => 'Added new Rider: Success']);
    }

    /**
     * @param Request $request
     * @param Riders $rider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,Riders $rider){
        $data['title'] = "Edit Rider";
        $data['user'] =$rider;
        return view('admin.manage.riders.edit', $data);
    }

    /**
     * @param Request $request
     * @param Riders $rider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,Riders $rider){
        $data=[
            'name' => ['required'],
            'plate' => ['required'],
            'phone' => ['required','numeric'],
        ];
        if (isset($request->username) && $request->username){
            if ($rider->username != $request->username){
                $data['username'] =['required','unique:riders,username'];
            }else{
                $data['username'] =['required'];
            }
        }
        $validatedData = Validator::make($request->all(),$data);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        Riders::_save($request,$rider->id);
        return redirect('/backend/manage/riders')->with(['flash_message'=>'Success']);
    }



    /**
     * @param Request $request
     * @param Riders $rider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request,Riders $rider){
        $rider->delete();
        return redirect()->back()->with(['flash_message' => 'Deleted Rider']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_user_name(Request $request){
        $validatedData = Validator::make($request->all(),[
            'username' => ['required','unique:riders,username'],
        ],['unique'=>'This username is used, please use other username']);
        if ($validatedData->fails()) {
            return response()->json(0,404);
        }
        return response()->json(1);
    }
}
