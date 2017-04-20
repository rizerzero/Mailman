
@extends('partials.layout')

@section('content')

	<div class="col-sm-12">
		{!! Breadcrumbs::render('list-stats', $list) !!}
		<div class="page-header">
			<h1>Stats for {{ $list->title}}</h1>
		</div>

		@include('partials.stats.graph')
	</div>



@endsection