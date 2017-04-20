
@extends('partials.layout')

@section('content')

	<div class="col-sm-12">
		{!! Breadcrumbs::render('list-queue', $list) !!}
		<div class="page-header">
			<h1>Stats for {{ $list->title}}</h1>
		</div>

		@include('partials.queues.table')

		{{ $queues->links() }}
	</div>



@endsection