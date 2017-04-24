@extends('emails.layout')

@section('content')
	{{-- Can't use the var $message --}}
	{!! eval('?>'.Blade::compileString($mailmessage->content)) !!}
@endsection