@extends('layouts.default')
@section('content')

<h1>Schools</h1>

<div class="section">
@if ($schools->count())
        @foreach($schools as $school)
                <div class="user">{!! link_to("schools/edit/$school->id", $school->school_name) !!}</div>
        @endforeach
@else 
        <p>There are no Schools</p>
@endif
</div>

<div class="section">
        {!! link_to_route('schools.create', 'Create School', null, ['class' => 'button']) !!}
</div>

@stop
