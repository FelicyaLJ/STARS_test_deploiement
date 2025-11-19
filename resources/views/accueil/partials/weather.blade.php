@php
    $weather = new OpenWeather();
    $city = request()->input('city', 'Rawdon');
    $weatherData = [
        'fc' => $weather->getForecastWeatherByCityName($city, 'metric'),
        'current' => $weather->getCurrentWeatherByCityName($city, 'metric'),
    ];
@endphp

<div class="bg-black/60 backdrop-blur flex flex-col justify-center items-center sm:rounded-lg shadow-lg">
    <div class="p-4 text-white font-sans">
        @php
            $location = $weatherData['current']['location'];

            // Current
            $current = $weatherData['current'];
            $currentDt = $current['datetime'];
            $currentCond = $current['condition'];
            $currentTemp = $current['forecast']['temp'];
            $iconCode = pathinfo($current['condition']['icon'], PATHINFO_FILENAME);
        @endphp

        <div class="flex flex-col">
            <!-- Date et location -->
            <div class="mb-2">
                <p class="text-lg font-semibold">{{ $location['name'] }}, {{ $location['country'] }}</p>
                <p class="text-sm">{{ Illuminate\Support\Str::of(\Carbon\Carbon::parse($currentDt['formatted_day'])->locale('fr')->translatedFormat('l'))->title() }},
                    {{ \Carbon\Carbon::parse($currentDt['formatted_date'])->locale('fr')->translatedFormat('d M Y') }}</p>
            </div>

            <!-- Conditions météorologiques courantes -->
            <div class="flex items-center gap-4 my-4">
                <img src="{{ $currentCond['icon'] }}" alt="{{ $currentCond['desc'] }}" class="w-16 h-16">
                <div>
                    <p class="text-4xl font-bold">{{ $currentTemp }}°C</p>
                    <p class="text-sm capitalize">{{ $currentCond['desc'] }}</p>
                </div>
            </div>
        </div>

        <!-- Au cours du prochain jour -->
        <div class="flex gap-4 mt-4 text-center text-sm">
            @foreach(array_slice($weatherData['fc']['forecast'], 0, 6) as $next)
                @php
                    $dtNext = $next['datetime'];
                    $condNext = $next['condition'];
                    $tempNext = $next['forecast']['temp'];
                    $iconCodeNext = pathinfo($next['condition']['icon'], PATHINFO_FILENAME);
                @endphp
                <div class="flex flex-col items-center">
                    <p class="capitalize">{{ \Carbon\Carbon::parse($dtNext['formatted_date'])->locale('fr')->translatedFormat('D') }}</p>
                    <p class="capitalize">{{ \Carbon\Carbon::parse($dtNext['formatted_time'])->format('g A') }}</p>
                    <img src="{{ $condNext['icon'] }}" alt="{{ $condNext['desc'] }}" class="w-8 h-8">
                    <p>{{ $tempNext }}°C</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
