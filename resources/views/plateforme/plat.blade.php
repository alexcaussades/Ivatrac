<?php

use Illuminate\Support\Carbon;
?>
@extends("metar-base")

@section("title", "Info IVAO")

@include("navbar-metar")

@section('content')


<div class="container">
    <a href="{{ Route('ivao.plateforme', ["icao" => $Pilot["icao"]] )}}" class="btn btn-success"><span class="d-flex align-items-center"><span class="material-symbols-outlined">sync</span> Refresh</span></a>
    <div class="row mt-2 d-flex justify-content-center">
        <div class="col d-flex inline-flex">
            @for ( $i = 0; $i < count($ivao["atc_open"]); $i++ ) 
            <div class="card text-white bg-dark ms-3">
                <div class="card-header text-center">
                    <h4 class="text-warning">{{ $ivao["atc_open"][$i]["composePosition"]}}</h4>
                    <span class="text-info">Online:</span>
                </div>
                <div class="card-body text-center">
                    <span class="text-info">Freq:</span> {{ $ivao["atc_open"][$i]["frequency"] }} Mhz
                    <hr>
                    <span class="text-info">Revision:</span>
                    <hr>
                    <span class="text-info">Information:</span> 
                    <div><span>{{$atc}}</span></div>
                </div>
            </div>
        @endfor
    </div>
</div>
<hr>
<div class="row mt-2 d-flex justify-content-center">
    <h6>ATC Zone In / Out</h6>
    <div class="col-12">
        @foreach ($other as $r )
        @for ($i = 0; $i < count($r); $i++) <div class="d-flex d-inline-flex">
            <button class="btn btn-info">{{$r[$i]["callsign"]}}</button>
    </div>
    @endfor
    @endforeach
</div>
</div>
<hr>
<div class="">
    <div class="col">
        <h5><span class="material-symbols-outlined">flight_takeoff</span> Departure ({{$Pilot["departure"]["count"]}})</h5>
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>Callsign</th>
                    <th>State</th>
                    <th>Route</th>
                    <th>FlightRules</th>
                    <th>TO</th>
                    <th>Online</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Pilot["departure"]["data"] as $PilotData)

                <tr>
                    <td scope="row">{{ $PilotData["callsign"] }}</td>
                    <td>{{ $PilotData["lastTrack"]["state"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["route"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["flightRules"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["arrivalId"] }}</td>
                    <td>{{ Carbon::parse($PilotData["lastTrack"]["time"])->format('H:i') }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col">
        <h5><span class="material-symbols-outlined">flight_land</span> Arrival ({{$Pilot["arrivals"]["count"]}})</h5>
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>Callsign</th>
                    <th>State</th>
                    <th>Route</th>
                    <th>FlightRules</th>
                    <th>FROM</th>
                    <th>Arrived in</th>
                    <th>Online</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Pilot["arrivals"]["data"] as $PilotData)
                @php
                $distance_arrival = $PilotData['lastTrack']['arrivalDistance'];
                $distance_arrival = explode(".", $distance_arrival);
                @endphp

                <tr>
                    <td scope="row">{{ $PilotData["callsign"] }}</td>
                    <td>{{ $PilotData["lastTrack"]["state"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["route"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["flightRules"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["departureId"] }}</td>
                    <td>{{ $distance_arrival[0] }} NM</td>
                    <td>{{ Carbon::parse($PilotData["lastTrack"]["time"])->format('H:i') }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

@if (ENV('APP_ENV') == 'local')
<script src="{{ asset("asset/js/update_friend.js") }}"></script>
@else
<script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
@endif