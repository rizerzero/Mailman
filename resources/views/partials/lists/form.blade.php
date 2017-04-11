<form action="{{ (isset($list)) ? action('ListController@update', $list->id ) :  action('ListController@store') }}" method="POST">

		<div class="form-group">
			<label for="title">Title</label>
			<input type="text" class="form-control" name="title" placeholder="Title" value="{{ (isset($list)) ? $list->title : null }}">
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea name="description" class="form-control" placeholder="Description">{{ (isset($list)) ? $list->description : null }}</textarea>
		</div>

		<div class="form-group">
		<label for="campaign_start">Campaign Start</label>
			<input type="text" class="form-control datepicker" name="campaign_start" value="{{ (isset($list)) ? $list->campaign_start : null }}">
		</div>

		<div class="form-group">
			<input type="submit" value="{{ (isset($list)) ? 'Update' : 'Save' }}" class="btn btn-success">
		</div>

		{{ csrf_field() }}
	</form>


<script>
	$( function() {
	    $( ".datepicker" ).datepicker({
	    	minDate: 0,
	    	dateFormat: 'yy-mm-dd'
	    });
	});
</script>