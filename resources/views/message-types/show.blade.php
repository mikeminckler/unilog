@extends('layouts.default')
@section('content')

@if (isset($message_type))

	<h1>Log Category</h1>
	
	@include('message-types/partials/form', ['message_type' => $message_type])

@endif

@stop
