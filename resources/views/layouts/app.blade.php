<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{{ secure_asset('/pictures/logo/logo.png') }}}">
    <title>{{ config('Oro taršos stebėjim', 'Oro taršos stebėjimas') }}</title> 

    <!-- Scripts -->
    
    <script src="{{ secure_asset('/js/app.js') }}" defer></script>
    @yield('Scripts')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    @yield('Fonts')

    <!-- Styles -->
    <link href="{{ secure_asset('/css/app.css') }}" media="all" rel="stylesheet"  type="text/css">
    <link href="{{ secure_asset('/css/header.css') }}" media="all"  rel="stylesheet"  type="text/css">
    @yield('Styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ route('/') }}">
                    <img src="{{ secure_asset('/pictures/logo/logo.png') }}" style='width:36px;height:48px;'>
                    {{ config('Oro taršos stebėjimas', 'Oro taršos stebėjimas') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto navigation">

                        <li> <a class='nav-link' href="{{route('map')}}">Grafikai</a> </li>    
                        <li> <a class='nav-link' href="{{route('viewReports')}}">Ataskaitos</a> </li>

                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Prisijungti') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="/changePassword">
                                        {{ __('Pakeisti slaptažodį') }}
                                    </a>
                                    <a class="dropdown-item" href="{{route('/dataDownload')}}">Parsisiųsti Duomenis</a>
                                    <a class="dropdown-item" href="{{route('/reports')}}">Valdyti ataskaitas</a>
                                    @if(Auth::user()->role == 'Administrator')
                                        <a class="dropdown-item" href="{{route('/devices')}}">Valdyti prietaisus</a>
                                        <a class="dropdown-item" href="{{route('/sensors')}}">Valdyti sensorius</a>      
                                        <a class="dropdown-item" href="{{route('/users')}}">Valdyti specialistus</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Atsijungti') }}
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main >
            <div class='container' style='padding:2vh 0 0 0;'>
                <div class="row" >
                        @if (session('error'))
                            <div class="alert alert-danger" style="width: 100%">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" style="width: 100%">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card"style='width:100%; margin:auto;'>
                            <div class="card-header">
                                @yield('cardHeader')
                            </div>
                            <div class="card-body" >
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
