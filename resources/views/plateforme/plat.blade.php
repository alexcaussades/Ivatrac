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
                <div class="card-header text-center">
                    <h4 class="text-warning">{{ $ivao["APP"]["callsign"]}}</h4>
                    <span class="text-info">Online:</span> {{Carbon::parse($ivao["APP"]["lastTrack"]["time"])->format('H:i')}}
                </div>
                <div class="card-body text-center">
                    <span class="text-info">Freq:</span> {{ $ivao["APP"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    <span class="text-info">Revision:</span> {{$ivao["APP"]["atis"]["revision"]}}
                    <hr>
                    <span class="text-info">Information:</span> {{$ATC["APP"] ?? ""}}
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
                <div class="card-header text-center">
                    <h4 class="text-warning">{{ $ivao["TWR"]["callsign"]}}</h4>
                    <span class="text-info">Online:</span> {{Carbon::parse($ivao["TWR"]["lastTrack"]["time"])->format('H:i')}}
                </div>
                <div class="card-body text-center">
                    <span class="text-info">Freq:</span> {{ $ivao["TWR"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    <span class="text-info">Revision:</span> {{$ivao["TWR"]["atis"]["revision"]}}
                    <hr>
                    <span class="text-info">Information:</span> {{$ATC["TWR"] ?? ""}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["GND"] != null)
        <div class="col">
        <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center">
                    <h4 class="text-warning">{{ $ivao["GND"]["callsign"]}}</h4>
                    <span class="text-info">Online:</span> {{Carbon::parse($ivao["GND"]["lastTrack"]["time"])->format('H:i')}}
                </div>
                <div class="card-body text-center">
                    <span class="text-info">Freq:</span> {{ $ivao["GND"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    <span class="text-info">Revision:</span> {{$ivao["GND"]["atis"]["revision"]}}
                    <hr>
                    <span class="text-info">Information:</span> {{$ATC["GND"] ?? ""}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["FSS"] != null)
        <div class="col">
        <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center">
                    <h4 class="text-warning">{{ $ivao["FSS"]["callsign"]}}</h4>
                    <span class="text-info">Online:</span> {{Carbon::parse($ivao["FSS"]["lastTrack"]["time"])->format('H:i')}}
                </div>
                <div class="card-body text-center">
                    <span class="text-info">Freq:</span> {{ $ivao["FSS"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    <span class="text-info">Revision:</span> {{$ivao["FSS"]["atis"]["revision"]}}
                    <hr>
                    <span class="text-info">Information:</span> {{$ATC["FSS"] ?? ""}}
                </div>
            </div>
        </div>
        @endif
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