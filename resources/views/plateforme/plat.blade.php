@extends("metar-base")

@section("title", "Info IVAO")

@include("navbar-metar")

@section('content')

<div class="container">
    <a href="{{ Route('ivao.plateforme', ["icao" => $Pilot["icao"]] )}}" class="btn btn-success"><span class="d-flex align-items-center"><span class="material-symbols-outlined">sync</span> Refresh</span></a>
    <div class="row mt-2">
        @if ($ivao["APP"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["APP"]["callsign"]}}</h4>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["APP"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["APP"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["APP"][0]}}
                    <hr>
                    {{$ATC["APP"][1]}}
                    <hr>
                    {{$ATC["APP"][2]}} {{$ATC["APP"][3]}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["TWR"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["TWR"]["callsign"]}}</h4>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["TWR"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["TWR"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["TWR"][0]}}
                    <hr>
                    {{$ATC["TWR"][1]}}
                    <hr>
                    {{$ATC["TWR"][2]}} {{$ATC["TWR"][3]}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["GND"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["GND"]["callsign"]}}</h4>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["GND"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["GND"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["GND"][0]}}
                    <hr>
                    {{$ATC["GND"][1]}}
                    <hr>
                    {{$ATC["GND"][2]}} {{$ATC["GND"][3]}}
                </div>
            </div>
        </div>
        @endif
        @if ($ivao["FSS"] != null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>{{ $ivao["FSS"]["callsign"]}}</h4>
                </div>
                <div class="card-body text-center">
                    Freq: {{ $ivao["FSS"]["atcSession"]["frequency"] }} Mhz
                    <hr>
                    Revision: {{$ivao["FSS"]["atis"]["revision"]}}
                    <hr>
                    {{$ATC["FSS"][0]}}
                    <hr>
                    {{$ATC["FSS"][1]}}
                    <hr>
                    {{$ATC["FSS"][2]}} {{$ATC["FSS"][3]}}
                </div>
            </div>
        </div>
        @endif
        @if($ivao["APP"] == null && $ivao["TWR"] == null && $ivao["GND"] == null && $ivao["FSS"] == null)
        <div class="col">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header text-center text-warning">
                    <h4>No ATC</h4>
                </div>
            </div>
        </div>
        @endif
    </div>
    <hr>
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
                </tr>
            </thead>
            <tbody>
                @foreach ($Pilot["arrivals"]["data"] as $PilotData)

                <tr>
                    <td scope="row">{{ $PilotData["callsign"] }}</td>
                    <td>{{ $PilotData["lastTrack"]["state"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["route"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["flightRules"] }}</td>
                    <td>{{ $PilotData["flightPlan"]["departureId"] }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>