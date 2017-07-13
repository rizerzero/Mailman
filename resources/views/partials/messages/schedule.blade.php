
@if($messages->count() == 0)
	<p>Nothing exists yet</p>
@else
	<table class="table table-condensed">
		<thead>
			<tr class="table-legend">
				<th colspan="4" class="border-right">Data</th>
				<th colspan="4">Queue Stats</th>
			</tr>
			<tr>
				<td>Name</td>
				<td>Subject</td>
				<td>Send Time</td>
				<td>Send Date</td>

				<td>New Queues</td>
				<td>Processing</td>
				<td>Complete</td>
				<td>Total</td>
			</tr>
		</thead>

		<tbody>
			@foreach($messages->sortBy('send_date') as $message)
			<tr>
				<td><a href="{{ action('MessageController@edit', ['list' => $message->mailList->id, 'message' => $message->id]) }}">{{ $message->name }}</a></td>
				<td>{{ $message->subject }}</td>
				<td>{{ $message->getSendTime() }}</td>
				<td class="border-right">{{ $message->send_date }}</td>

				<td>{{ $message->mailQueues()->getNew()->count() }}</td>
				<td>{{ $message->mailQueues()->fromStatus(3)->count() }}</td>
				<td>{{ $message->mailQueues()->fromStatus(2)->count() }}</td>
				<td>{{ $message->mailqueues()->count() }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@endif
