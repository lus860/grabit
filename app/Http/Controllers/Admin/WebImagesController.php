<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\WebImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;

class WebImagesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = "Web images";
        $data['images'] = WebImages::paginate(10);
        return view('admin.web_images.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['title'] = "Add image";
        return view('admin.web_images.create',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'page' => ['required'],
            'name' => ['required'],
            'image' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $image = ImageController::imageUploadDefault($request);
        $request->merge(['saved_image'=>$image]);
        WebImages::_save($request);
        return redirect('/backend/web-images')->with(['flash_message' => 'Added new image: Success']);
    }

    /**
     * @param Request $request
     * @param WebImages $image
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,WebImages $image){
        $data['title'] = "Edit web image";
        $data['image'] = $image;
        return view('admin.web_images.edit', $data);
    }

    /**
     * @param Request $request
     * @param WebImages $image
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,WebImages $image){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'page' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $image_url = '';
        if (isset($request->image)){
            $validatedData = Validator::make($request->all(),[
                'image' => ['required'],
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
        }
        if($request->image != '') {
            if ($image->image){
                ImageController::imageDelete($image->image);
            }
            $new_image = ImageController::imageUploadDefault($request,null,null,null);
            $request->merge(['saved_image'=>$new_image]);
        }

        WebImages::_save($request,$image->id);
        return redirect('/backend/web-images')->with(['flash_message'=>'Success']);
    }
}
