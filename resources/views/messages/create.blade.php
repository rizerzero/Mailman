@inject('carbon', 'Carbon\Carbon')
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
		<form action="{{ action('MessageController@save', $list->id) }}" method="POST">
	@endif

	  <!-- Nav tabs -->
		<div class="col-sm-12">
			<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#editor" aria-controls="editor" role="tab" data-toggle="tab">Editor</a></li>
			<li role="presentation"><a href="#docs" aria-controls="docs" role="tab" data-toggle="tab">Documentation</a></li>
			<li role="presentation"><a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a></li>
			<li role="presentation"><a href="#test" aria-controls="test" role="tab" data-toggle="tab">Send test email</a></li>
			</ul>
		</div>
	  <!-- Tab panes -->
	  <div class="tab-content col-sm-12">
	    <div role="tabpanel" class="tab-pane active" id="editor">
			@include('partials.messages.form')

		</div>

	    <div role="tabpanel" class="tab-pane" id="docs">

			@include('partials.messages.docs')
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
			relative_urls : false,
			remove_script_host : false,
		    selector: '.tmce',
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

		$('form').submit(function(e) {
			e.preventDefault();

			var ptc = $('#plain-text');

			if(ptc.is(':checked') && $('#message-content').hasClass('tmce')) {
				var rawtext = tinyMCE.activeEditor.getContent().replace(/<[^>]*>/g, "");
				$('#text-only-content').attr("value", rawtext);
			} else if(! ptc.is(':checked') && $('#message-content').hasClass('tmce')) {
				/* When the user wants a HTML email, and the TMCE editor is displayed */
					$('#text-only-content').attr('value', tinyMCE.activeEditor.getContent());
			} else {
				/** Standard Text area for a text only email */
				$('#text-only-content').attr("value", $('#message-content').val());
			}
			this.submit();
		});
	</script>
@endsection
