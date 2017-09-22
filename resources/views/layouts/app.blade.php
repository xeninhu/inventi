<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link rel="stylesheet" type="text/css" href="{{ asset('css/semantic.min.css') }}">

    
</head>

<body>
    <div id="app">
        <!--nav class="navbar navbar-default navbar-static-top"-->

        <div class="ui inverted menu">
            <div class="ui container">
                <div class="title item">
                    <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                    </a>
                </div>            

                <!-- Right Side Of Navbar -->
                
                <!-- Authentication Links -->
                @if (!Auth::guest())
                    <div class="ui simple dropdown link item right">
                        <span class="text">{{ Auth::user()->name }}</span>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                                <a class="item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        </div>
                    </div>
                    
                @endif
            </div>
        </div>


        @yield('content')
    </div>

    <!-- Scripts -->
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/semantic.min.js') }}"></script>
<script language="javascript">
    $('.message .close')
        .on('click', function() {
            $(this)
            .closest('.message')
            .transition('fade');
        });
</script>
</body>
</html>
