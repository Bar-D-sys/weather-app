@if(isset($weather['main']))

    <div class="text-center mt-4">

        <h2>{{ $weather['name'] }}, {{ $weather['sys']['country'] }}</h2>

        <p class="text-muted mb-2">
    {{ now()->format('l, d F Y') }}
    <br>
    Updated: {{ now()->format('h:i A') }}
</p>

        <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="Weather Icon">

        <h4 class="text-capitalize">
            {{ $weather['weather'][0]['description'] }}
        </h4>

        <h1 class="display-3 fw-bold">
            {{ round($weather['main']['temp'], 1) }}°C
        </h1>

        <p class="text-muted">
            Feels like {{ round($weather['main']['feels_like'], 1) }}°C
        </p>
        
        @php
    $alreadySaved = isset($favorites)
        ? $favorites->contains('city', $weather['name'])
        : false;
@endphp

@if(!$alreadySaved)
    <form action="{{ route('favorites.save') }}" method="POST" class="mt-3">
        @csrf

        <input type="hidden" name="city" value="{{ $weather['name'] }}">

        <button type="submit" class="btn btn-warning">
            ⭐ Save to Favorites
        </button>
    </form>
@else
    <div class="alert alert-success mt-3 py-2">
        ⭐ This city is already in Favorites.
    </div>
@endif

    </div>

    <div class="row text-center">

    <div class="col-6 mt-3">
        <h6>🌅 Sunrise</h6>
        <strong>
            {{ \Carbon\Carbon::createFromTimestamp($weather['sys']['sunrise'])->format('H:i') }}
        </strong>
    </div>

    <div class="col-6 mt-3">
        <h6>🌇 Sunset</h6>
        <strong>
            {{ \Carbon\Carbon::createFromTimestamp($weather['sys']['sunset'])->format('H:i') }}
        </strong>
    </div>

    <div class="col-6 mb-3">
        <h6>💧 Humidity</h6>
        <strong>{{ $weather['main']['humidity'] }}%</strong>
    </div>

    <div class="col-6 mb-3">
        <h6>🌬 Wind</h6>
        <strong>{{ $weather['wind']['speed'] }} m/s</strong>
    </div>

    <div class="col-6">
        <h6>👁 Visibility</h6>
        <strong>{{ $weather['visibility'] / 1000 }} km</strong>
    </div>

    <div class="col-6">
        <h6>🌡 Pressure</h6>
        <strong>{{ $weather['main']['pressure'] }} hPa</strong>
    </div>

</div>

@endif

@if(isset($airQuality))

<hr>

<h5 class="text-center mt-3">
    🌿 Air Quality
</h5>

<p class="text-center fs-5">

    @php
        $aqi = $airQuality['list'][0]['main']['aqi'];
    @endphp

    @switch($aqi)

        @case(1)
            🟢 Good
            @break

        @case(2)
            🟡 Fair
            @break

        @case(3)
            🟠 Moderate
            @break

        @case(4)
            🔴 Poor
            @break

        @case(5)
            🟣 Very Poor
            @break

    @endswitch

</p>

@endif


@if(isset($forecast['list']))

<hr class="my-4">

<h4 class="text-center mb-3">
    🕒 Next 24 Hours
</h4>

<div class="row row-cols-2 row-cols-md-4 g-3">

    @foreach(array_slice($forecast['list'], 0, 8) as $hour)

        <div class="col">

            <div class="card text-center h-100 shadow-sm">

                <div class="card-body">

                    <strong>
                        {{ \Carbon\Carbon::parse($hour['dt_txt'])->format('H:i') }}
                    </strong>

                    <br>

                    <img src="https://openweathermap.org/img/wn/{{ $hour['weather'][0]['icon'] }}.png"
                        alt="Icon">

                    <h6>
                        {{ round($hour['main']['temp']) }}°C
                    </h6>

                    <small class="text-capitalize">
                        {{ $hour['weather'][0]['description'] }}
                    </small>

                </div>

            </div>

        </div>

    @endforeach

</div>

    <hr class="my-4">

    <h4 class="text-center mb-3">
        5-Day Forecast
    </h4>

    <div class="row row-cols-2 row-cols-md-5 g-3">

        @foreach($forecast['list'] as $item)

            @if(\Carbon\Carbon::parse($item['dt_txt'])->format('H:i') == '12:00')

                <div class="col">

                    <div class="card text-center shadow-sm h-100">

                        <div class="card-body">

                            <h6>
                                {{ \Carbon\Carbon::parse($item['dt_txt'])->format('D, M d') }}
                            </h6>

                            <img src="https://openweathermap.org/img/wn/{{ $item['weather'][0]['icon'] }}@2x.png"
                                alt="Forecast Icon">

                            <p class="fw-bold mb-1">
                                {{ round($item['main']['temp']) }}°C
                            </p>

                            <p class="mb-0 text-danger">
                                ↑ {{ round($item['main']['temp_max']) }}°C
                            </p>

                            <p class="text-primary">
                                ↓ {{ round($item['main']['temp_min']) }}°C
                            </p>

                            <small class="text-capitalize">
                                {{ $item['weather'][0]['description'] }}
                            </small>

                        </div>

                    </div>

                </div>

            @endif

        @endforeach

    </div>

@endif