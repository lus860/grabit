<?php

namespace App\Http\Composers;

use App\Models\CourierOrders;
use App\Models\AdminCenterNotification;
use App\Models\Notifications;
use App\Models\RatingsVendors;
use App\Models\PendingOrders;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\View\View;

class BadgeSideBarComposer
{
    public function __construct()
    {

    }

    /**
     * Share global data to all views.
     */
    public function compose(View $view)
    {
        $admin_notification_header_badge=Notifications::where(
            ['admin_center'=>null,'courier_order_id'=>null])->orderBy('created_at','desc')->get();
        $admin_vendor_offline_badge=AdminCenterNotification::where('vendor_offline','!=',null)->where('status',null)
            ->orderBy('created_at','desc')->get();
        $menu_items= AdminCenterNotification::where('item_not_available','!=',null)->where('status',null)
            ->orderBy('created_at','desc')->get();
        $menu_items_options= AdminCenterNotification::where('product_not_available','!=',null)->where('status',null)
            ->orderBy('created_at','desc')->get();
        $order_overdue= AdminCenterNotification::where('overdue_order','!=',null)->where('status',null)
            ->orderBy('created_at','desc')->get();

        $total = $admin_notification_header_badge->count();
        $total += $admin_vendor_offline_badge->count();
        $total += $menu_items->count();
        $total += $menu_items_options->count();
        $total += $order_overdue->count();
        $data['total_for_admin_center_badge']=$total;

        $admin_notification_header_badge=$admin_notification_header_badge->take(5);
        $admin_vendor_offline_badge=$admin_vendor_offline_badge->take(5);
        $menu_items=$menu_items->take(5);
        $menu_items_options=$menu_items_options->take(5);
        $order_overdue=$order_overdue->take(5);
        $admin_notification_header_badge=$admin_notification_header_badge->merge($admin_vendor_offline_badge);
        $admin_notification_header_badge=$admin_notification_header_badge->merge($menu_items);
        $admin_notification_header_badge=$admin_notification_header_badge->merge($menu_items_options);
        $admin_notification_header_badge=$admin_notification_header_badge->merge($order_overdue);

        $data['admin_notifications_list_header']=$admin_notification_header_badge->sortByDesc('created_at');

        $data['badge_count_pending']=PendingOrders::where(function ($query){
            $query->where('status','!=','pending');
            $query->where('status','!=','Cancelled');
            $query->where('status','!=','status_303');
            $query->where('created_at','<', Carbon::now()->subMinutes(2));
            $query->where('seen','=',null);
        })->get()->count();
        $data['badge_count_cancelled']=PendingOrders::where(function ($query){
            $query->where('status','Cancelled');
            $query->where('seen','=',null);
        })->get()->count();
        $data['badge_count_delivered']=PendingOrders::where(function ($query){
            $query->where('status','status_303');
            $query->where('seen','=',null);
        })->get()->count();
        $data['badge_count_courier_pending']=CourierOrders::where(function ($query){
            $query->where('status','!=','Cancelled');
            $query->where('status','!=','status_303');
            $query->where('status','!=','status_304');
            $query->where('created_at','<', Carbon::now()->subMinutes(2));
            $query->where('seen','=',null);
        })->get()->count();

        $data['badge_count_courier_cancelled']=CourierOrders::where(function ($query){
            $query->where('status','Cancelled');
            $query->where('seen','=',null);
        })->get()->count();
        $data['badge_count_courier_delivered']=CourierOrders::where(function ($query){
            $query->where('status','status_303');
            $query->orWhere('status','status_304');
            $query->where('seen','=',null);
        })->get()->count();
        $data['badge_count_orders_ratings']=RatingsVendors::where(function ($query){
            $query->where('seen',null);
            $query->where('vendor_id','!=',null);
        })->get()->count();
        $data['badge_count_courier_orders_ratings']=RatingsVendors::where(function ($query){
            $query->where('seen',null);
            $query->where('vendor_id',null);
        })->get()->count();

        $view->with($data);

    }
}
