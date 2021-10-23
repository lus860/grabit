<?php

namespace App\Http\Controllers\Admin;

/*use App\Http\Requests\Request;*/

use App\Models\Delivery;
use App\Http\Controllers\Controller;
use App\Mail\AdminNotification;
use App\Models\AdminCenterNotification;
use App\Models\Notifications;
use App\Models\Setting;
use App\Models\Shipping;
use App\PendingOrders;
use App\Restaurant;
use App\Services\EmailService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Repositories\CrudRepository;
use App\Repositories\OrderRepository;
use App\Models\SSJUtils;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Route;

class BackendController extends Controller {
    protected $crud;
    public $statuses = array('New','Dispatched','Transit','Delivered');


    /**
     * @param CrudRepository $CrudRepo
     */
    public function __construct(CrudRepository $CrudRepo)
    {
        $this->crud = $CrudRepo;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        if (auth()->check() && auth()->user()->user_type == 1) {
            $title = 'Admin Dashboard';
        } else {
            $title = 'User Dashboard';
        }

        return view('admin.backend.dashboard', compact('title'));
    }

    /**
     * Show main categories
     * @return View
     */
    public function brand()
    {
        $filter = $this->crud->brandsFilter();
        $grid = $this->crud->brandsGrid();
        $title = $this->crud->getBrandTable();
        return view('admin.backend/content', compact('filter', 'grid', 'title'));
    }

    /**
     * Edit Main Category
     * @return string|View
     */
    public function brandsEdit()
    {
        if (request()->get('do_delete') == 1) return "not the first";
        $edit = $this->crud->brandsEdit();
        $title = $this->crud->getBrandTable();
        return view('admin.backend/content', compact('edit', 'title'));
    }

    /**
     * Show main categories
     * @return View
     */
    public function category()
    {
        $filter = $this->crud->catFilter();
        $grid = $this->crud->catGrid();
        $title = $this->crud->getCatTable();
        return view('admin.backend/content', compact('filter', 'grid', 'title'));
    }

    /**
     * Edit Main Category
     * @return string|View
     */
    public function categoryEdit()
    {
        if (request()->get('do_delete') == 1) return "not the first";
        $edit = $this->crud->catEdit();
        $title = $this->crud->getCatTable();
        return view('admin.backend/content', compact('edit', 'title'));
    }



    /**
     * Show products
     * @return View
     */
    public function products()
    {
        $filter = $this->crud->productsFilter();
        $grid = $this->crud->productsGrid();
        $title = $this->crud->getProductTable();
        return view('admin.backend/content', compact('filter', 'grid', 'title'));
    }

    /**
     * Edit Products
     * @return string|View
     */
    public function productsEdit()
    {
        if (request()->get('do_delete') == 1) return "not the first";
        $edit = $this->crud->productsEdit();
        $title = $this->crud->getProductTable();
        return view('admin.backend/content', compact('edit', 'title'));
    }

    /**
     * Show User profile
     * @return View
     */
    public function profile()
    {
        $grid = $this->crud->profileGrid();
        $title = auth()->user()->name;
        return view('admin.backend/content', compact('grid', 'title'));
    }

    /**
     * Edit User Profile
     * @return View
     */
    public function profileEdit()
    {
        $edit = $this->crud->profileEdit();
        $title = auth()->user()->name;
        return view('admin.backend/content', compact('edit', 'title'));
    }

    /**
     * Show all orders to admins
     * @return View
     */
    public function orders()
    {
        $filter = $this->crud->ordersFilter();
        $grid = $this->crud->ordersGrid();
        $title = $this->crud->getOrderTable();
        return view('admin.backend/content', compact('filter', 'grid', 'title'));
    }

    /**
     * Edit Orders
     * @return string|View
     */
    public function ordersEdit()
    {
        if (request()->get('do_delete') == 1) return "not the first";
        $edit = $this->crud->ordersEdit();
        $title = $this->crud->getOrderTable();
        return view('admin.backend/content', compact('edit', 'title'));
    }

    /**
     * Show customer orders
     * @return View
     */
    public function userOrders()
    {
        $grid = $this->crud->UserOrdersGrid();
        $title = $this->crud->getOrderTable();
        return view('admin.backend/content', compact('grid', 'title'));
    }

    /**
     * Edit customer orders
     * @return View
     */
    public function userOrdersEdit(OrderRepository $order, GateContract $gate)
    {
        if (request()->has('update')) {
            $result = $order->findBy('id', request()->input('update'));
        } else {
            $result = $order->findOrFail(request()->all());
        }
        //$this->authorize('view-resource', $order);
        //authorize via AuthorizesRequests trait.
        if ($gate->denies('view-resource', $result)) {
            return redirect('backend/profile')
                ->withErrors('Your are not authorized to view resources');
        }
        $edit = $this->crud->UsersOrdersEdit();
        $title = $this->crud->getOrderTable();
        return view('admin.backend/content', compact('edit', 'title'));
    }

    public function order_status($id){

        $order = SSJUtils::get_order($id);
        $providers = Shipping::all();
        $next_status = '';
        $button_label = '';

        $status_array = [];
        foreach($order['statuses'] as $status){
            $status_array[] = $status['status'];
        }

        //array('New','Dispatched','Transit','Delivered');
        if(in_array($this->statuses[0], $status_array)
            && !in_array($this->statuses[1], $status_array)
            && !in_array($this->statuses[2], $status_array)
            && !in_array($this->statuses[3], $status_array)){

            $next_status = $this->statuses[1];
            $button_label = "Dispatch";

        }elseif(in_array($this->statuses[0], $status_array)
                && in_array($this->statuses[1], $status_array)
                && !in_array($this->statuses[2], $status_array)
                && !in_array($this->statuses[3], $status_array)){
            $next_status = $this->statuses[2];
            $button_label = "Send to transit";

        }elseif(in_array($this->statuses[0], $status_array)
                && in_array($this->statuses[1], $status_array)
                && in_array($this->statuses[2], $status_array)
                && !in_array($this->statuses[3], $status_array)){
            $next_status = $this->statuses[3];
            $button_label = "Mark as delivered";
        }

        return view('order.status',
            [   'statuses'      =>$this->statuses,
                'order'         =>$order,
                'status_array'  =>$status_array,
                'providers'     =>$providers,
                'next_status'   => $next_status,
                'button_label'   => $button_label
            ]);
    }

    public function order_status_update(Request $request){
        $data = $request->all();
        $order_id = $data['order_id'];
        $status = $data['status'];

        $order_status = new OrderStatus();
        $order_status->status = $status;
        $order_status->order_id = $order_id;

        $order = SSJUtils::get_order($order_id);
        $token = $order['customer']['token'];
        $name = $order['customer']['name'];

        if($order_status->save()) {

            $order = Order::find($order_id);
            $order->status = $status;
            if($status == 'Transit') {
                $provider = $data['provider'];
                $order->shipping_id = $provider;
            }

            $order->save();

            $title = "Computerland";
            $big_text = "Hello $name, your order #$order_id updated with status $status";
            $message = "Hello $name, your order #$order_id updated with status $status";
            $sub_text = "";

            $notification = array(
                'title' => $title,
                'body' => $message,
            );

            SSJUtils::send_notification($notification, $token);

            if($status == 'Transit') {
                if($provider == '1'){
                    SSJUtils::send_order_to_delivery($order_id);
                }
            }
        }

        if(isset($delivery_id)) {
            return redirect(url('/') . '/foodie/backend/order-status/'.$order_id.'/?delivering=true&delivery='.$delivery_id);
        }else{
            return redirect(url('/') . '/foodie/backend/order-status/' . $order_id.'/?updated=true');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function all_notifications_for_admin(){
        $data['title']='Admin Notifications';
        $notification_for_order=Notifications::where(['courier_order_id'=>null])->orderBy('created_at','desc')->get();
        $notification_for_vendor=AdminCenterNotification::orderBy('created_at','desc')->get();

        $data['notifications_for_admin_table']=$notification_for_order->merge($notification_for_vendor);
        $data['notifications_for_admin_table']=$data['notifications_for_admin_table']->sortByDesc('created_at');
        $data['notifications_for_admin_table']=$data['notifications_for_admin_table']->paginate(25);

        return view('admin.admin_notification.index',$data);
    }
}
