@extends('layouts.default')

@section('content')
<h1>Login</h1>

{{ Form::open(array('route' => 'sessions.store')) }}
<div class="form-group">
	{{ Form::label('username', 'Username:') }}
	<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		{{ Form::text('username', '2318318408', ['class' => 'form-control', 'placeholder' => 'Username']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('password', 'Password:') }}
	<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-lock"></i></span>
		{{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password']) }}
	</div>
</div>
<div class="form-group">
	{{ Form::submit('Login', ['class' => 'btn btn-primary']) }}
	{{ link_to_route('password_resets.create', 'Forgot your password?') }}
</div>
{{ Form::close() }}
@stop
