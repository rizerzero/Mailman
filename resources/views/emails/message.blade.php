@extends('emails.layout')

@section('title', 'Please verify your account')

@section('content')


	{{-- Can't use the var $message --}}
	{!! eval('?>'.Blade::compileString($mailmessage->content)) !!}

@endsection