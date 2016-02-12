@extends('layouts.default')

@section('content')

@if (isset($message))

<h1>Log {{ sprintf('%04d', $message->id) }}</h1>

	@include('messages/partials/form', ['message' => $message])
	
@endif

@stop
