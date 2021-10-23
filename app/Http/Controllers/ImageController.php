<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use Image;
use File;
use Illuminate\Support\Facades\Validator;


class ImageController extends Controller
{

    const path = [
        'image' => 'admin/uploads/',
//        'thumb' => 'upload/images/thumb/'
    ];


    /**
     * return image
     *
     * @param Request $request
     * @param $image
     * @param null $thumb
     * @return mixed
     */
    public function get(Request $request, $image, $thumb = null)
    {
        $imagesPath = ($thumb) ? self::path['thumb'] : self::path['image'];
        return Image::make(public_path($imagesPath . $image))->response();
    }
    /**
     * return image
     *
     * @param Request $request
     * @param $image
     * @param null $thumb
     * @return mixed
     */
    public function get_other(Request $request, $path, $image)
    {
        return Image::make(storage_path('app/'.$path.'/'.$image))->response();
    }

    /**
     * delete image from storage
     *
     * @param $imageName
     * @return mixed
     */
    public static function imageDelete($imageName){
        $img = explode('/',$imageName);
        $key = array_search('uploads',$img);
        $image_url = public_path('\\admin\\'.$img[$key].'\\'.array_last($img));
        return File::delete(["$image_url"]);
    }

    /**
     * upload image to storage
     *
     * @param $request
     * @param null $imageName
     * @param int $iHeight
     * @param int $tHeight
     * @return int|null|string
     */
    public static  function imageUpload( $request,$imageName=null ,$tWidth=1017,$iWidth=448)    {
        if(isset($request->image)){
            $image=$request->image;
        }else{
            $image=$request;
        }
        if ($image) {
            if(!$imageName){
                $imageName = time() . '.' .$image->getClientOriginalName();
            }
            $upload_image = $image->move(public_path(self::path['image']), $imageName);
            // resizing an uploaded file
            if($upload_image){
                Image::make(public_path(self::path['image']).$imageName)->resize($tWidth, $iWidth)->save(public_path(self::path['image']) . $imageName);
                return url('/').'/admin/uploads/'.$imageName;
            }
            else{
                return 0;
            }
        }
        else{
            return 0;
        }
    }

    public static  function imageUploadDefault( $request,$imageName=null){
        if(isset($request->image)){
            $image=$request->image;
        }else{
            $image=$request;
        }
        if ($image) {
            if(!$imageName){
                $imageName = time() . '.' .$image->getClientOriginalName();
            }
            $upload_image = $image->move(public_path(self::path['image']), $imageName);
            // resizing an uploaded file
            if($upload_image){
                Image::make(public_path(self::path['image']).$imageName)->save(public_path(self::path['image']) . $imageName);
                return url('/').'/admin/uploads/'.$imageName;
            }
            else{
                return 0;
            }
        }
        else{
            return 0;
        }
    }


    public function upload(Request $request){
        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = self::path['image'] . uniqid() . '.png';

        file_put_contents($file, $image_base64);
        Image::make(public_path($file))->resize(1017, 448)->save(public_path($file));
        return response()->json(['success'=>'success','name'=>url('/').'/'.$file]);
    }
}
