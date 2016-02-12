@extends('layouts.default')
@section('content')


@if (isset($title) && isset($messages))

	<h1>{{ $title }}</h1>

	{!! Form::open() !!}

	<div id="message_list">
	{!! formatMessages($messages, $two_level) !!}
	</div>

	{!! Form::close() !!}
@endif

@stop
