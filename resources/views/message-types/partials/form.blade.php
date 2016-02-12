<div class="form">

	@if (isset($message_type))
		{!! Form::model($message_type, ['route' => ['message-types.update', $message_type->id]]) !!}
	@else
		{!! Form::open() !!}
	@endif

	<div class="input-block">
		<div class="label">
            {!! Form::label('message_type_name', 'Log Type:') !!}
		</div>
		<div class="input">
            {!! Form::text('message_type_name', null, ['class' => 'text-input']) !!}
		</div>
	</div>

	<div class="input-block">
                <div class="submit">
			@if (isset($message_type)) 
                {!! Form::submit('Update Log Category') !!}
			@else  
                {!! Form::submit('Create Log Category') !!}
			@endif
		</div>
	</div>

    {!! Form::close() !!}

</div>
