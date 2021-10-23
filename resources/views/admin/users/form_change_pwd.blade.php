<div class="form-group">
    {{--<a href="{{ url('backend/users')}}" class="btn btn-primary">Back</a>--}}
</div>
<div class="form-group">
    {!! Form::label('New Password', 'New Password') !!}
    {!! Form::password('password',['class'=>'form-control']) !!}
    {!! Form::hidden('change_password','1',['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Change Password', ['class' => 'btn btn-primary']) !!}
</div>