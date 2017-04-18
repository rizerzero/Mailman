<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/_bootstrap.css" rel="stylesheet" >
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/tinymce.css">

    @stack('header')
    <script src="/js/javascripts.js"></script>
  </head>

  <body>

  @if(! Request::is('login'))
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">MailMan</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lists <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ action('ListController@index') }}">View All</a></li>
                <li><a href="{{ action('ListController@create') }}">Create New</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ action('QueueController@index') }}">View Queue</a></li>
              </ul>
            </li>

            <li><a href="{{ action('OptionController@index') }}">Options</a></li>
            <li><a href="/logs/">Logs</a></li>
            <li><a href="{{ action('StatController@view') }}">Stats</a></li>
            <li><a href="{{ action('GeneratorController@generate') }}">Factories</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li>
              @if(Auth::check() )
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            @endif

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    @endif
<div class="container">
  <div class="col-lg-12" >

      <div class="@if(Request::route()->getAction()['controller'] == 'App\Http\Controllers\PageController@index') home-message @endif">

          @if (session('success'))
              <div class="alert alert-success messages-container" data-hide="true">
                  {!! session('success') !!}
              </div>
          @endif

          @if (session('error'))
              <div class="alert alert-danger messages-container" data-hide="true">
                  {!! session('error') !!}
              </div>
          @endif

          @if($errors->any())

            <div class="alert alert-danger messages-container" data-hide="true">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{!! $error !!}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
      </div>

  </div>
</div>

