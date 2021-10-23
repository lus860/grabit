<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatingContent;
use App\Models\VendorType;
use Illuminate\Http\Request;

class RatingController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      //
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      //
   }

   /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      //
   }

   public function api_add_rating(Request $request)
   {

   }

   public function getRating()
   {
      $vendorTypes = VendorType::with('RatingContent')->get()->toArray();

      //dd($vendorTypes);

      $finalData = [];

      foreach ($vendorTypes as $vendorType) {
         $ratingContentData = [];
         foreach ($vendorType['rating_content'] as $rating) {
            $array = [];
            $array['id'] = $rating['id'];
            $array['rating_score'] = $rating['rating_score'];
            $array['rating_message'] = $rating['rating_message'];
            array_push($ratingContentData, $array);
         }
         $finalData[] = array(
            'vendor_type_id' => $vendorType['id'],
            'vendor_type'    => $vendorType['vendor_name'],
            'rating_content' => $ratingContentData,

         );
      }

      return json_encode($finalData);
   }

    /**
     * @return false|string
     *
     */
   public function vendorTypes()
   {
      $vendorTypes = VendorType::all()->toArray();

      //dd($vendorTypes);

      $finalData = [];

      foreach ($vendorTypes as $vendorType) {

         $finalData[] = array(
            'vendor_type_id' => $vendorType['id'],
            'vendor_type'    => $vendorType['vendor_name'],
             'vendor_image'    => $vendorType['image'],

         );
      }
      return json_encode(
         array(
            "success"      => true,
            "message"      => "Ok",
            "vendor_types" => $finalData,
         ));
   }


}
