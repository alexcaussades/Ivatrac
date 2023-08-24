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
        @if ($ivao["APP"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["APP"]["callsign"]}}</h4>
                    <span class="text-info">Online: {{Carbon::parse($ivao["APP"]["lastTrack"]["time"])->format('H:i')}}</span>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["APP"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["APP"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["APP"][0] ?? ""}} / {{$ATC["APP"][1] ?? ""}}
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <!-- <a href="{{ Route("friends.add.oter.page.post", ["vid_friend" => $ivao['APP']["userId"], "host" => $hosturl ] ) }}"><button type="button" class="btn btn-info">Add Friend</button></a> -->
            </div>
        </div>
        @endif
        @if ($ivao["TWR"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["TWR"]["callsign"]}}</h4>
                    <span class="text-info">Online: {{Carbon::parse($ivao["TWR"]["lastTrack"]["time"])->format('H:i')}}</span>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["TWR"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["TWR"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["TWR"][0] ?? ""}} / {{$ATC["TWR"][1] ?? ""}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["GND"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["GND"]["callsign"]}}</h4>
                    <span class="text-info">Online: {{Carbon::parse($ivao["GND"]["lastTrack"]["time"])->format('H:i')}}</span>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["GND"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["GND"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["GND"][0] ?? ""}} / {{$ATC["GND"][1] ?? ""}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["FSS"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["FSS"]["callsign"]}}</h4>
                    <span class="text-info">Online: {{Carbon::parse($ivao["FSS"]["lastTrack"]["time"])->format('H:i')}}</span>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["FSS"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["FSS"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["FSS"][0] ?? ""}} / {{$ATC["FSS"][1] ?? ""}}
                </div>
            </div>
        </div>
        @endif
    </div>
    <hr>
    <div class="row mt-2 d-flex justify-content-center">
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