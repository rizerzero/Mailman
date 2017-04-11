
@extends('partials.layout')

@section('content')
    <div class="page-header col-sm-12">
    	<h1>View Queues</h1>
    	<p>Queues are sorted by their parent message send time.</p>
    </div>
	@include('partials.queues.filters')
	<div class="col-sm-12">
		@include('partials.queues.table')
		<div class="text-center">
			{{ $queues->appends(Request::only('status','list'))->links() }}
		</div>

	</div>



@endsection