<table class="table table-condensed table-striped">
	<thead>
		@if(!isset($list))
		<th>List</th>
		@endif
		<th>Entry</th>
		<th>Message</th>
		<th>Status</th>
		<th>Send Time</th>
		<th>Log Message</th>

	</thead>

	<tbody>
		@foreach($queues as $queue)
		<tr>
			@if(!isset($list))
			<td>{{ $queue->message->mailList->title }}</td>
			@endif
			<td>{{ $queue->entry->email }}</td>
			<td>{{ $queue->message->name }}</td>
			<td>{{ $queue->status }}</td>
			<td>{{ $queue->message->send_date }}</td>
			<td>{{ $queue->report }}</td>


		</tr>

		@endforeach
	</tbody>
</table>