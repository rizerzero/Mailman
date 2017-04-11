<form action="{{ action('QueueController@index') }}" method="GET">

	<div class="form-group col-sm-5">
		<label for="status">Status</label>
		<select name="status" class="form-control" id="">
			@foreach($data->status as $k => $v)
			<option value="{{ $k }}" @if(Request::get('status') == $k) selected="selected" @endif> {{ $v }}</option>
			@endforeach
		</select>

	</div>

	<div class="form-group col-sm-5">
		<label for="list">List</label>

		<select name="list" class="form-control" name="list">
		<option value="0">All</option>
		@foreach($lists as $list)
		<option value="{{ $list->id }}" @if(Request::get('list') == $list->id) selected="selected" @endif>{{ $list->title }}</option>
		@endforeach
		</select>
	</div>

	<div class="form-group col-sm-2 text-right">
		<label for="">&nbsp;</label>
		<input type="submit" class="btn btn-success nolabel">
	</div>

</form>