
@extends('partials.layout')

@section('content')
    <div class="page-header">
    	<h1>View Queues</h1>
    	<p>Queues are sorted by their parent message send time.</p>
    </div>
	@include('partials.queues.filters')
	@include('partials.queues.table')

	{{ $queues->appends(Request::only('status','list'))->links() }}
@endsection