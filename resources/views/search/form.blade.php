<div class="form">
	{!! Form::open(['url' => 'search', 'method' => 'GET', 'class' => 'ajax']) !!}
	{!! Form::input('search', 'q', null, ['placeholder' => 'Search...']) !!}
	{!! Form::close() !!}
</div>
