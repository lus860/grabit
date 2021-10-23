@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Target</th>
            <th>Type</th>
            <th>Title</th>
            <th>Message</th>
            <th>Created at</th>
        </tr>
        </thead>
        <tbody>
        @if(count($alerts)>0)
            @foreach ($alerts as $alert)
                <tr class="bg-info">
                    <td>
                        @php
                            if (substr(trim($alert->users), -1) == ','){
                                echo substr(trim($alert->users), 0, -1);
                            }else{ echo trim($alert->users);}
                        @endphp
                    </td>
                    <td>{{$alert->type}}</td>
                    <td>{{$alert->title??'N/A'}}</td>
                    <td>{{$alert->message??'N/A'}}</td>
                    <td>{{$alert->created_at??'N/A'}}</td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No Data.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$alerts->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/custom.css')}}" >
@endpush
