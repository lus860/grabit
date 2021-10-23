{{--@admin--}}
    @component('admin.backend.link')
    @slot('link'){{ url('backend/admin') }}@endslot
    @slot('icon')icon fa fa-tachometer @endslot
    @slot('name')Dashboard @endslot
    @endcomponent
    {{--@component('backend.link')
    @slot('link'){{ url('backend/articles') }}@endslot
    @slot('icon')icon fa fa-pencil-square-o @endslot
    @slot('name')Custom Table @endslot
    @endcomponent--}}

    @component('admin.backend.link')
    @slot('link'){{ url('backend/users') }}@endslot
    @slot('icon')icon fa fa-user @endslot
    @slot('name')Customers @endslot
    @endcomponent

    @component('admin.backend.link')
    @slot('link'){{ url('backend/vendors') }}@endslot
    @slot('icon')icon fa fa-briefcase @endslot
    @slot('name')Vendors @endslot
    @endcomponent

    <li class="menu panel panel-default dropdown">
        <a data-toggle="collapse" href="#manage-dropdown">
            <span class="icon fa fa-motorcycle"></span>
            <span class="title">Manage Deliveries
                </span>
        </a>
        <!-- Dropdown-->
        <div id="manage-dropdown" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{url('/backend/manage/orders')}}">
                            <span class="icon fa fa-shopping-cart"></span>
                            <span class="title for-correct-badge">Orders</span>
                        </a>
                    </li>
                    <li><a href="{{url('/backend/manage/riders')}}">
                            <span class="icon fa fa-motorcycle"></span>
                            <span class="title for-correct-badge">Riders</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End Dropdown-->
    </li>

    @component('admin.backend.link')
        @slot('link'){{ url('backend/menu') }}@endslot
        @slot('icon')icon fa fa-list @endslot
        @slot('name')Menu @endslot
    @endcomponent

{{--    @component('backend.link')--}}
{{--    @slot('link'){{ url('backend/orders') }}@endslot--}}
{{--    @slot('icon')icon fa fa-shopping-cart @endslot--}}
{{--    @slot('name')Pending Orders @endslot--}}
{{--    @endcomponent--}}

    <li class="menu panel panel-default dropdown">
        <a data-toggle="collapse" href="#orders-dropdown">
            <span class="icon fa fa-shopping-cart"></span>
            <span class="title">Vendor Orders
            </span>
        </a>
        <!-- Dropdown-->
        <div id="orders-dropdown" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{url('/backend/orders')}}">
                            <span class="icon fa fa-shopping-cart"></span>
                            <span class="title for-correct-badge">Pending</span>
                            @if($badge_count_pending)
                            <span class="badge badge-secondary badge-custom">{{$badge_count_pending}}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{url('/backend/orders/cancelled')}}">
                            <span class="icon fa fa-times"></span>
                            <span class="title for-correct-badge">Cancelled</span>
                            @if($badge_count_cancelled)
                            <span class="badge badge-secondary badge-custom">{{$badge_count_cancelled}}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{url('/backend/orders/delivered')}}">
                            <span class="icon fa fa-check"></span>
                            <span class="title for-correct-badge">Delivered</span>
                        </a></li>
                    <li><a href="{{url('/backend/orders/ratings')}}">
                            <span class="icon fa fa-star-half-o"></span>
                            <span class="title for-correct-badge">Ratings</span>
                            @if($badge_count_orders_ratings)
                                <span class="badge badge-secondary badge-custom">{{$badge_count_orders_ratings}}</span>
                            @endif
                        </a></li>
                </ul>
            </div>
        </div>
        <!-- End Dropdown-->
    </li>
    <li class="menu panel panel-default dropdown">
        <a data-toggle="collapse" href="#courier-dropdown">
            <span class="icon ">
                <i class="fa fa-bicycle" aria-hidden="true"></i>
            </span>
            <span class="title">Courier Orders
                </span>
        </a>
        <!-- Dropdown-->
        <div id="courier-dropdown" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{url('/backend/courier-orders')}}">
                            <span class="icon fa fa-shopping-cart"></span>
                            <span class="title for-correct-badge">Pending</span>
                            @if($badge_count_courier_pending)
                                <span class="badge badge-secondary badge-custom">{{$badge_count_courier_pending}}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{url('/backend/courier-orders/cancelled')}}">
                            <span class="icon fa fa-times"></span>
                            <span class="title for-correct-badge">Cancelled</span>
                            @if($badge_count_courier_cancelled)
                                <span class="badge badge-secondary badge-custom">{{$badge_count_courier_cancelled}}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{url('/backend/courier-orders/delivered')}}">
                            <span class="icon fa fa-check"></span>
                            <span class="title for-correct-badge">Delivered</span>
                        </a></li>
                    <li><a href="{{url('/backend/courier-orders/ratings')}}">
                            <span class="icon fa fa-star-half-o"></span>
                            <span class="title for-correct-badge">Ratings</span>
                            @if($badge_count_courier_orders_ratings)
                                <span class="badge badge-secondary badge-custom">{{$badge_count_courier_orders_ratings}}</span>
                            @endif
                        </a></li>
                </ul>
            </div>
        </div>
        <!-- End Dropdown-->
    </li>
    @component('admin.backend.link')
        @slot('link'){{ url('backend/loyalty') }}@endslot
        @slot('icon')icon fa fa-star @endslot
        @slot('name')Loyalty program @endslot
    @endcomponent
<li class="menu panel panel-default dropdown">
    <a data-toggle="collapse" href="#adverts"><span class="fa fa-list-alt icon" aria-hidden="true"></span><span class="title">Adverts</span></a>
    <!-- Dropdown-->
    <div id="adverts" class="panel-collapse collapse">
        <div class="panel-body">
            <ul class="nav navbar-nav">
                {{--                    <li><a href="{{url('/backend/groups')}}"><span class="icon fa fa-cogs"></span> <span class="title">Customization Groups</span></a></li>--}}
                <li><a href="{{url('/backend/manage-categories')}}"><span class="fa fa-pencil-square-o icon" aria-hidden="true"></span><span class="title">Manage Categories</span></a></li>
                <li><a href="{{url('/backend/manage-listings')}}"><span class="fa fa-bars icon" aria-hidden="true"></span><span class="title">Manage Listings</span></a></li>
            </ul>
        </div>
    </div>
    <!-- End Dropdown-->
</li>
    <li class="menu panel panel-default dropdown">
        <a data-toggle="collapse" href="#settings"><span class="icon fa fa-wrench"></span><span class="title">Settings</span></a>
        <!-- Dropdown-->
        <div id="settings" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="nav navbar-nav">
{{--                    <li><a href="{{url('/backend/groups')}}"><span class="icon fa fa-cogs"></span> <span class="title">Customization Groups</span></a></li>--}}
                    <li><a href="{{url('/backend/cities')}}"><span class="icon fa fa-cogs"></span> <span class="title">Cities</span></a></li>
                    <li><a href="{{url('/backend/areas')}}"><span class="icon fa fa-cogs"></span> <span class="title">Areas</span></a></li>
                    <li><a href="{{url('/backend/cuisines')}}"><span class="icon fa fa-cogs"></span> <span class="title">Cuisines</span></a></li>
{{--                    <li><a href="{{url('/backend/menu-categories')}}"><span class="icon fa fa-cogs"></span> <span class="title">Menu Categories</span></a></li>--}}
                    <li><a href="{{url('/backend/deliveries')}}"><span class="icon fa fa-cogs"></span> <span class="title">Delivey Options</span></a></li>
                    <li><a href="{{url('/backend/carrier')}}"><span class="icon fa fa-cogs"></span> <span class="title">Courier Settings</span></a></li>
                    <li><a href="{{url('/backend/vendor-type')}}"><span class="icon fa fa-cogs"></span> <span class="title">Vendor types</span></a></li>
                    <li><a href="{{url('/backend/app-settings')}}"><span class="icon fa fa-cogs"></span> <span class="title">App Settings</span></a></li>

                    <li><a href="{{url('/backend/monthly-price')}}"><span class="icon fa fa-cogs"></span> <span class="title">Monthly price</span></a></li>
                    <li><a href="{{url('/backend/yearly-price')}}"><span class="icon fa fa-cogs"></span> <span class="title">Yearly price</span></a></li>
                </ul>
            </div>
        </div>
        <!-- End Dropdown-->
    </li>

@component('admin.backend.link')
    @slot('link'){{ url('backend/web-images') }}@endslot
    @slot('icon')icon fa fa-picture-o @endslot
    @slot('name')Web images @endslot
@endcomponent
<li class="menu panel panel-default dropdown">
    <a data-toggle="collapse" href="#reports"><span class="icon fa fa-file-pdf-o"></span><span class="title">Reports</span></a>
    <!-- Dropdown-->
    <div id="reports" class="panel-collapse collapse">
        <div class="panel-body">
            <ul class="nav navbar-nav">
                <li><a href="{{url('/backend/reports/couriers')}}">
                        <span class="icon"></span>
                        <span class="title">Couriers</span></a></li>
            </ul>

            <ul class="nav navbar-nav">
                <li><a href="{{url('/backend/reports/credit')}}">
                        <span class="icon"></span>
                        <span class="title">Credit</span></a></li>
            </ul>
        </div>
    </div>
    <!-- End Dropdown-->
</li>
{{--@endadmin--}}
{{--@user--}}
{{--    @component('admin.backend.link')--}}
{{--    @slot('link'){{ url('backend/user') }}@endslot--}}
{{--    @slot('icon')icon fa fa-tachometer @endslot--}}
{{--    @slot('name')User Dashboard @endslot--}}
{{--    @endcomponent--}}
{{--    @component('admin.backend.link')--}}
{{--    @slot('link'){{ url('backend/profile') }}@endslot--}}
{{--    @slot('icon')icon fa fa-eye @endslot--}}
{{--    @slot('name')User Profile @endslot--}}
{{--    @endcomponent--}}
{{--    @component('admin.backend.link')--}}
{{--    @slot('link'){{ url('backend/user-orders') }}@endslot--}}
{{--    @slot('icon')icon fa fa-money @endslot--}}
{{--    @slot('name')My Orders @endslot--}}
{{--    @endcomponent--}}
{{--@enduser--}}
