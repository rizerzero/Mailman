
@extends('partials.layout')

@section('content')

	<div class="col-sm-12">
		{!! Breadcrumbs::render('lists') !!}
		<div class="page-header">
			<h1>All Lists <a class="btn btn-success" href="{{ action('ListController@create') }}">Create New</a></h1>
		</div>

		@include('partials.lists.table')
	</div>



@endsection