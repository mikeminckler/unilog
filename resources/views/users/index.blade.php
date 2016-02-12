@extends('layouts.default')
@section('content')

<h1>Users</h1>

<div class="section">
@if ($users->count())
	@foreach($users as $user)
		<div class="user">{!! link_to("users/$user->id", $user->username) !!}</div>
	@endforeach
@else 
	<p>There are no Users</p>
@endif
</div>

<div class="section">
	{!! link_to_route('users.create', 'Create User', null, ['class' => 'button']) !!}
</div>

@stop
