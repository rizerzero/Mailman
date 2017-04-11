
@extends('partials.layout')

@section('content')
	{!! Breadcrumbs::render('create-list') !!}
    <div class="page-header">
    	<h1>Create a new list</h1>
    </div>

	@include('partials.lists.form')

@endsection