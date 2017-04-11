
@extends('partials.layout')

@section('content')

	{!! Breadcrumbs::render('lists') !!}
	<div class="page-header">
		<h1>All Lists</h1>
	</div>
      @include('partials.lists.table')

@endsection