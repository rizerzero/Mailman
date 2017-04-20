<h2>Messages Schedule</h2>
<table class="table table-condensed">
	<thead>
		<tr>
			<th>Name</th>
			<th>Subject</th>
			<th>Send Date</th>
			<th>Pending Queues</th>
		</tr>
	</thead>

	<tbody>
		@foreach($messages as $message)
		<tr>
			<td>{{ $message->name }}</td>
			<td>{{ $message->subject }}</td>
			<td>{{ $message->send_date }}</td>
			<td>{{ $message->mailQueues()->getNew()->count() }}</td>
		</tr>
		@endforeach
	</tbody>
</table>