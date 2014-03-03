@extends('layouts.default')

@section('content')
<h1>Reset Your Password</h1>
{{ Form::open(['route' => 'password_resets.store']) }}

<div class="form-group">
	{{ Form::label('email', 'Email:') }}<br>
	{{ Form::text('email', null, ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']) }}
</div>

<div class="form-group">
	{{ Form::submit('Reset', ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}

@if (Session::has('error'))

<p>{{ Session::get('reason') }}</p>

@elseif (Session::get('success'))

<p>Please check your email</p>

@endif

@stop
