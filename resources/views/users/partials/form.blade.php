<div class="form">

	@if(isset($user))
        {!! Form::model($user, ['route' => ['users.update', $user->id]]) !!}
	@else
        {!! Form::open() !!}
	@endif

	<div class="input-block">
		<div class="label">
            {!! Form::label('username', 'Name') !!}
		</div>
		<div class="input">
            {!! Form::text('username', null, ['class' => 'text-input required']) !!}
		</div>
	</div>


	<div class="input-block">
		<div class="label">
            {!! Form::label('email', 'Email') !!}
		</div>
		<div class="input">
			@if (isset($user))
                {!! Form::text('dummy_email', $user->email, ['class' => 'text-input', 'disabled' => 'disabled']) !!}
                {!! Form::text('email', null, ['class' => 'text-input hidden']) !!}
			@else
                {!! Form::text('email', null, ['class' => 'text-input required']) !!}
			@endif
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('password', 'Password') !!}
		</div>
		<div class="input">
			@if (isset($user))
                {!! Form::password('password', ['class' => 'text-input']) !!}
			@else
                {!! Form::password('password', ['class' => 'text-input required']) !!}
			@endif 
		</div>
	</div>

	<div class="input-block">
		<div class="label">
            {!! Form::label('password_confirmation', 'Confirm Password') !!}
		</div>
		<div class="input">
			@if (isset($user))
                {!! Form::password('password_confirmation', ['class' => 'text-input']) !!}
			@else
                {!! Form::password('password_confirmation', ['class' => 'text-input required']) !!}
			@endif 
		</div>
	</div>

	@if (auth()->user()->hasRole('admin'))
	<div class="input-block">
		<div class="label">
            {!! Form::label('admin', 'Administrator') !!}
		</div>
		<div class="input">
			@if(isset($user)) 
				@if ($user->hasRole('admin'))
                    {!! Form::checkbox('admin', null, true) !!}
				@else 
                    {!! Form::checkbox('admin') !!}
				@endif
			@else
                {!! Form::checkbox('admin') !!}
			@endif
		</div>
	</div>
	@endif


	<div class="input-block">
		<div class="submit">
			@if (isset($user))	
                {!! Form::submit('Update User') !!}
			@else
                {!! Form::submit('Create User') !!}
			@endif
		</div>
	</div>


    {!! Form::close() !!}
</div>
