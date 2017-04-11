
@extends('partials.layout')

@section('header-files')
	<link rel="stylesheet" href="/css/themes.css">
@endsection
@section('content')

	<div class="page-header">
		<h1>{{ isset($message) ? 'Edit ' . $message->name : 'Create Message' }}</h1>
	</div>

	@if(isset($message))
		{!! Breadcrumbs::render('message', $message) !!}
		<form action="{{ action('MessageController@update', ['list' => $list->id, 'message' => $message->id]) }}" method="POST">
	@else
		{!! Breadcrumbs::render('create-message', $list) !!}
		<div class="alert alert-info">You will need to set the position once this message has been created</p></div>
		<form action="{{ action('MessageController@save', $list->id) }}" method="POST">
	@endif



	<div class="col-sm-10">
		<div class="form-group">
			<label for="">Name:</label>
			<input type="text" class="form-control" name="name" value={{ (isset($message)) ? $message->name : null }}>
		</div>

		<div class="form-group">
			<label for="">Subject</label>
			<input type="text" class="form-control" name="subject" value="{{ (isset($message)) ? $message->subject : null }}">
		</div>

		<div class="form-group">
			<label for="send_date">Send interval:</label>
			<input type="number" name="day_offset" class="form-control" value="{{ (isset($message)) ? $message->day_offset : null }}">
			<p class="help-block">The number of days after the campaign has started to send the email.</p>
		</div>


		<div class="form-group">
			<label for="start_time">Queue Time:</label>
			<input type="text" class="timepicker form-control" name="start_time" value="{{ (isset($message)) ? $message->message_time : null }}">
			<p class="help-block">The time to begin queueing emails.</p>
		</div>
		<div class="form-group">
			<textarea name="body">{{ (isset($message)) ? $message->content : null }}</textarea>
		</div>


		<input type="hidden" name="list_id" value="{{ $list->id }}">

		<input type="submit" value="{{ (isset($message)) ? 'Update' : 'Save' }}" class="btn btn-success">
		{{ csrf_field() }}


	</div>

	<div class="col-sm-2">
		@if(isset($message))
		@php
			$max_message_count  = $list->messages->count();

			$max_message_count++;

		@endphp
		<label for="position">Position:</label>
		<select name="position" class="form-control">
			@foreach($list->messages()->orderBy('position', 'asc')->get() as $mes)
				<option value="{{ $mes->position }}" {{ (isset($message) && $message->position == $mes->position) ? 'selected' : null }}>
					{{ (isset($message) && $mes->id == $message->id) ? '(Current)' : null }} {{ $mes->position }}: {{ $mes->name }}
				</option>
			@endforeach
			@if(isset($message) && $list->messages->count() != 1)
			<option value="{{ $max_message_count }}">{{ $max_message_count }}: Push to end of list</option>
			@endif
		</select>

		@endif


	</div>
	</form>
	<script>
		$( function() {
			$('.timepicker').timepicker({
				timeText: 'Time (24h)',
				controlType: 'select',
				oneLine: true,
			});
		    // $( "" ).datepicker({
		    //
		    // 	dateFormat: 'yy-mm-dd'
		    // });
		});

		tinymce.init({
		    selector: 'textarea',
		    skin_url: '/css/tinymce',
		    menubar: false,
		    plugins: [
		    	'image',
		    	'link',
		    ],
		    toolbar:  "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | link"

		 });
	</script>
@endsection