@extends("metar-base")

@section("title", "Metar and TAF and IVAO")

@include("navbar-metar")

@section('content')

<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h1> {{$metar["station"]}}</h1>
        </div>
        <div class="d-flex">
            @if ($metar["flight_rules"] == "VFR")
            <button class="btn btn-success btn-sm"><i class='bi bi-123'></i> VFR</button>
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
        <div class="d-flex flex-row-reverse text-warning">
            @auth
            <form action="#" method="post">
                <input type="hidden" name="icao" value="{{$metar["station"]}}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <button type="submit" class="btn btn-info btn-sm ms-2 disabled"><span class="d-flex align-items-center"><span class="material-symbols-outlined fill">star</span>Add to favorites</span></button>
            </form>
            @endauth
            @guest
            <form action="#" method="get">
                <a href="{{ Route("auth.register") }}"><p class="btn btn-primary btn-sm ms-2"> Register </p></a>
            </form>
            @endguest
            <form action="{{ Route('metars.icao') }}" method="get">
                <input type="hidden" name="icao" value="{{$metar["station"]}}">
                <button type="submit" class="btn btn-success btn-sm"> <span class="d-flex align-items-center"><span class="material-symbols-outlined">sync</span> Refresh</span></button>
            </form>
        </div>
        <div class="mt-2">
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="card-title d-flex align-items-center"> {{$metar["metar"]}}</h4>
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
                            @if ($metar["wind"]["direction"] == "None")
                            <li>Direction : {{$metar["wind"]["direction"]}}</li>
                            @else
                            <li>Direction : {{$metar["wind"]["direction"]}} °</li>
                            @endif
                            @if ($metar["wind"]["wind_variable"] == "None")
                            <li>Wind variable : {{$metar["wind"]["wind_variable"]}} </li>
                            @else
                            <li>Wind variable : {{$metar["wind"]["wind_variable"]}} °</li>
                            @endif
                            @if ($metar["wind"]["speed_KT"] == "None")
                            <li>Speed : {{$metar["wind"]["speed_KT"]}}</li>
                            @else
                            <li>Speed : {{$metar["wind"]["speed_KT"]}} Kt</li>
                            @endif
                            @if ($metar["wind"]["speed_KM"] == "None")
                            <li>Speed : {{$metar["wind"]["speed_KM"]}}</li>
                            @else
                            <li>Speed : {{$metar["wind"]["speed_KM"]}} KM/h</li>
                            @endif
                        </ul>
                    </div>
                </div>
                </p>
            </div>
        </div>
        <hr class="mt-2">
        <div class="card bg-dark">
            <div class="card-header text-white">
                <h4> <span class="material-symbols-outlined">subdirectory_arrow_right</span></i> TAF :</h4>
            </div>
            <div class="card-body text-center text-white">
                {{$taf["taf"]}}
            </div>
        </div>
        <hr class="mt-2">
        <h3><span class="material-symbols-outlined">map</span> CHART</h3>
        <div class="d-flex justify-content-center">
            <a href="{{ $chartIFR }}" target="_blank"> <button type="submit" class="btn btn-dark"><span class="d-flex align-items-center"><span class="material-symbols-outlined">assistant_navigation</span>&nbsp IFR</span></button></a>
            <a href="{{ $chartVFR }}" target="_blank"> <button type="submit" class="btn btn-dark ms-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">navigation</span> VFR</span></button></a>
        </div>
        <hr class="mt-2">
        <h3><span class="material-symbols-outlined">cell_tower</span> IVAO</h3>
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
                        <h4><span class="material-symbols-outlined">flight_takeoff</span> Departure</h4>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ Route("ivao.plateforme", ["icao" => $metar["station"], false] ) }}"> <button type="submit" class="btn btn-primary align-content-center justify-center no-link">
                                <h1><strong>{{$pilot["departure"]["count"]}}</strong></h1>
                            </button></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header text-center text-warning">
                        <h4><span class="material-symbols-outlined">flight_land</span> Arrival</h4>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ Route("ivao.plateforme", ["icao" => $metar["station"], false] ) }}"> <button type="submit" class="btn btn-primary align-content-center justify-center no-link">
                                <h1><strong>{{$pilot["arrivals"]["count"]}}</strong></h1>
                            </button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection