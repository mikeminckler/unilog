<div class="form">

	@if (isset($school)) 
        {!! Form::model($school, ['route' => ['schools.update', $school->id]]) !!}
	@else 
        {!! Form::open() !!}
	@endif

	<div class="input-block">
		<div class="label">
            {!! Form::label('school_name', 'School Name') !!}
		</div>
		<div class="input">
            {!! Form::text('school_name', null, ['class' => 'text-input required']) !!}
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('contact_name', 'Contact Name') !!}
		</div>
		<div class="input">
            {!! Form::text('contact_name', null, ['class' => 'text-input']) !!}
		</div>
	</div>


	<div class="input-block">
		<div class="label">
            {!! Form::label('email', 'Email') !!}
		</div>
		<div class="input">
            {!! Form::text('email', null, ['class' => 'text-input']) !!}
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('phone', 'Phone Number') !!}
		</div>
		<div class="input">
            {!! Form::text('phone', null, ['class' => 'text-input']) !!}
		</div>
	</div>


	<div class="input-block">
		<div class="label">
            {!! Form::label('website', 'Website') !!}
		</div>
		<div class="input">
            {!! Form::text('website', null, ['class' => 'text-input']) !!}
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('application_url', 'Application URL') !!}
		</div>
		<div class="input">
            {!! Form::text('application_url', null, ['class' => 'text-input']) !!}
		</div>
	</div>




	<div class="input-block">
		<div class="submit">
			@if (isset($school))
                {!! Form::submit('Update School') !!}
			@else 
                {!! Form::submit('Create School') !!}
			@endif
		</div>
	</div>


    {!! Form::close() !!}
</div>
