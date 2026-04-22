<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <i class="bi bi-kanban fs-1 text-primary"></i>
                                <div>
                                    <h1 class="h3 mb-1">{{ config('app.name', 'Task Manager') }}</h1>
                                    <p class="text-muted mb-0">Task management application built with Laravel.</p>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                        <i class="bi bi-speedometer2 me-1"></i>
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-person-plus me-1"></i>
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
