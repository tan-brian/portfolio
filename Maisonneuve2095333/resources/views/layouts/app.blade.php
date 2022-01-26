<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" />
        <link href="{{asset('css/main.css')}}" rel="stylesheet" />
        <style>
            body {
                font-family: 'Nunito';
            }
        </style>

    </head>

    <body>
    @php $locale = session()->get('locale'); @endphp
    <nav class="navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">@if(session('user'))@lang('lang.hello'), {{session('user')->name}} @endif</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100">
                <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiants') }}">@lang('lang.student_list')</a>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">@lang('lang.login')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration') }}">@lang('lang.register')</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.edit', session('user')->id) }}">@lang('lang.profile')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('forum') }}">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documents') }}">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">@lang('lang.logout')</a>
                    </li>
                    @endguest
                    <li class="nav-item ms-auto">
                    <a class="dropdown-item @if($locale=='en') bg-warning @endif" href="/Maisonneuve2095333/lang/en"><img src="{{asset('images/flag/en.png')}}" > @lang('lang.english')</a>
                </li>
                <li class="nav-item">
                  <a class="dropdown-item @if($locale=='fr') bg-warning @endif" href="/Maisonneuve2095333/lang/fr"><img src="{{asset('images/flag/fr.png')}}" > @lang('lang.french')</a>
                </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    </body>
    

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>     
</div>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="{{asset('js/main.js')}}"></script>
</html>