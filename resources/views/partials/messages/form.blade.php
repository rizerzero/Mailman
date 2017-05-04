<div class="row">
	<div class="form-group col-sm-12">
		<label for="">Name:</label>
		<input type="text" class="form-control" name="name" value={{ (isset($message)) ? $message->name : null }}>
	</div>

	<div class="form-group col-sm-12">
		<label for="">Subject</label>
		<input type="text" class="form-control" name="subject" value="{{ (isset($message)) ? $message->subject : null }}">
	</div>

	<div class="form-group col-md-6">
		<label for="send_date">Send interval:</label>
		<input type="number" name="day_offset" class="form-control" value="{{ (isset($message)) ? $message->day_offset : 0 }}">
	</div>

	<div class="form-group col-md-6">
		<label for="start_time">Queue Time:</label>
		<input type="text" class="timepicker form-control" name="start_time" value="{{ (isset($message)) ? $message->message_time : null }}">
		<p class="help-block">The time to begin queueing emails.</p>
	</div>
	<div class="col-sm-12">
		<div class="well">
			<p class="help-block">Send Interval and Queue Time Explained: <br/>
				<strong>Example 1:</strong> If the campaign start date is on {{ $carbon->now()->toDateString() }} and the send interval is 0 @ {{ $carbon->now()->addMinutes(5)->toTimeString() }}, then the first message will be sent at {{ $carbon->now()->addMinutes(5)->toDateTimeString() }}. <br>
				<strong>Example 2:</strong> If the campaign start date is on {{ $carbon->now()->addDays(1)->toDateString() }} and the send interval is 2 @ {{ $carbon->now()->addHours(1)->addMinutes(5)->toTimeString() }}, then the first message will be sent at {{ $carbon->now()->addMinutes(5)->addHours(1)->addDays(3)->toDateTimeString() }}</p>

		</div>
	</div>
	<div class="form-group col-sm-12">
		<label for="text_only"> <input type="checkbox" name="text_only" id="plain-text" @if(isset($message) && $message->text_only) checked="checked"@endif> Text Only Email</label>
		<p class="help-block">If you change this...you need to update the message twice to ensure that no HTML tags snuck in. When the page is loaded for a "text only message", it switches out the WYSIWYG editor for a standard text area allowing you to save newline chars and other entities instead of HTML</p>
	</div>

	<div class="form-group col-sm-12">
		<div class="alert alert-warning">Please make sure images are hosted on a reliable CDN or capable server. I have created a bucket on S3 called "mailman-media" that is meant to contain this.</div>

		@if(isset($message) && $message->text_only)
			<textarea name="body" width="100%" id="message-content">{{ (isset($message)) ? $message->content : null }}</textarea>
		@else
			<textarea name="body" class="tmce" id="message-content">{{ (isset($message)) ? $message->content : null }}</textarea>
		@endif


	</div>



	<div class="form-group col-sm-12">
	<input type="submit" value="{{ (isset($message)) ? 'Update' : 'Save' }}" class="btn btn-success">

	</div>
	<input type="hidden" name="list_id" value="{{ $list->id }}">
	<input type="hidden" name="message_body" value="" id="text-only-content">
	{{ csrf_field() }}

</div>