<div class="row">

	<div class="col-sm-6">
		<h3>@if(! $message->mailList->isActive())<a href="{{ action('MessageController@edit', ['list' => $message->mailList->id, 'message' => $message->id]) }}">@endif{{ $message->name }}@if(! $message->mailList->isActive())</a>@endif</h3>
	<p>Order: {{ $message->position }}</p>
	<p>Created On: {{ $message->created_at }}</p>
	<p>Last Update: {{ $message->updated_at }}</p>
	<p>To be queued: {{ $message->send_date }}</p>
	</div>

	<div class="col-sm-6">
		<p><a href="{{ action('MessageController@render', $message->id )}}" target="_blank">Preview</a></p>
		<iframe src="{{ action('MessageController@render', $message->id )}}" width="100%"></iframe>
	</div>

</div>

