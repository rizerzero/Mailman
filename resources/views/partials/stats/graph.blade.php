@if(!isset($hide_controls))<form action="#" method="get">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="start">Date Start</label>
				<input type="text" name="date_start" class="form-control datepicker" value="{{ Request::get('date_start') }}">
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="start">Date End</label>
				<input type="text" name="date_end" class="form-control datepicker" value="{{ Request::get('date_end') }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<label for="start">Type</label>
				<select name="type" class="form-control">
					<option value="">All</option>
					<option value="entry">Entry</option>
					<option value="message">Message</option>
					<option value="list">List</option>
				</select>
			</div>
		</div>
		<div class="col-sm-2">
			{{ csrf_field() }}

			<input type="submit" class="btn btn-default nolabel">
		</div>
	</div>







</form>

@endif

@if($list->stats()->count() == 0)
    <p>Nothing exists yet</p>
@else
    <canvas id="myChart" width="400" height="400"></canvas>
    <script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels : {!! $stats->keys()->toJson() !!},
                datasets: [
                        {
                    label: 'Clicks',
                    data: {!! $stats->pluck('clicks')->toJson() !!},
                    borderColor : "rgba(69,145,255,1 )",
                    fill : false,

                },
                        {
                    label: 'Opens',
                    data: {!! $stats->pluck('opens')->toJson() !!},
                    borderColor : "rgba(1,232,116,1 )",
                    fill : false,

                },

                {
                    label: 'Deliveries',
                    data: {!! $stats->pluck('deliveries')->toJson() !!},
                    borderColor : "rgba(202,255,11,1 )",
                    fill : false,

                },
                {
                    label: 'Unsubscribes',
                    data: {!! $stats->pluck('spam_complaints')->toJson() !!},
                    borderColor : "rgba(255,106,88,1 )",
                    fill : false,

                }]
            },
            options: {

                scales: {

                }
            }

        });

    </script>
@endif

<script>
    $( function() {
        $( ".datepicker" ).datepicker();
    });
</script>