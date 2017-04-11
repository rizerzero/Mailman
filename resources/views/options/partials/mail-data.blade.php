	<div class="well">
		<h2>Mail Settings</h2>

		<div class="form-group">
			<label for="mail[driver]">Driver:</label>
			<select name="mail[driver]" class="form-control">
				@foreach(['mailgun', 'mandrill', 'ses', 'sparkpost', 'log'] as $val)
					<option value="{{ $val }}" @if(config('mail.driver') == $val) selected="selected" @endif>{{ $val }}</option>
				@endforeach

			</select>
			<p class="help-block">Choose the API to use when sending mail</p>
		</div>



		<div class="form-group">
			<label for="mail[port]">Mail Port: </label>
			<input type="text" class="form-control" name="mail[port]" value="{{ config('mail.port') }}">
		</div>

		<div class="form-group">
			<label for="mail[from][address]">From Address: </label>
			<input type="text" name="mail[from][address]" value="{{ config('mail.from.address') }}" class="form-control">
		</div>

		<div class="form-group">
			<label for="mail[from][name]">From Name: </label>
			<input type="text" name="mail[from][name]" value="{{ config('mail.from.name') }}" class="form-control">
		</div>

		<div class="alert alert-warning">After changing the mail driver, you will need to adjust the settings below.</div>
		<div class="form-group">
			<label for="mail[host]">Mail Host: </label>
			<input type="text" class="form-control" name="mail[host]" value="{{ config('mail.host') }}">
		</div>

		<div class="form-group">
			<label for="mail[username]">SMTP Username: </label>
			<input type="text" name="mail[username]" value="{{ config('mail.username') }}" class="form-control">
		</div>

		<div class="form-group">
			<label for="mail[password]">SMTP Password: </label>
			<input type="text" name="mail[password]" value="{{ config('mail.password') }}" class="form-control">
		</div>

	</div>