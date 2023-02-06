<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Admin Portal') }}</title>
        <link rel="icon" href="{{ asset('images/packt-logo.png') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- reCAPTCHA JS -->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('css/project.css') }}" />

        @yield('customcss')

    </head>

    <body>
        <header>
            <div class="top-nav">
                <button class="btn--side-nav" ontouchstart="">
                    <img src="{{ asset('images/sidenav/menu.svg') }}" alt="">
                </button>

                <img src="{{ asset('images/packt.svg') }}" alt="Packt">
                <a href="/logout" class="btn--logout btn" type="button">Log Out</a>
            </div>
        </header>

        <div class="container container--padded">
            <nav class="side-nav">
                <ul class="side-nav__nav">

                    @foreach($side_nav_items as $button)
                        <li class="nav-item">
                            <a href="{{ $button['link'] }}" class="side-nav__nav-link">
                                <img src="{{ $button['imageURL'] }}" alt="">
                                <span class="link-text">{{ $button['text'] }}</span>
                            </a>
                        </li>
                    @endforeach

                </ul>
            </nav>

            <main>
                <h1>@yield('title')</h1>
                <div class="card">
                    @include('partials.messages')
                    @yield('content')
                </div>

                <div class="loading hide" id="app-loader">Loading&#8230;</div>
                <div class="hide" id="myProgress">
                    <div class="bubbleDots">
                        Uploading in progress
                        <div class="loadingDot loadingDot--1">
                        </div>

                        <div class="loadingDot loadingDot--2">
                        </div>

                        <div class="loadingDot loadingDot--3">
                        </div>
                      </div>
                    <div id="progress-bar">

                        <div id="myBar"></div>
                    </div>
                </div>

            </main>
        </div>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        @yield('pageLevelJs')
    </body>
</html>
