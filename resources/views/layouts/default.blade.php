<!doctype html>
<html>
<head>
	<meta charset="utf-8">

	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300|Source+Sans+Pro:400,200,300|Source+Code+Pro:400,200|Poiret+One' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
    {!! Html::style('css/stylesheet.css') !!}

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
    {!! Html::script('js/jquery.js') !!}

</head>

<body>

	<div id="feedback_container">
		<div id="feedback">

		@if (session()->has('success'))
			<div class="success-header">Success</div>
			<ul class="feedback">
				<li class="feedback success">{!! session()->get('success') !!}</li>
			</ul>
		@endif

		@if(session()->has('error'))
			<div class="error-header">Error</div>
			<ul class="feedback">
				<li class="feedback error">{!! session()->get('error') !!}</li>
			</ul>
		@endif

		@if(isset($success))
			<div class="success-header">Success</div>
			<ul class="feedback">
				<li class="feedback success">{!! $success !!}</li>
			</ul>
		@endif

		@if(isset($error))
			<div class="error-header">Error</div>
			<ul class="feedback">
				<li class="feedback error">{!! $error !!}</li>
			</ul>
		@endif

		@if (count($errors->all()) > 0)
			<div class="error-header">Error</div>
			<ul class="feedback">

				@foreach($errors->all() as $error)
					<li class="feedback error">{!! $error !!}</li>
				@endforeach

			</ul>
		@endif

		</div>
	</div>


	<div id="container">

		<div id="menu_container">

			<div id="home_link" class="">{!! link_to_route('home', 'UNI.LOG') !!}</div>
			@if (auth()->user())

				<div id="search">
					@include('search/form')
				</div>
				<div class="top-menu-item">{!! link_to_route('home', 'Logs', null, ['class' => 'top-menu-item'])  !!}</div>
				<div class="top-menu-item">{!! link_to_route('emails.send', 'Email', null, ['class' => 'top-menu-item'])  !!}</div>
				<div class="top-menu-item">{!! link_to_route('schools', 'Schools', null, ['class' => 'top-menu-item']) !!}</div>

				<div class="top-menu-item logout">{!! link_to_route('logout', 'Logout', null, ['class' => 'top-menu-item']) !!}</div>

				@if (auth()->user()->hasRole('admin'))
					<div id="menu_link" class="clickable">Admin</div>
					<div id="menu">
						<div class="menu-item">{!! link_to_route('message-types', 'Categories', null, ['class' => 'menu-item'])  !!}</div>
						<div class="menu-item">{!! link_to_route('users', 'Users', null, ['class' => 'menu-item']) !!}</div>
					</div>
				@endif

				<div id="dates">

					<div id="start_date"><input type="text" name="start-date" class="datepicker start-date" value="{{ date('Y-m-d', strtotime(session()->get('start_date'))) }}" /></div>
					
					<div id="end_date"><input type="text" name="end-date" class="datepicker end-date" value="{{ date('Y-m-d', strtotime(session()->get('end_date'))) }}" /></div>

				</div>


			@endif
		</div>

		<div id="content">

        {!! Form::open() !!}
		<div id="search_results"></div>
        {!! Form::close() !!}

		@yield('content')

		</div>
	</div>


</body>
</html>
