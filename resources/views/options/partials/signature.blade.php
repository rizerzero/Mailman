	<div class="well">
		<h2>Mail Signature</h2>

		<div class="form-group">
			<label for="mail[signature][copy]">Signature</label>
			<textarea class="form-control" name="mail[signature][copy]">{{ config('mail.signature.copy') }}</textarea>
		</div>

		<div class="form-group">
			<label for="mail[unsubscribe]">Unsubscribe Action</label>
			<input type="text" class="form-control" name="mail[signature][unsubscribe]" disabled value="{{ config('mail.signature.unsubscribe') }}">
			<p class="help-block">A programmer should set this value, as you need to modify the applications routing.</p>
		</div>
	</div>