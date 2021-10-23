@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    <div class="ssj-form-wrapper">
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Group Detail</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Group Name</label>
                            <p class="item-display">{{$group->name}}</p>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Group Type</label>
                            <p class="item-display">{{$group->getCtype()}}</p>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Maximum Selection</label>
                            <p class="item-display">{{$group->select_max}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Group Values</h3>
                <div class="row form-row the-menu-items">
                    <div id="original-item-content">
                        <div class="row item-option-container">
                            <div class="form-group col-lg-12 col-md-12">
                                @foreach($group->values as $key=>$value)
                                <p class="item-display">{{$key+1}}. {{$value->name}}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <a href="{{url('/backend/groups')}}" type="submit" class="btn btn-primary">Back to list</a>
                </div>
            </div>
        </div>
        </div>
@stop
