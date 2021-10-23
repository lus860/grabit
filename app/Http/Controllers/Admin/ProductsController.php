<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cuisine;
use App\Models\CustomizationGroup;
use App\Models\CustomizationValue;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index(){
        $products = array();

        $products_list = Product::paginate(20);

        $products_links = $products_list->links();
        $products['pages'] = $products_links;
        $products['product'] = array();

        if(!empty($products_list)){
            foreach ($products_list as $key=>$product){
                $products['product'][$key]['id'] = $product->id;
                $products['product'][$key]['name'] = $product->name;
                $products['product'][$key]['description'] = $product->description;
                $products['product'][$key]['image'] = $product->a_img;
                $products['product'][$key]['image2'] = $product->b_img;
                $products['product'][$key]['image3'] = $product->c_img;
                $products['product'][$key]['parent'] = $product->parent_id;
                $products['product'][$key]['quantity'] = $product->quantity;
                $products['product'][$key]['price'] = $product->price;
                $products['product'][$key]['brand'] = Brand::where('brand_id', $product->brand_id)->get();
                $products['product'][$key]['category'] = Category::where('cat_id', $product->cat_id)->get();
            }
        }
        return response()->json($products);
    }

    public function category($category){
        $products = array();

        $products_list = Product::where('cat_id', $category)->paginate(20);

        $products_links = $products_list->links();
        $products['pages'] = $products_links;
        $products['product'] = array();

        if(!empty($products_list)){
            foreach ($products_list as $key=>$product){
                $products['product'][$key]['id'] = $product->id;
                $products['product'][$key]['name'] = $product->name;
                $products['product'][$key]['description'] = $product->description;
                $products['product'][$key]['image'] = $product->a_img;
                $products['product'][$key]['image2'] = $product->b_img;
                $products['product'][$key]['image3'] = $product->c_img;
                $products['product'][$key]['parent'] = $product->parent_id;
                $products['product'][$key]['quantity'] = $product->quantity;
                $products['product'][$key]['price'] = $product->price;
                $products['product'][$key]['brand'] = Brand::where('brand_id', $product->brand_id)->get();
                $products['product'][$key]['category'] = Category::where('cat_id', $product->cat_id)->get();
            }
        }
        return response()->json($products);
    }
    public function cuisines(Request $request){
        $cuisine = array();
        $cuisines = Cuisine::all();
        if(!empty($cuisines)){
            foreach ($cuisines as $key=>$item){
                $cuisine[$key]['id'] = $item->id;
                $cuisine[$key]['name'] = $item->name;
                $cuisine[$key]['imageUri'] = $item->image;
                $cuisine[$key]['icon'] = $item->icon;
                $cuisine[$key]['price'] = 0;
                $cuisine[$key]['quantity'] = 0;
            }
        }
        return response()->json($cuisine);
    }
    public function cuisine(Request $request){
        $cuisine = array();
        $cuisine_id = $request->id;
        $cuisine_item = Cuisine::find($cuisine_id);
        if(!empty($cuisine_item)){
            $cuisine['id'] = $cuisine_item->id;
            $cuisine['name'] = $cuisine_item->name;
            $cuisine['icon'] = $cuisine_item->icon;
            $cuisine['imageUri'] = $cuisine_item->image;
            $cuisine['price'] = 0;
            $cuisine['quantity'] = 0;
            $cuisine['menu'] = $this->get_cuisine_menu($cuisine_id);
        }
        return response()->json($cuisine);
    }

    public function get_cuisine_menu($cuisine_id){
        $the_cuisine = Cuisine::find($cuisine_id);
        /*print_r($the_cuisine->menu);
        die();*/
        $data = [];
        if (!empty($the_cuisine)) {
            $menus = [];
            if(!empty($the_cuisine->menu)){
                foreach($the_cuisine->menu as $key4=>$menu){
                    /*print_r($menu);
                    die();*/
                    $menus[$key4]['id'] = $menu->id;
                    $menus[$key4]['name'] = $menu->name;
                    $menus[$key4]['start_time'] = $menu->start_time;
                    $menus[$key4]['end_time'] = $menu->end_time;

                    $menu_items = [];
                    if(!empty($menu->menuItem)){
                        foreach($menu->menuItem as $key5=>$val5){
                            $menu_items[$key5]['id'] = $val5->id;
                            $menu_items[$key5]['name'] = $val5->name;
                            $menu_items[$key5]['imageUri'] = $val5->Menu->cuisine->image;
                            $menu_items[$key5]['description'] = $val5->description;
                            $menu_items[$key5]['item_type_code'] = $val5->item_type;
                            $menu_items[$key5]['item_type'] = $val5->item_type == 1?'Veg':'Non Veg';
                            $menu_items[$key5]['max_quantity'] = $val5->max_quantity;
                            $menu_items[$key5]['price'] = $val5->price;
                            $menu_items[$key5]['rating'] = 0;
                            $menu_items[$key5]['quantity'] = 1;
                            $menu_items[$key5]['container_price'] = $val5->container_price;
                            $menu_items[$key5]['offer_price'] = $val5->offer_price;
                            $menu_items[$key5]['discountPercentage'] = 0;

                            $price_list = [];

                            /*if(!empty($val5->price)) {
                                foreach($val5->price as $key6=>$price) {
                                    $price_list[$key6]['price'] = $price->price;
                                    $price_list[$key6]['valid_from'] = $price->valid_from;
                                    $price_list[$key6]['valid_to'] = $price->valid_to;
                                }
                            }*/
                            //$menu_items[$key5]['price'] = $price_list;

                            $menu_items_options = [];

                            $sql = "select distinct(`option_key`) as `option_key` from `menu_item_options` where `item_id`='".$val5->id."'  group by `option_key`";
                            $group_values = DB::select($sql);
                            if(!empty($group_values)){
                                foreach($group_values as $key6=>$val6){
                                    $group_data = CustomizationGroup::where('id', $val6->option_key)->first();
                                    $menu_items_options[$key6]['id'] = $group_data->id;
                                    $menu_items_options[$key6]['group_name'] = $group_data->name;
                                    $menu_items_options[$key6]['group_type'] = $group_data->ctype == 1?'multiple':'single';
                                    $menu_items_options[$key6]['group_type_code'] = $group_data->ctype;
                                    $menu_items_options[$key6]['select_max'] = $group_data->select_max;
                                    $the_group_values = [];
                                    foreach($group_data->values as $key8=>$val8){
                                        $the_option = MenuItemOption::where(['option_value'=> $val8->id])->first();
                                        $the_group_values[$key8]['item_id'] = $val8->id;
                                        $the_group_values[$key8]['item_name'] = $val8->name;
                                        $the_group_values[$key8]['price'] = !empty($the_option)?$the_option->added_price:0;
                                    }
                                    $menu_items_options[$key6]['group_values'] = $the_group_values;
                                }
                            }
                            $menu_items[$key5]['customization_groups'] = $menu_items_options;
                        }
                    }

                    $menus[$key4]['items'] = $menu_items;
                }
            }
            $data = $menus;
        }
        return $data;
    }

    public function popular_items(Request $request){
        $popular_items = $this->get_menu_items('popular_item');
        $offer_items = $this->get_menu_items('special_offer');

        return response()->json([
            'success'=>1,
            'popular'=>$popular_items,
            'offer'=>$offer_items
        ]);
    }
    public function get_menu_items($menu_type){
        $menuItems = MenuItem::where([$menu_type=>1])->orderBy('id', 'desc')->skip(0)->take(5)->get();
        $menu_items = [];
        if(!empty($menuItems)) {
            foreach ($menuItems as $key5 => $val5) {
                /*print_r($val5->Menu);
                die();*/
                $menu_items[$key5]['id'] = $val5->id;
                $menu_items[$key5]['name'] = $val5->name;
                $menu_items[$key5]['imageUri'] = $val5->menu->cuisine->image;
                $menu_items[$key5]['description'] = $val5->description;
                $menu_items[$key5]['item_type_code'] = $val5->item_type;
                $menu_items[$key5]['item_type'] = $val5->item_type == 1 ? 'Veg' : 'Non Veg';
                $menu_items[$key5]['max_quantity'] = $val5->max_quantity;
                $menu_items[$key5]['price'] = $val5->price;
                $menu_items[$key5]['container_price'] = $val5->container_price;
                $menu_items[$key5]['offer_price'] = $val5->offer_price;

                $menu_items[$key5]['rating'] = 0;
                $menu_items[$key5]['quantity'] = 1;
                $menu_items[$key5]['discountPercentage'] = 0;

                $menu_items_options = [];

                $sql = "select distinct(`option_key`) as `option_key` from `menu_item_options` where `item_id`='" . $val5->id . "'  group by `option_key`";
                $group_values = DB::select($sql);
                if(!empty($group_values)){
                    foreach($group_values as $key6=>$val6){
                        $group_data = CustomizationGroup::where('id', $val6->option_key)->first();
                        $menu_items_options[$key6]['id'] = $group_data->id;
                        $menu_items_options[$key6]['group_name'] = $group_data->name;
                        $menu_items_options[$key6]['group_type'] = $group_data->ctype == 1?'multiple':'single';
                        $menu_items_options[$key6]['group_type_code'] = $group_data->ctype;
                        $menu_items_options[$key6]['select_max'] = $group_data->select_max;
                        $the_group_values = [];
                        foreach($group_data->values as $key8=>$val8){
                            $the_option = MenuItemOption::where(['option_value'=> $val8->id])->first();
                            $the_group_values[$key8]['item_id'] = $val8->id;
                            $the_group_values[$key8]['item_name'] = $val8->name;
                            $the_group_values[$key8]['price'] = !empty($the_option)?$the_option->added_price:0;
                        }
                        $menu_items_options[$key6]['group_values'] = $the_group_values;
                    }
                }
                $menu_items[$key5]['customization_groups'] = $menu_items_options;
            }
        }
        return $menu_items;
    }
    public function search(Request $request){
        $keyword = $request->keyword;
        $menuItems = MenuItem::where('name', 'like', "%$keyword%")->orderBy('id', 'desc')->skip(0)->take(15)->get();
        $menu_items = [];
        if(!empty($menuItems)) {
            foreach ($menuItems as $key5 => $val5) {
                /*print_r($val5->Menu);
                die();*/
                $menu_items[$key5]['id'] = $val5->id;
                $menu_items[$key5]['name'] = $val5->name;
                $menu_items[$key5]['imageUri'] = $val5->menu->cuisine->image;
                $menu_items[$key5]['description'] = $val5->description;
                $menu_items[$key5]['item_type_code'] = $val5->item_type;
                $menu_items[$key5]['item_type'] = $val5->item_type == 1 ? 'Veg' : 'Non Veg';
                $menu_items[$key5]['max_quantity'] = $val5->max_quantity;
                $menu_items[$key5]['price'] = $val5->price;
                $menu_items[$key5]['container_price'] = $val5->container_price;
                $menu_items[$key5]['offer_price'] = $val5->offer_price;

                $menu_items[$key5]['rating'] = 0;
                $menu_items[$key5]['quantity'] = 1;
                $menu_items[$key5]['discountPercentage'] = 0;

                $menu_items_options = [];

                $sql = "select distinct(`option_key`) as `option_key` from `menu_item_options` where `item_id`='" . $val5->id . "'  group by `option_key`";
                $group_values = DB::select($sql);
                if(!empty($group_values)){
                    foreach($group_values as $key6=>$val6){
                        $group_data = CustomizationGroup::where('id', $val6->option_key)->first();
                        $menu_items_options[$key6]['id'] = $group_data->id;
                        $menu_items_options[$key6]['group_name'] = $group_data->name;
                        $menu_items_options[$key6]['group_type'] = $group_data->ctype == 1?'multiple':'single';
                        $menu_items_options[$key6]['group_type_code'] = $group_data->ctype;
                        $menu_items_options[$key6]['select_max'] = $group_data->select_max;
                        $the_group_values = [];
                        foreach($group_data->values as $key8=>$val8){
                            $the_option = MenuItemOption::where(['option_value'=> $val8->id])->first();
                            $the_group_values[$key8]['item_id'] = $val8->id;
                            $the_group_values[$key8]['item_name'] = $val8->name;
                            $the_group_values[$key8]['price'] = !empty($the_option)?$the_option->added_price:0;
                        }
                        $menu_items_options[$key6]['group_values'] = $the_group_values;
                    }
                }
                $menu_items[$key5]['customization_groups'] = $menu_items_options;
            }
        }

        return response()->json([
            'success'=>1,
            'menu'=>$menu_items
        ]);
    }
}
