<form action="{{ (isset($list)) ? action('ListController@update', $list->id ) :  action('ListController@store') }}" method="POST">

		<div class="form-group col-sm-10">
			<label for="title">Title</label>
			<input type="text" class="form-control" name="title" placeholder="Title" value="{{ (isset($list)) ? $list->title : null }}">
		</div>

		<div class="form-group col-sm-2">
		<label for="campaign_start">Campaign Start</label>
			<input type="text" class="form-control datepicker" name="campaign_start" value="{{ (isset($list)) ? $list->campaign_start : null }}">
		</div>

		<div class="form-group col-sm-12">
			<label for="description">Description</label>
			<textarea name="description" class="form-control" placeholder="Description">{{ (isset($list)) ? $list->description : null }}</textarea>
		</div>

		<div class="col-sm-6 form-group">
			<label for="">Reply to Email:</label>
			<input type="text" class="form-control" name="from_email" value="{{ (isset($list)) ? $list->from_email : null }}">
			<p class="help-block">The email that the customer can reply to in the message.</p>
		</div>

		<div class="col-sm-6 form-group">
			<label for="">Reply to Name:</label>
			<input type="text" class="form-control" name="from_name" value="{{ (isset($list)) ? $list->from_name : null }}">
			<p class="help-block">The name that displays of "who sent the email".</p>
		</div>


		<div class="form-group col-sm-12">
			<input type="submit" value="{{ (isset($list)) ? 'Update' : 'Save' }}" class="btn btn-success">
		</div>

		{{ csrf_field() }}
	</form>


<script>
	$( function() {
	    $( ".datepicker" ).datepicker({
	    	minDate: 0,
	    	showOn: 'focus',
	    	dateFormat: 'yy-mm-dd',
	    }).datepicker("setDate", "0");
	});
</script>