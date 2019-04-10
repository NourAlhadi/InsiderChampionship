<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Insider') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('css')


</head>
<body>
<div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('homepage') }}">Insider's Championship</a>
            </li>
            <li class="nav-item">
                <div class="nav-link" href="#">||</div>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('teamActive')" href="{{ route('teams.index') }}">Set teams</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('matchActive')" href="{{ route('matches.index') }}">Go to league</a>
            </li>
        </ul>

        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <button class="btn btn-outline-light" onclick="triggerAction();">Reset Progress</button>
            </li>
        </ul>
    </nav>

    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    <form id="myform" action="{{ route('reset') }}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ csrf_token() }}">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
        function triggerAction() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will reset all your progress in league",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, lets go!'
            }).then((result) => {
                if (result.value) {
                    $('#myform').submit();
                }
            });
        }
    </script>
    @yield('js')
</div>
</body>
</html>