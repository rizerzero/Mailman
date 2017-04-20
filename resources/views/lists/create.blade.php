
@extends('partials.layout')

@section('content')
	{!! Breadcrumbs::render('create-list') !!}
	<div class="col-sm-8 col-sm-offset-2">
    <div class="page-header">
    	<h1>Create a new list</h1>
    </div>

	@include('partials.lists.form')
	</div>
@endsection