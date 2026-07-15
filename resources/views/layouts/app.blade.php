<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Weather App')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a href="/" class="navbar-brand">
                🌤 Weather App
            </a>
            <button id="themeToggle" class="btn btn-light">
                🌙 Dark Mode
            </button>

        </div>
    </nav>

    <div class="container py-5">
        @yield('content')
    </div>

</body>

</html>