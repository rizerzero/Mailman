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
    <meta name="robots" content="noindex">
    <title>MailMan</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/_bootstrap.css" rel="stylesheet" >
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/tinymce.css">
    <link rel="stylesheet" href="/css/themes.css">
    <script src="/js/javascripts.js"></script>
    @stack('header')

  </head>

  <body>

  @if(! Request::is('login'))
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">MailMan</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lists <span class="caret"></span></a>
              <ul class="dropdown-menu">

                @foreach(App\MailList::take(5)->get()->sortBy('updated_at') as $bar)
                  <li><a href="{{ action('ListController@single', $bar->id) }}">{{ $bar->title }}</a></li>
                @endforeach
                <li class="divider" role="seperator"></li>
                <li><a href="{{ action('ListController@index') }}">View All</a></li>
                <li><a href="{{ action('ListController@create') }}">Create New</a></li>
              </ul>
            </li>


            <li><a href="{{ action('QueueController@index') }}">Queue</a></li>
            <li><a href="{{ action('StatController@view') }}">Stats</a></li>

          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Developer <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ action('OptionController@index') }}">Options</a></li>
                <li><a href="{{ action('GeneratorController@generate') }}">Factories</a></li>
                <li><a href="/logs/">Logs</a></li>
              </ul>
            </li>
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

