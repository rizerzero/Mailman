
@extends('partials.layout')

@section('content')
	<div class="col-sm-12">
		{!! Breadcrumbs::render('messages', $list) !!}
		<div class="page-header">
			<h1>Messages for {{ $list->title }} <a class="btn btn-success" href="{{ action('MessageController@create', $list->id) }}">Create New</a></h1>
		</div>

		@if(! $list->isActive() )
			<a class="btn btn-default" href="{{ action('MessageController@create', $list->id) }}">Create New</a>
		@endif
		@foreach($list->messages()->orderBy('send_date','asc')->get() as $message)
			@include('partials.messages.grid-element')

		@endforeach

	</div>
@endsection