@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/menu-categories" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Category Detail</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-4 col-md-4">
                        <label>Cuisine Name</label>
                        <input placeholder="Eg. Pizzas" value="" required name="name" type="text" class="form-control" />
                    </div>

                    <div class="form-group col-lg-4 col-md-4">
                        <label>Image</label>
                        <input required name="image" type="file" class="form-control" />
                    </div>

                    <div class="form-group col-lg-4 col-md-4">
                        <label>Icon</label>
                        <select name="icon" id="menu_icon" class="form-control" required>
                            <option value="">Select Icon</option>
                            <option value="{{url('/icons')}}/burger.png">Burger Icon</option>
                            <option value="{{url('/icons')}}/chinese.png">Chinese Food Icon</option>
                            <option value="{{url('/icons')}}/dessert.png">Dessert Icon</option>
                            <option value="{{url('/icons')}}/drink.png">Drinks Icon</option>
                            <option value="{{url('/icons')}}/gravy.png">Indian Gravy Icon</option>
                            <option value="{{url('/icons')}}/grill.png">Grill Icon</option>
                            <option value="{{url('/icons')}}/indian.png">Indian Icon</option>
                            <option value="{{url('/icons')}}/italian.png">Italian Food Icon</option>
                            <option value="{{url('/icons')}}/pizza.png">Pizza Icon</option>
                            <option value="{{url('/icons')}}/poutine.png">Poutine Icon</option>
                            <option value="{{url('/icons')}}/salad.png">Salad Icon</option>
                            <option value="{{url('/icons')}}/sea.png">Sea Food Icon</option>
                            <option value="{{url('/icons')}}/side-dish.png">Side Dish Icon</option>
                            <option value="{{url('/icons')}}/starter.png">Starters Icon</option>
                            <option value="{{url('/icons')}}/swahili.png">Swahili Food Icon</option>
                            <option value="{{url('/icons')}}/wrap.png">Wraps Icon</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@stop
