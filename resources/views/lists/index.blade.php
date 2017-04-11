
@extends('partials.layout')

@section('content')

	<div class="col-sm-12">
		{!! Breadcrumbs::render('lists') !!}
		<div class="page-header">
			<h1>All Lists</h1>
		</div>

		@include('partials.lists.table')
	</div>



@endsection