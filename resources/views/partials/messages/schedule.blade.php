<h2>Messages Schedule <a class="btn btn-success" href="{{ action('MessageController@create', $list->id) }}">Create New</a></h2>
<table class="table table-condensed">
	<thead>
		<tr>
			<th>Name</th>
			<th>Subject</th>
			<th>Send Time</th>
			<th>Send Date</th>
			<th>Pending Queues</th>
		</tr>
	</thead>

	<tbody>
		@foreach($messages->sortBy('send_date') as $message)
		<tr>
			<td>@if(! $list->isActive() )<a href="{{ action('MessageController@edit', ['list' => $message->mailList->id, 'message' => $message->id]) }}">@endif {{ $message->name }}@if(! $list->isActive() )</a>@endif</td>
			<td>{{ $message->subject }}</td>
			<td>{{ $message->getSendTime() }}</td>
			<td>{{ $message->send_date }}</td>
			<td>{{ $message->mailQueues()->getNew()->count() }}</td>
		</tr>
		@endforeach
	</tbody>
</table>