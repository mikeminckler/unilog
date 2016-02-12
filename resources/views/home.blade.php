@extends('layouts.default')
@section('content')

<h1>Logs</h1>

@if($messages->count())

	{!! Form::open() !!}

	<div id="message_list">
	{!! formatMessages($messages) !!}
	</div>

	{!! Form::close() !!}

	<div class="pagination pagination-centered">
		{!! $messages->render() !!}
	</div>

@else 

	<p>There are no logs for this time period</p>
	{!! link_to_route('messages.new', 'Create Log', null, ['class' => 'button']) !!}

@endif


@stop
