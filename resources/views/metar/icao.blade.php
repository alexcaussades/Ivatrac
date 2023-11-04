@extends("metar-base")

@section("title", "Metar")

@include("navbar-metar")

@section('content')

<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h1> {{$metar["station"]}}</h1>
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
                <a href="{{ Route("ivao.connect") }}">
                    <p class="btn btn-primary btn-sm ms-2"> Login / Register</p>
                </a>
            </form>
            @endguest
            @if ($pilot["outbound"]>=1 || $pilot["inbound"]>=1)
            <form action="{{ Route('ivao.plateforme', ["icao" => $metar["station"], false]) }}" method="get">
                <button type="submit" class="btn btn-success btn-sm ms-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">info</span> &nbsp IVAO</span></button>
            </form>
            @endif
            <form action="{{ Route('metars.icao') }}" method="get">
                <input type="hidden" name="icao" value="{{$metar["station"]}}">
                <button type="submit" class="btn btn-dark btn-sm"> <span class="d-flex align-items-center"><span class="material-symbols-outlined">sync</span> Refresh</span></button>
            </form>
        </div>
        <div class="mt-2">
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="card-title d-flex align-items-center"> {{$metar["metar"] ?? "NO METAR"}}</h4>
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
                @if ($metar["tempo"] != null)
                <div class="card text-black bg-warning">
                    <div class="card-body">
                        <p class="card-text">{{$metar["tempo"]}}</p>
                    </div>
                </div>
                @endif
                </p>
            </div>
        </div>
        <hr class="mt-2">
        <div class="card bg-dark">
            <div class="card-header text-white">
                <h4> <span class="material-symbols-outlined">subdirectory_arrow_right</span></i> TAF :</h4>
            </div>
            <div class="card-body text-center text-white">
                {{$taf["taf"] ?? "No TAF"}}
            </div>
        </div>
    </div>

    <hr class="mt-2">

    <div class="row">
        <div class="col-4">
            <h4 class="text-center">Bookings</h4>
            <hr>
            <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Position</th>
                        <th>VID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td scope="row">{{$booking["Start_time"]}}</td>
                        <td>{{$booking["End_time"]}}</td>
                        <td>{{$booking["airport"]}}</td>
                        <td>{{$booking["user"]["vid"]}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
        <div class="col-8">
            <h3 class="text-center"> <span class="material-symbols-outlined text-center">cell_tower</span> IVAO</h3>
            <hr>
            <div class="row">
                <div class="col-8 text-center">
                    @if ($atc["atc_open"]!= null)
                    @foreach ($atc["atc_open"] as $openatc)
                    <li class="mt-2"><button class="btn btn-success btn-sm">{{ $openatc["composePosition"] }} - {{ $openatc["frequency"] }} Mhz</button></li>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <hr class="mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4><span class="material-symbols-outlined">flight_takeoff</span> Departure</h4>
                </div>
                <div class="card-body text-center">
                    <a href="{{ Route("ivao.plateforme", ["icao" => $metar["station"], false] ) }}"> <button type="submit" class="btn btn-primary align-content-center justify-center no-link">
                            <h1><strong>{{$pilot["outbound"]}}</strong></h1>
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
                            <h1><strong>{{$pilot["inbound"]}}</strong></h1>
                        </button></a>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
</div>
</div>

@if (ENV('APP_ENV') == 'local')
<script src="{{ asset("asset/js/update_friend.js") }}"></script>
@else
<script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
@endif

@endsection