
@extends('partials.layout')

@push('header')

	<link rel="stylesheet" href="/css/themes.css">
@endpush
@section('content')

	<div class="page-header">
		<h1>{{ isset($message) ? 'Edit ' . $message->name  : 'Create Message' }} @if(isset($message))<a class="btn btn-success" href="{{ action("MessageController@create", $list->id) }}">Create New</a>@endif</h1>
	</div>

	@if(isset($message))
		{!! Breadcrumbs::render('message', $message) !!}
		<form action="{{ action('MessageController@update', ['list' => $list->id, 'message' => $message->id]) }}" method="POST">
	@else
		{!! Breadcrumbs::render('create-message', $list) !!}
		<div class="alert alert-info">You will need to set the position once this message has been created</p></div>
		<form action="{{ action('MessageController@save', $list->id) }}" method="POST">
	@endif

	<div>

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#editor" aria-controls="editor" role="tab" data-toggle="tab">Editor</a></li>
	    <li role="presentation"><a href="#docs" aria-controls="docs" role="tab" data-toggle="tab">Documentation</a></li>
	    <li role="presentation"><a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a></li>
	    <li role="presentation"><a href="#test" aria-controls="test" role="tab" data-toggle="tab">Send test email</a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="editor">
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
			<div class="alert alert-warning">Please make sure images are hosted on a reliable CDN or capable server. I have created a bucket on S3 called "mailman-media" that is meant to contain this.</div>
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

	    </div>
	    <div role="tabpanel" class="tab-pane" id="docs">

			<h1>Template Variables</h1>
			<h2>Usage</h2>

			<p>In order to display a variable in the message, you must use double curly brackets before and after like so <code>&#123;&#123; $model->property &#125;&#125;</code>.</p>

			<p>Examples:</p>

			<p><code>&#123;&#123; $entry->name &#125;&#125;</code></p>
			<p><code>&#123;&#123; $list->title &#125;&#125;</code></p>
			<p><code>&#123;&#123; $mailmessage->name &#125;&#125;</code></p>
	    	<h2>Models and Properties</h2>
	    	<ul>
		    	<li>$entry
		    		<ul>
		    			<li><strong>name</strong> Name of the recipeint</li>
		    			<li><strong>email</strong> Email of the recipeint</li>
		    		</ul>
		    	</li>
		    	<li>$list
		    		<ul>
		    			<li><strong>title</strong> Title of the list</li>
		    			<li><strong>description</strong> Description of the list</li>
		    		</ul>
		    	</li>
		    	<li>$mailmessage
					<ul>
						<li><strong>name</strong></li>
						<li><strong>content</strong></li>
						<li><strong>subject</strong></li>
					</ul>
		    	</li>

		    </ul>

		    <h2>Template Tags</h2>

		    <h2>Features with Image</h2>

		    <p><code>&#64;include('emails.partials.features-left-radio-right')</code></p>

			<p><em>The above will output</em></p>
			<div class="well">
			@include('emails.partials.features-left-radio-right')
			</div>

			<h2>CTA Button</h2>
			<p>Place a centered button that links to a page</p>
			<p><strong>Arguments</strong></p>
			<pre>
				$alignment = Table column alignment attribute. - https://www.w3schools.com/tags/att_td_align.asp
				$link = The link to navigate to
				$text = The text to use for the button
			</pre>
			<p><strong>Usage</strong></p>
			<p><code>&#64;include('emails.partials.cta-button', ['alignment' => 'center', 'link' => 'http://example.com', 'text' => 'Badass Example'])</code></p>

			<p>I can't get an example of this to look correctly on the actual website, but it works...make sure to double check in the message preview.</p>
	    </div>

	    <div role="tabpanel" class="tab-pane" id="stats">
	    	@if(isset($message))
			@include('partials.stats.graph')
			@endif
	    </div>
	    <div role="tabpanel" class="tab-pane" id="test">
	    	@if(isset($message))
			@include('partials.messages.test')
			@endif
	    </div>
	  </div>

	</div>


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
			relative_urls : false,
			remove_script_host : false,
		    selector: 'textarea',
		    skin_url: '/css/tinymce',
		    entity_encoding: "raw",
		    menubar: false,
		    force_p_newlines : false,
		    height: "600",
		    plugins: [
		    	'image',
		    	'link',
		    	'advlist',
		    	'lists',
		    	'code',
		    ],
		    toolbar:  "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | bullist | code"

		 });
	</script>
@endsection