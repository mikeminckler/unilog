@extends('layouts.default')
@section('content')

@if (isset($school))

	<h1>School</h1>
	@include('schools/partials/form', ['school' => $school])
	
@endif

@stop
