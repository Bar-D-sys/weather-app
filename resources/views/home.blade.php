@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h2>🌤 Weather App</h2>
                </div>

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- API Error --}}
                    @if(isset($error))
                        <div class="alert alert-danger">
                            {{ ucfirst($error) }}
                        </div>
                    @endif

                    {{-- Search Form --}}
                    <form action="{{ route('weather.search') }}" method="GET">

                        <div class="mb-3">
                            <label class="form-label">City Name</label>

                            <input type="text" name="city" class="form-control" placeholder="Enter city"
                                value="{{ old('city', session('last_city')) }}">
                        </div>

                        <button id="searchBtn" type="submit" class="btn btn-primary w-100">

                            <span id="btnText">
                                Search Weather
                            </span>

                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true">
                            </span>

                        </button>

                        <button type="button" id="locationBtn" class="btn btn-success w-100 mt-2">
                            📍 Use My Location
                        </button>

                    </form>
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(isset($favorites) && $favorites->count())

                        <hr>

                        <h5>⭐ Favorite Cities</h5>

                        <div class="d-flex flex-wrap gap-2">

                            @foreach($favorites as $favorite)

                                <div class="d-flex align-items-center gap-1">

                                    <a href="{{ route('weather.search', ['city' => $favorite->city]) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        {{ $favorite->city }}
                                    </a>

                                    <form action="{{ route('favorites.delete', $favorite) }}" method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm">
                                            🗑
                                        </button>

                                    </form>

                                </div>

                            @endforeach

                        </div>

                    @endif

                    {{-- Weather Card --}}
                    @include('partials.weather-card')

                </div>
            </div>

        </div>

    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function () {

            document.getElementById("searchBtn").disabled = true;

            document.getElementById("btnText").textContent = "Loading...";

            document.getElementById("loadingSpinner").classList.remove("d-none");

        });

        document.getElementById("locationBtn").addEventListener("click", function () {

            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser.");
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {

                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                window.location.href =
                    `/weather/location?lat=${latitude}&lon=${longitude}`;

            }, function () {

                alert("Unable to retrieve your location.");

            });

        });
    </script>

@endsection