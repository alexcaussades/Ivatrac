@extends("metar-base")

@section("title", "Metar and TAF and IVAO")

@include("navbar-metar")

@section('content')

<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h1>{{$metar["station"]}}</h1>
        </div>
        <div class="d-flex">
            @if ($metar["flight_rules"] == "VFR")
            <button class="btn btn-success btn-sm">VFR</button>
            @endif
            @if ($metar["flight_rules"] == "LIFR")
            <button class="btn btn-dark btn-sm">LIFR</button>
            @endif
            @if ($metar["flight_rules"] == "IFR")
            <button class="btn btn-danger btn-sm">IFR</button>
            @endif
            @if ($metar["flight_rules"] == "MVFR")
            <button class="btn btn-primary btn-sm">MVFR</button>
            @endif
        </div>
        <div class="mt-2">
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="card-title">{{$metar["metar"]}}</h4>
                <p class="card-text">
                <p>Time: {{$metar["meta_day"]["time"]}}</p>
                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li>Wind : {{$metar["wind"]["wind"]}}</li>
                            <li>Visibility : {{$metar["visibility"]}}</li>
                            <li>Clouds : {{$metar["clouds"]}}</li>
                            <li>Temperature : {{$metar["temperature"]}} °C</li>
                            <li>Dew Point : {{$metar["dewpoint"]}} °C</li>
                            <li>QNH : {{$metar["QNH"]}}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li>Direction : {{$metar["wind"]["direction"]}} °</li>
                            @if ($metar["wind"]["wind_variable"] == "None")
                            <li>Wind variable : {{$metar["wind"]["wind_variable"]}} </li>
                            @else
                            <li>Wind variable : {{$metar["wind"]["wind_variable"]}} °</li>
                            @endif
                            <li>Speed : {{$metar["wind"]["speed_KT"]}} Kt</li>
                            <li>Speed : {{$metar["wind"]["speed_KM"]}} KM/h</li>
                        </ul>
                    </div>
                </div>
                </p>
            </div>
        </div>
        <hr class="mt-2">
        <div class="card bg-dark">
            <div class="card-header text-white">
                <h4> <i class="bi bi-arrow-90deg-right"></i> TAF :</h4>
            </div>
            <div class="card-body text-center text-white">
                {{$taf["taf"]}}
            </div>
        </div>
        <hr class="mt-2">
        <h3>CHART</h3>
        <div class="d-flex justify-content-center">
            <a href="{{ $chartIFR }}" target="_blank"> <button type="submit" class="btn btn-dark">IFR</button></a>
            <a href="{{ $chartVFR }}" target="_blank"> <button type="submit" class="btn btn-dark ms-2">VFR</button></a>
        </div>
        <hr class="mt-2">
        <h3>IVAO</h3>
        <div class="row">
            <div class="col-md-4">
                @if ($ATC["APP"] == null)
                <ul><button class="btn btn-dark btn-sm">{{$metar["station"]}}_APP - OFFLINE</button></ul>
                @else
                <ul><button class="btn btn-success btn-sm">{{ $ATC["APP"]["callsign"] }} - {{ $ATC["APP"]["atcSession"]["frequency"] }} Mhz</button></ul>
                @endif
                @if ($ATC["TWR"] == null)
                <ul><button class="btn btn-dark btn-sm">{{$metar["station"]}}_TWR - OFFLINE</button></ul>
                @else
                <ul><button class="btn btn-success btn-sm">{{ $ATC["TWR"]["callsign"] }} - {{ $ATC["TWR"]["atcSession"]["frequency"] }} Mhz</button></ul>
                @endif
                @if ($ATC["GND"] == null)
                <ul><button class="btn btn-dark btn-sm">{{$metar["station"]}}_GND - OFFLINE</button></ul>
                @else
                <ul><button class="btn btn-success btn-sm">{{ $ATC["GND"]["callsign"] }} - {{ $ATC["GND"]["atcSession"]["frequency"] }} Mhz</button></ul>
                @endif
                @if ($ATC["FSS"] == null)
                <ul><button class="btn btn-dark btn-sm">{{$metar["station"]}}_FSS - OFFLINE</button></ul>
                @else
                <ul><button class="btn btn-success btn-sm">{{ $ATC["FSS"]["callsign"] }} - {{ $ATC["FSS"]["atcSession"]["frequency"] }} Mhz</button></ul>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header text-center text-warning">
                        <h4>Departure</h4>
                    </div>
                    <div class="card-body text-center">
                    <a href="{{ Route("ivao.plateforme", ["icao" => $metar["station"], false] ) }}"> <button type="submit" class="btn btn-primary align-content-center justify-center no-link"><h1><strong>{{$pilot["departure"]["count"]}}</strong></h1></button></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header text-center text-warning">
                        <h4>Arrival</h4>
                    </div>
                    <div class="card-body text-center">
                    <a href="{{ Route("ivao.plateforme", ["icao" => $metar["station"], false] ) }}"> <button type="submit" class="btn btn-primary align-content-center justify-center no-link"><h1><strong>{{$pilot["arrivals"]["count"]}}</strong></h1></button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection