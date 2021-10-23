<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use Illuminate\Support\Carbon;
use App\Models\MenuItemPrice;
use App\Models\MenuItemOption;

class MenuItemController extends Controller {
    public function add($menu_id, Request $request){
        $data = $request->all();
        if($menu_id != '') {
            foreach($data['item_name'] as $key=>$val) {
                $menu_item = new MenuItem();
                $menu_item->menu_id = $menu_id;
                $menu_item->name = $val;
                $menu_item->description = $data['description'][$key];
                $menu_item->item_type = $data['item_type'][$key];
                $menu_item->max_quantity = $data['max_quantity'][$key];
                $menu_item->offer_price = $data['offer_price'][$key];
                $menu_item->container_price = $data['container_price'][$key];
                $menu_item->special_offer = $data['special_offer'][$key];
                $menu_item->popular_item = $data['popular_item'][$key];
                $menu_item->price = $data['price'][$key];
                $the_price = $data['price'][$key];
                $menu_item->save();
                $menu_item_id = $menu_item->id;

                if($menu_item_id != '') {
                    //Add Price
                    $date = new Carbon();
                    $price = new MenuItemPrice();
                    $price->item_id = $menu_item_id;
                    $price->price = $the_price;
                    $price->valid_from = date('Y-m-d');
                    $price->valid_to = $date->addDays(3650);
                    $price->save();

                    foreach($data['group_id'] as $key2=>$val2) {
                        if(!$data['group_id'][$key2] == '') {
                            $group_id = $val2;
                            //Array fields
                            if($data['item_option_value'] != '') {

                                foreach($data['item_option_value'] as $key3=>$val3) {
                                    if($data['item_option_price'][$key3] != '') {
                                        $item_option = new MenuItemOption();
                                        $item_option->item_id = $menu_item_id;
                                        $item_option->option_key = $group_id;
                                        $item_option->option_value = $data['item_option_value'][$key3];
                                        $item_option->added_price = $data['item_option_price'][$key3];
                                        $item_option->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect(url('/backend/menu/'.$menu_id));
    }

    public function remove($item_id, Request $request){
        $menu_id = $request->menu_id;
        MenuItemOption::where('item_id', $item_id)->destroy();
        MenuItemPrice::where('item_id', $item_id)->destroy();
        MenuItem::where('id', $item_id)->destroy();
        return redirect(url('/backend/menu/'.$menu_id));
    }
}
