<table class="table">
	<thead>
		<tr>
			<th>Time</th>
			<th>Deliveries</th>
			<th>Opens</th>
			<th>Clicks</th>
			<th>Spam Complaints</th>
		</tr>
	</thead>
	<tbody>
		@foreach($stats as $key => $stat)
			<tr>
				<td>{{ $key }}</td>
				<td>{{ $stat->get('deliveries') }}</td>
				<td>{{ $stat->get('opens') }}</td>
				<td>{{ $stat->get('clicks') }}</td>
				<td>{{ $stat->get('spam_complaints') }}</td>

			</tr>
		@endforeach
	</tbody>
</table>