<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Менеджер задач') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            Менеджер задач
        </a>

        <div class="navbar-nav me-auto">
            <a class="nav-link" href="{{ route('tasks.index') }}">Задачи</a>
            <a class="nav-link" href="{{ route('task_statuses.index') }}">Статусы</a>
            <a class="nav-link" href="{{ route('labels.index') }}">Метки</a>
        </div>

        <div class="navbar-nav">
            @auth
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    {{ Auth::user()->name }}
                </a>

                {!! html()->form('POST', route('logout'))->open() !!}
                    @csrf

                    <a
                        class="nav-link"
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        Выход
                    </a>
                {!! html()->form()->close() !!}
            @else
                <a class="nav-link" href="{{ route('login') }}">
                    {{ __('auth.log_in') }}
                </a>

                @if (Route::has('register'))
                    <a class="nav-link" href="{{ route('register') }}">
                        {{ __('auth.register') }}
                    </a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="bg-secondary bg-opacity-10 rounded p-5">
        <h1 class="display-1">
            Привет от Хекслета!
        </h1>

        <p class="fs-3">
            Практические курсы по программированию
        </p>

        <hr class="my-4">

        <a class="btn btn-primary btn-lg" href="https://hexlet.io" target="_blank">
            Узнать больше
        </a>
    </div>
</main>
</body>
</html>
