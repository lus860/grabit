@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/parcel-type/create')}}" class="btn btn-primary">Add Parcel Type</a>
    <a href="{{url('backend/carrier')}}" class="btn btn-primary"><i class="fa fa-angle-double-left"></i>  Back</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Parcel Type Name</th>
            <th>Parcel Type Status</th>
            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($parcelTypes)>0)
            @foreach ($parcelTypes as $val)
                <tr class="bg-info">
                    <td>{{$val->id}}</td>
                    <td>{{$val->parcel_name}}</td>
                    <td>{{$val->parcel_status}}</td>
                    <td>
                        <div id="myModal-{{$val->id}}" class="modal">
                            <div  class="modal-content">
                                <span onclick="modalNone({{$val->id}})" class="close">&times;</span>
                                <div style="display: inline-block; margin-left: 50%;">
                                    <p>Want to delete?</p>
                                    {!! Form::open(['method' => 'DELETE', 'route'=>['parcel-type.destroy', $val->id]]) !!}
                                    <button type="submit" class='btn btn-danger' style="background: red !important;">Yes</button>
                                    {!! Form::close() !!}</div>
                                <button  onclick="modalNone({{$val->id}})" class= 'btn btn-primary'>No</button>
                            </div>
                        </div>
                        <a href="{{route('parcel-type.edit',$val->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <button class='btn btn-danger' onclick="deletePopup({{$val->id}})" id="myBtn" style="background: red !important;"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No customization groups defined.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$parcelTypes->links()}}

        </nav>
    </div>
    <div class="row">
    </div>
@endsection
<script !src="">

    function deletePopup(id) {
        var modal = document.getElementById("myModal-"+id);
        modal.style.display = "block";
    }
    function modalNone(id) {
        let modal = document.getElementById("myModal-"+id);
        modal.style.display = "none";
    }

</script>
<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .fixing-position{
        margin-top: 2em;
    }
</style>
