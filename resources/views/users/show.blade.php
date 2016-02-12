@extends('layouts.default')
@section('content')

@if (isset($user))

	<h1>Update User</h1>
	@include('users/partials/form', ['user' => $user])
	
@endif

@stop
