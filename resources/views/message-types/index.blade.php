@extends('layouts.default')
@section('content')

<h1>Log Category</h1>

<div class="section">
@if ($message_types->count())
	@foreach($message_types as $message_type)
		<div class="message-type">{!! link_to("message-types/edit/$message_type->id", $message_type->message_type_name) !!}</div>
	@endforeach
@else 
	<p>There are no Log Categories</p>
@endif
</div>

<div class="section">
	{!! link_to_route('message-types.create', 'Create Log Category', null, ['class' => 'button']) !!}
</div>

@stop
