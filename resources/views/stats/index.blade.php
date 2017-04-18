
@extends('partials.layout')

@section('content')
	<div class="col-sm-12">
    <div class="page-header">
    	<h1>Message Statistics</h1>
    </div>
	</div>

	@include('partials.stats.graph')
	@include('partials.stats.table')
@endsection