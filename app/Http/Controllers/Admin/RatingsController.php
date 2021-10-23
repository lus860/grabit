<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatingsContent;
use App\Models\RatingsVendors;
use App\Models\VendorTypes;
use App\Models\Restaurant;
use App\Traits\CalculateRating;
use App\Traits\OrderResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingsController extends Controller
{
    use OrderResources,CalculateRating;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        $data['title'] = "Vendor Ratings";
        $data['ratings'] = RatingsVendors::where('vendor_id','!=',null)->paginate(10);
        foreach ($data['ratings'] as $key=>$value){
            $value->delivery_rating_name = $value->delivery_rating_message?$this->get_delivery_rating_content_name($value->delivery_rating_message):'N/A';
            $value->vendor_rating_name = $this->get_delivery_rating_content_name($value->vendor_rating_message);
            if ($value->order){
                if ($value->order->order_type == 1){
                    $status = $value->order->status == 'status_303'?$value->order->created_at->toDateTimeString():'N/A';

                }else{
                    $status = $value->order->status == 'dispatch'?$value->order->created_at:'N/A';
                }
            }else{
                unset($data['ratings'][$key]);
                continue;
            }
            $value->deliveried = $status;
        }
        return view('admin.ratings.vendor.index', $data);
    }

    public function courier_ratings(){
        $data['title'] = "Courier Ratings";
        $data['ratings'] = RatingsVendors::where('vendor_id',null)->paginate(10);
        foreach ($data['ratings'] as $key=>$value){
            $value->delivery_rating_name =$value->delivery_rating_message?$this->get_delivery_rating_content_name($value->delivery_rating_message):'N/A';
            if ($value->courier_order->order_type == 1){
                $status = $value->courier_order->status == 'status_303'?$value->courier_order->created_at->toDateTimeString():'N/A';
            }else{
                $status = $value->courier_order->status== 'dispatch'?$value->courier_order->created_at:'N/A';
            }
            $value->deliveried = $status;
        }
//        dd($data);
        return view('admin.ratings.courier.index', $data);
    }

    /**
     * @param $result
     * @return string
     */
    private function get_delivery_rating_content_name($result){
        $response = RatingsContent::whereIn('id',$result)->select('name')->get();
        $name = '';
        foreach ($response as $key =>$value){
            if ($response->count()-1 == $key){
                $name .= $value->name;
            }else{
                $name .= $value->name.', ';
            }

        }
        return $name;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|void
     */
    public function show(Request $request,$id){
        $rating = RatingsVendors::find($id);
        if ($rating){
            $order = $this->getMenu(json_decode($rating->order->action,true));
            $map = MapController::map($rating->order);
            return view('admin.ratings.vendor.show', [
                'order'=>$rating->order,
                'menus'=>$order,
                'map'=>$map,
                'title'=>'Order Rating'
            ]);
        }
        return abort(404);
    }

    public function show_courier_ratings(Request $request,$id){
        $rating = RatingsVendors::find($id);
        if ($rating){
            $map = MapController::map($rating->courier_order);
            return view('admin.ratings.courier.show', [
                'order'=>$rating->courier_order,
                'map'=>$map,
                'title'=>'Courier order rating'
            ]);
        }
        return abort(404);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request){
        $validatedData = Validator::make($request->all(),[
            'id' => ['required'],
        ],['required'=>'please try again']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $answer = RatingsVendors::where('id',$request->id)->first();
        $vendor_id = $answer->vendor_id;
        $answer->delete();
        if ($vendor_id){
            $ratings = RatingsVendors::where('vendor_id',$vendor_id)->get();
            if ($ratings->count()){
                Restaurant::find($vendor_id)->update(['average_rating'=>$this->calulcate_rating($ratings)]);
            }
        }
        if ($answer){
            return redirect()->back()->with(['flash_message' => 'Deleted Rating']);
        }

        return redirect()->back()->withErrors(['flash_message' => 'Something is wrong,please try later']);
    }
}
