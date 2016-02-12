@extends('layouts.default')

@section('content')

<h1>Create Log</h1>

@if (isset($student))
	@include('messages/partials/form', ['student' => $student])
@else 
	@include('messages/partials/form')
@endif

@stop
