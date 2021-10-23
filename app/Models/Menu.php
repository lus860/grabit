<?php

namespace App\Models;

use App\Http\Controllers\ImageController;
use App\Traits\GetIncrement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    use GetIncrement;

    public $timestamps = false;
    protected $fillable=['sort_id','start_time','restaurant_id'];
    protected $hidden = ['pivot'];

    public static function adminList($restaurant){
        if (!$restaurant) return [];
        return self::query()->where('restaurant_id', $restaurant)->orderBy('sort_id','asc')->get();
    }

    public static function saveData($inputs, $model=null) {
        $config = config('menu');
        if ($model) {
            $edit = true;
            $id = $model['id'];
            $model->load(['menu_items' => function($q){
                $q->with(['options' => function($q){
                    $q->with('values');
                }]);
            }]);
        }
        else {
            $edit = false;
            $model = new self;
            $id = self::getIncrement();
            $model['id'] = $id;
        }
        $menuItemIds = [];
        $optionIds = [];
        $valueIds = [];
        $now = now()->toDateTimeString();
        $daysInsert = [];
        $menuItemIncrement = MenuItem::getIncrement();
        $optionsIncrement = MenuItemOption::getIncrement();
        $optionValuesIncrement = MenuItemOptionValue::getIncrement();
        $days = Day::getIds();
        $menuItemsInsert = [];
        $optionsInsert = [];
        $optionValuesInsert = [];
        $model['name'] = $inputs['name'];
        $model['restaurant_id'] = $inputs['restaurant_id'];
        $model['start_time'] = $inputs['start_time']??null;
        $model['image'] = $inputs['image']??null;
        $model['early_schedule_time'] = $inputs['early_schedule_time']??null;
        $model['latest_schedule_time'] = $inputs['latest_schedule_time']??null;
        $model['end_time'] = $inputs['end_time']??null;
        $model['same_as_restaurant'] = (isset($inputs['same_as_restaurant']) && $inputs['same_as_restaurant'])?1:null;
        $old_sort = Menu::where(['sort_id'=>$inputs['sort_id'],'restaurant_id'=>$inputs['restaurant_id']])->first();
        if ($old_sort){
            $old_sort->update(['sort_id'=>($model['sort_id'])?$model['sort_id']:Menu::all()->count()+1]);
            $model['sort_id'] = $inputs['sort_id'];
        }else{
            $model['sort_id'] = $inputs['sort_id'];
        }
        $model['availability'] = $availability = ($inputs['availability']??null)=='specific_days'?'specific_days':'all_days';
        if ($availability == 'specific_days' && isset($inputs['day_id']) && is_array($inputs['day_id'])){
            foreach ($inputs['day_id'] as $day) if (in_array($day, $days)) {
                $daysInsert[] = $day;
            }
        }
        foreach ($inputs['menu_items'] as $menu_item) {
            if ($edit && isset($menu_item['id']) &&
                $findMenuItem = $model->menu_items->where('id', $menu_item['id'])->first()) {
                $newMenuItemId = $findMenuItem['id'];
                $menuItemIds[] = $newMenuItemId;
            } else  {
                $findMenuItem = null;
                $newMenuItemId = $menuItemIncrement++;
            }
            if (isset($menu_item['item_image']) && $menu_item['item_image']){
                if ($edit && $findMenuItem->image){
                    ImageController::imageDelete($findMenuItem->image);
                }
                $image_name = ImageController::imageUpload($menu_item['item_image']);
                $menu_item['item_image'] =$image_name;
            }else{
                $menu_item['item_image'] = null;

            }

            $newMenuItem = [
                'id' => $newMenuItemId,
                'menu_id' => $id,
                'name' => $menu_item['name'],
                'type' => $menu_item['type'],
                'price' => $menu_item['price'],
                'max_quantity' => $menu_item['max_quantity'],
                'description' => $menu_item['description']??null,
                'container_price' => $menu_item['container_price'],
                'offer_price' => $menu_item['offer_price'],
                'special_offer' => $menu_item['special_offer'],
                'popular_item' => $menu_item['popular_item'],
                'status' => (isset($menu_item['status']) && $menu_item['status'])?1:0,
                'image' => $menu_item['item_image']?:(($edit && $findMenuItem->image)?$findMenuItem->image:null),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            foreach(($menu_item['options']??[]) as $option) {
                if ($findMenuItem && isset($option['id']) &&
                    $findOption = $findMenuItem->options->where('id', $option['id'])->first()
                ) {
                    $newOptionId = $findOption['id'];
                    $optionIds[] = $newOptionId;
                }
                else {
                    $findOption = null;
                    $newOptionId = $optionsIncrement++;
                }
                $item_maximum = (int) ($option['item_maximum']??1);
                if ($option['type'] != 'addon' || $item_maximum>config('menu.types.addon')['max'] || $item_maximum<1) $item_maximum = 1;
                $newOption = [
                    'id' => $newOptionId,
                    'item_id' => $newMenuItem['id'],
                    'type' => $option['type'],
                    'name' => $option['name'],
                    'item_maximum' => $item_maximum,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $max = $config['types'][$option['type']]['max'];
                $i=1;
                foreach ($option['values'] as $value) {
                    if ($findOption && isset($value['id']) &&
                        $findValue = $findOption->values->where('id', $value['id'])->first()
                    ) {
                        $newValueId = $findValue['id'];
                        $valueIds[] = $findValue['id'];
                    }
                    else {
                        $findValue = null;
                        $newValueId = $optionValuesIncrement++;
                    }
                    $newValue = [
                        'id' => $newValueId,
                        'option_id' => $newOption['id'],
                        'value' => $value['value'],
                        'price' => $value['price'],
                        'status' => (isset($value['status']) && $value['status'])?1:0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $optionValuesInsert[] = $newValue;
                    ++$i;
                    if ($i>$max) break;
                }
                $optionsInsert[] = $newOption;
            }
            $menuItemsInsert[] = $newMenuItem;
        }
        DB::transaction(function() use ($model, $menuItemIds, $optionIds, $valueIds, $menuItemsInsert, $optionsInsert, $optionValuesInsert, $daysInsert){
            $model->save();
            MenuItem::query()->where('menu_id', $model['id'])->whereNotIn('id', $menuItemIds)->delete();
            MenuItemOption::query()->whereIn('item_id', $menuItemIds)->whereNotIn('id', $optionIds)->delete();
            MenuItemOptionValue::query()->whereIn('option_id', $optionIds)->whereNotIn('id', $valueIds)->delete();
            if (count($menuItemsInsert)) MenuItem::insertOrUpdate($menuItemsInsert, [
                'menu_id', 'name', 'type', 'price', 'max_quantity', 'description', 'container_price', 'offer_price', 'special_offer', 'status', 'popular_item', 'updated_at','image'
            ]);
            if (count($daysInsert)) $model->days()->sync($daysInsert);
            if (count($optionsInsert)) MenuItemOption::insertOrUpdate($optionsInsert, [
                'item_id', 'type', 'name', 'item_maximum', 'updated_at',
            ]);

            if (count($optionValuesInsert)) MenuItemOptionValue::insertOrUpdate($optionValuesInsert, [
                'option_id', 'value', 'price', 'status' ,'updated_at',
            ]);
        });
        return $inputs['restaurant_id'];
    }

    public static function getDataForEdit($id){
        $result = self::query()->select('id', 'name', 'restaurant_id', 'start_time',
            'end_time', 'availability','same_as_restaurant','image','early_schedule_time','latest_schedule_time')->where('id', $id)->with([
            'days' => function($q){
                $q->select('days.id');
            },
            'menu_items' => function($q) {
                $q->select('id', 'menu_id', 'name', 'type', 'price', 'max_quantity', 'description', 'container_price', 'offer_price', 'special_offer', 'popular_item','status','image')->with(['options' => function($q){
                    $q->select('id', 'item_id', 'type', 'name', 'item_maximum')->with(['values'=>function($q){
                        $q->select('id', 'option_id', 'value', 'price','status');
                    }]);
                }]);
            }
        ])->firstOrFail();
        if (count($result['days'])) $result['day_id'] = $result['days']->pluck('id')->toArray();
        unset($result['days']);
        return $result->toArray();
    }

    public function days(){
        return $this->belongsToMany(Day::class, 'menu_days')->withTimestamps();
    }

    public function restaurantMenu()
    {
        return $this->hasOne(RestaurantMenu::class, 'menu_id');
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function menu_items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('name','asc');
    }

    public function get_vendor()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function getEndTimeAttribute()
    {
        $same = $this->attributes['same_as_restaurant']??null;
        if ($same){
            $now = Carbon::now()->dayOfWeek;
            $rest = $this->getRelationValue('get_vendor');
            $time = $rest->openingTimes->where('day',$now)->first();
            return gettype($time)=='object'?$time->closing_time:$time;
        }
        return $this->attributes['end_time'];
    }

    public function getStartTimeAttribute()
    {
        $same = $this->attributes['same_as_restaurant']??null;
        if ($same){
            $now = Carbon::now()->dayOfWeek;
            $rest = $this->getRelationValue('get_vendor');
            $time = $rest->openingTimes->where('day',$now)->first();
            return gettype($time)=='object'?$time->opening_time:$time;
        }
        return $this->attributes['start_time'];
    }
}
