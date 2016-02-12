@extends('layouts.default')
@section('content')


<div id="login">
	<h1>Login</h1>

	<div class="form">
        {!! Form::open() !!}

		<div class="input-block">
			<div class="label">
                {!! Form::label('email', 'Email') !!}
			</div>
			<div class="input">
                {!! Form::text('email', null, ['class' => 'required login-email']) !!}
			</div>
		</div>

		<div class="input-block">
			<div class="label">
                {!! Form::label('password', 'Password') !!}
			</div>
			<div class="input">
                {!! Form::password('password', ['class' => 'required']) !!}
			</div>
		</div>

		<div class="input-block">
			<div class="submit">	
                {!! Form::submit('Login') !!}
			</div>
		</div>

        {!! Form::close() !!}
	</div>
</div>
@stop
