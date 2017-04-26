
@if($messages->count() == 0)
	<p>Nothing exists yet</p>
@else
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
@endif


