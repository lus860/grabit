<?php

namespace App\Models;

use App\Models\Country;
use App\Models\Loyalty;
use App\Models\Order;
use App\Models\VendorTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    protected $fillable=['sort_id','average_rating','preparation_time','status','qr_url','qr_code'];

    protected $guarded = [];

    public static function adminList(){
        return self::query()->get();
    }

    public static function _save($request,$id=null,$images=null,$certificates=null){
        if (!$data = self::find($id)){
            $data = new self();
        }
        if (isset($request['name']) && $request['name']){
            $data->name = $request['name'];
            $data->slug = Str::slug($request['name'], '-').'-'.substr(md5(time()), -10);
        }
        if (isset($request['company_name']) && $request['company_name']){
            $data->company_name = $request['company_name'];
        }
        if (isset($request['contact_name']) && $request['contact_name']){
            $data->contact_name = $request['contact_name'];
        }
        if (isset($request['email']) && $request['email']){
            $data->email = $request['email'];
        }
        if (isset($request['phone']) && $request['phone']){
            $data->phone = SSJUtils::add255($request['phone']);
        }
        if (isset($request['website']) && $request['website']){
            $data->website = $request['website'];
        }
        if (isset($request['country']) && $request['country']){
            $data->country_id = $request['country'];
        }
        if (isset($request['city']) && $request['city']){
            $data->city_id = $request['city'];
        }
        if (isset($request['latitude']) && $request['latitude']){
            $data->latitude = $request['latitude'];
        }
        if (isset($request['longitude']) && $request['longitude']){
            $data->longitude = $request['longitude'];
        }
        if (isset($request['address1']) && $request['address1']){
            $data->address1 = $request['address1'];
        }
        if (isset($request['address2']) && $request['address2']){
            $data->address2 = $request['address2'];
        }
        if (isset($request['area']) && $request['area']){
            $data->area_id = $request['area'];
        }

        if (isset($request['delivery_commission']) && $request['delivery_commission']){
            $data->delivery_commission = $request['delivery_commission'];
        }
        if (isset($request['collection_commission']) && $request['collection_commission']){
            $data->collection_commission = $request['collection_commission'];
        }
        if (isset($request['dine_in_commission']) && $request['dine_in_commission']){
            $data->dine_commission = $request['dine_in_commission'];
        }
        if (isset($request['number_for_customers']) && $request['number_for_customers']){
            $data->number_for_customers = $request['number_for_customers'];
        }
        if (isset($request['status']) && $request['status']){
            $data->status = $request['status'];
        }
        if (isset($request['bank_name']) && $request['bank_name']){
            $data->bank_name = $request['bank_name'];
        }
        if (isset($request['beneficiary_name']) && $request['beneficiary_name']){
            $data->beneficiary_name = $request['beneficiary_name'];
        }
        if (isset($request['account_number']) && $request['account_number']){
            $data->account_number = $request['account_number'];
        }
        if (isset($request['paytz_number']) && $request['paytz_number']){
            $data->account_number = $request['paytz_number'];
        }
        if (isset($request['payment_frequent']) && $request['payment_frequent']){
            $data->payment_frequent = $request['payment_frequent'];
        }
        if (isset($request['cuisine_cost_for_two']) && $request['cuisine_cost_for_two']){
            $data->cost_for_two = $request['cuisine_cost_for_two'];
        }
        if (isset($request['cuisine_prep_time']) && $request['cuisine_prep_time']){
            $data->preparation_time = $request['cuisine_prep_time'];
        }
        if (isset($request['minimum_order']) && $request['minimum_order']){
            $data->minimum_order = $request['minimum_order'];
        }
        if (isset($request['vendor_id']) && $request['vendor_id']){
            $data->vendor_id = $request['vendor_id'];
        }
        $file_destination = public_path().'/uploads/';
        $oldBanner=null;
        $oldDisplay=null;
        if (isset($images['banner']) && $images['banner']){
            if ($data->banner_image){
                $oldBanner=explode('/',$data->banner_image);
                $oldBanner=array_last($oldBanner);
            }
            $data->banner_image = $images['banner'];
        }
        if (isset($images['display']) && $images['display']){
            if ($data->display_image){
                $oldDisplay=explode('/',$data->display_image);
                $oldDisplay=array_last($oldDisplay);
            }
            $data->display_image = $images['display'];
        }
        if (isset($certificates['registration_certificate']) && $certificates['registration_certificate']){
            if ($data->registration_certificate){
                $registration_certificate=explode('/',$data->registration_certificate);
                $registration_certificate=array_last($registration_certificate);
            }
            $data->registration_certificate = $certificates['registration_certificate'];
        }
        if (isset($certificates['tin_certificate']) && $certificates['tin_certificate']){
            if ($data->tin_certificate){
                $tin_certificate=explode('/',$data->tin_certificate);
                $tin_certificate=array_last($tin_certificate);
            }
            $data->tin_certificate = $certificates['tin_certificate'];
        }
        if (isset($certificates['business_license']) && $certificates['business_license']){
            if ($data->business_license){
                $business_license=explode('/',$data->business_license);
                $business_license=array_last($business_license);
            }
            $data->business_license = $certificates['business_license'];
        }
        if (isset($certificates['director_id']) && $certificates['director_id']){
            if ($data->director_id){
                $director_id=explode('/',$data->director_id);
                $director_id=array_last($director_id);
            }
            $data->director_id = $certificates['director_id'];
        }
        if (isset($certificates['agreement']) && $certificates['agreement']){
            if ($data->agreement){
                $agreement=explode('/',$data->agreement);
                $agreement=array_last($agreement);
            }
            $data->agreement = $certificates['agreement'];
        }
        if ($data->save()){
            if(isset($oldBanner)&& $oldBanner && File::exists($file_destination.$oldBanner)) {
                File::delete($file_destination.$oldBanner);
            }
            if(isset($oldDisplay)&& $oldDisplay && File::exists($file_destination.$oldDisplay)) {
                File::delete($file_destination.$oldDisplay);
            }
            if(isset($registration_certificate)&& $registration_certificate && File::exists($file_destination.$registration_certificate)) {
                File::delete($file_destination.$registration_certificate);
            }
            if(isset($tin_certificate)&& $tin_certificate && File::exists($file_destination.$tin_certificate)) {
                File::delete($file_destination.$tin_certificate);
            }
            if(isset($business_license)&& $business_license && File::exists($file_destination.$business_license)) {
                File::delete($file_destination.$business_license);
            }
            if(isset($director_id)&& $director_id && File::exists($file_destination.$director_id)) {
                File::delete($file_destination.$director_id);
            }
            if(isset($agreement)&& $agreement && File::exists($file_destination.$agreement)) {
                File::delete($file_destination.$agreement);
            }
            return $data;
        }
        return false;
    }

    public function restaurantEmail()
    {
        return $this->hasMany(RestaurantEmail::class);
    }

    public function paymentFrequency()
    {
        return $this->belongsTo(PaymentFrequency::class, 'payment_frequent');
    }

    public function restaurantCuisine()
    {
        return $this->hasMany(RestaurantCuisine::class);
    }

    public function customization()
    {
        return $this->hasMany(CustomizationGroup::class);
    }

    public function restaurantOffering()
    {
        return $this->hasMany(RestaurantOffering::class);
    }

    public function user()
    {
        return $this->hasOne(RestaurantUsers::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function openingTimes()
    {
        return $this->hasMany(RestaurantOpeningTime::class, 'restaurant_id');
    }

    public function break_times()
    {
        return $this->hasMany(RestaurantBreakTime::class, 'restaurant_id');
    }

    public function getStatus()
    {
        return $this->status == 1 ? 'Active' : 'InActive';
    }

    public function getServiceAre()
    {
        $data = [];
        $service_area = RestaurantServiceArea::where('restaurant_id', $this->id)->get();
        if (!empty($service_area)) {
            foreach ($service_area as $key => $area) {
                $data[$key]['id'] = $area->id;
                $data[$key]['area_id'] = $area->area?$area->area->id:null;
                $data[$key]['name'] = $area->area?$area->area->name:null;
            }
        }
        return $data;
    }

    public function restaurantMenu()
    {
        return $this->hasMany(RestaurantMenu::class);
    }

    public function favourite()
    {
        return $this->hasMany(FavouriteRestaurant::class, 'restaurant_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function service_area(){
        return $this->belongsToMany(Area::class, 'restaurant_service_areas')->withTimestamps();
    }
    public function get_service_area(){
        return $this->belongsTo(Area::class, 'service_area');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getBasicData()
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'company_name' => $this->company_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'area' => $this->area->name,
            'country' => $this->country->name,
            'city' => $this->city->name,
            'display_image' => $this->display_image,
            'banner_image' => $this->banner_image,
            'preparation_time' => $this->preparation_time,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address1,
        ];
    }

    public function menu()
    {
        return $this->hasMany(Menu::class)->orderBy('sort_id','asc');
    }

    public function vendor_type()
    {
        return $this->hasOne(VendorTypes::class,'id','vendor_id');
    }

    public function loyalty()
    {
        return $this->hasOne(Loyalty::class,'vendor_id','id');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toDateTimeString();
    }

    public function branches(){

        return $this->hasMany(VendorBranch::class,'vendor_id','id');
    }

    public function branche(){

        return $this->belongsTo(VendorBranch::class,'vendor_id','id');
    }

    public function branches_name(){
        $name = [];
        foreach ($this->branche()->get() as $branch){
            $name[] = $branch->name;
        }
        return $name;
    }

    public function redemptionServices(){
        return $this->hasMany(VendorsRedemptionServices::class,'vendor_id','id');
    }
}
