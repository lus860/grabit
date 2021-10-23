<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-expand-toggle">
                <i class="fa fa-bars icon"></i>
            </button>
            <ol class="breadcrumb navbar-breadcrumb" style="margin-bottom: 0px!important;">
{{--                @admin--}}
                    <li>Admin Panel</li>
{{--                @endadmin--}}
{{--                @user--}}
{{--                    <li>User Panel</li>--}}
{{--                @enduser--}}
                {{--<li class="active">{{Auth::user()->email}}</li>--}}
                {{--<li class="active"><a href="{{ url('cms') }}">Back to Site</a></li>--}}
            </ol>
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-th icon"></i>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-bell-o" aria-hidden="true"></i>></i>
            </button>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false">{{Auth::user()->name}}<span class="caret"></span></a>
                <ul class="dropdown-menu animated fadeInDown">
                    <li>
                        <div class="profile-info">
                            <h4 class="username">{{Auth::user()->name}}</h4>

                            <p>{{Auth::user()->email}}</p>

                            <div class="btn-group margin-bottom-2x" role="group">
                                <button type="button" class="btn btn-default"><i class="fa fa-user"></i>
{{--                                    @admin--}}
                                    <a href="{{ url('backend/profile') }}"><?= _('Profile')?></a>
{{--                                    @endadmin--}}
{{--                                    @user--}}
{{--                                    <a href="{{ url('backend/user') }}"><?= _('Profile')?></a>--}}
{{--                                    @enduser--}}
                                </button>
                                <button type="button" class="btn btn-default"><i class="fa fa-sign-out"></i>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <?= _('Logout')?>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-times icon"></i>
            </button>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false">
                    <i class="fa fa-bell-o fixing-position" aria-hidden="true"></i>
                    @if(isset($total_for_admin_center_badge) && $total_for_admin_center_badge)
                        <span class="badge badge-secondary badge-custom">{{$total_for_admin_center_badge}}</span>
                    @endif
                </a>
                <ul class="dropdown-menu animated fadeInDown" style="width: 500px">
                    <li>
                        <div class="notification-header">
                            <span>
                               <i class="fa fa-bell-o" aria-hidden="true"></i>
                            @if(isset($total_for_admin_center_badge) && $total_for_admin_center_badge)
                                    {{$total_for_admin_center_badge}}
                                @else
                                    0
                                @endif
                            new notification(s)
                            </span>
{{--                            <span class="make-all-notifications-as-read" style="float: right;padding-right: 5px;cursor: pointer">--}}
{{--                                check all read--}}
{{--                            </span>--}}
                        </div>
                        <div class="profile-info notification-info">
                            <div class="row">
                                @if(isset($admin_notifications_list_header) && $admin_notifications_list_header->count())
                                    @foreach($admin_notifications_list_header as $value)
                                        @if(isset($value->get_overdue_order) && $value->get_overdue_order)
                                            <div class="col-md-10 notification-card">
                                                <p class="notification-title text-left text-info">
                                                    An order is overdue
                                                </p>
                                                <p class="notification-message text-left text-info">
                                                    An order of “{{config('api.order.order_type')[$value->get_overdue_order->order_type]}}” from “{{$value->get_overdue_order->get_vendor->name}}” has been overdue now, please help speed up completion to avoid unhappy customers.
                                                </p>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="btn btn-warning check-admin-notify">
                                                    <i class="fa fa-eye"></i>
                                                    <i class="fa fa-check hide"></i>
                                                    <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification">
                                                </a>
                                            </div>
                                        @endif
                                        @if(isset($value->menu_item_option) && $value->menu_item_option)
                                            <div class="col-md-10 notification-card">
                                                <p class="notification-title text-left text-info">
                                                    Menu item not available for over 48 hours
                                                </p>
                                                <p class="notification-message text-left text-info">
                                                    “{{$value->menu_item_option->menuOption->menuItem->Menu->get_vendor->name}}” has not got “{{$value->menu_item_option->value}}” on their “{{$value->menu_item_option->menuOption->name}}” menu
                                                </p>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="btn btn-warning check-admin-notify">
                                                    <i class="fa fa-eye"></i>
                                                    <i class="fa fa-check hide"></i>

                                                    <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification">
                                                </a>
                                            </div>
                                        @endif
                                        @if(isset($value->menu_item) && $value->menu_item)
                                            <div class="col-md-10 notification-card">
                                                <p class="notification-title text-left text-info">
                                                    Menu item not available for over 48 hours
                                                </p>
                                                <p class="notification-message text-left text-info">
                                                    {{$value->menu_item->Menu->get_vendor->name}}” has not got “{{$value->menu_item->name}}” on their items menu
                                                </p>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="btn btn-warning check-admin-notify">
                                                    <i class="fa fa-eye"></i>
                                                    <i class="fa fa-check hide"></i>

                                                    <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification">
                                                </a>
                                            </div>
                                        @endif
                                        @if(isset($value->order) && $value->order)
                                        <div class="col-md-10 notification-card">
                                            <p class="notification-title text-left text-info">
                                                Pending order for “schedule = {{$value->order->schedule}}” not accepted by vendor
                                            </p>
                                            <p class="notification-message text-left text-info">
                                                “{{$value->order->get_vendor->name}}” has not accepted an order of transaction ID
                                                “{{$value->order->transaction_id}}” posted at “{{$value->order->created_at}}”
                                            </p>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="btn btn-warning check-admin-notify">
                                                <i class="fa fa-eye"></i>
                                                <i class="fa fa-check hide"></i>

                                                <input type="hidden" data-type="order" data-id="{{$value->id}}" class="send-ajax-for-read-notification">
                                            </a>
                                        </div>
                                        @endif
                                        @if(isset($value->vendor) && $value->vendor)
                                                <div class="col-md-10 notification-card">
                                                    <p class="notification-title text-left text-info">
                                                        Offline vendor for over 24 hours
                                                    </p>
                                                    <p class="notification-message text-left text-info">
                                                        “{{$value->vendor->name}}” has been offline for over 24 hours, kindly contact the vendor to check if all is good
                                                    </p>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:void(0)" class="btn btn-warning check-admin-notify">
                                                        <i class="fa fa-eye"></i>
                                                        <i class="fa fa-check hide"></i>
                                                        <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification">
                                                    </a>
                                                </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <div class="btn-group margin-bottom-2x" role="group">
                                <a href="/backend/all-notifications-for-admin" class="btn btn-default">
                                    All notifications
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
