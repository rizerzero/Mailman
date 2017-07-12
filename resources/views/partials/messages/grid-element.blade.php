<div class="row">

	<div class="col-md-8 col-md-offset-2">
		<h2><a href="{{ action('MessageController@edit', ['list' => $message->mailList->id, 'message' => $message->id]) }}">{{ $message->name }}</a></h2>
		<p><strong>Subject:</strong> {{ $message->subject }}</p>
		<iframe src="{{ action('MessageController@render', $message->id )}}" width="100%" height="500px"></iframe>

		<p><strong>Schedule Time:</strong> {{ $message->getSendTime() }}</p>
		<p><strong>Send Date:</strong> {{ (is_null($message->send_date)) ? 'TBD' : $message->send_date }}</p>
		<p><a href="{{ action('MessageController@render', $message->id )}}" target="_blank">Preview</a></p>
	</div>



</div>

	<hr>