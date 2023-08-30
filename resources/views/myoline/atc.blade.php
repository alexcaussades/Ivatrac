@extends("metar-base")

@section("title", "Online")


@include("navbar")

@section('content')

<div class="container mt-5">
    <h2>{{$atc["atis"][1]}} ({{$atc["callsign"]}}) </h2>
    <div class="text-muted">Tracker Id : {{$atc['id_session']}}</div>

    <div class="card text-white bg-dark">
        <div class="card-body">
            <h4 class="card-title d-flex align-items-center">On Live <span class="material-symbols-outlined text-danger ms-2 blink">radio_button_checked</span></h4>
            <p class="card-text">
            <div class="row">
                <div class="col"> <span class="text-info">Frequency :</span> {{ $atc["frequency"] }} Mhz </div>
                <div class="col"> <span class="text-info">Time Online :</span> {{ $atc["time"] }}</div>
                <div class="col"> <span class="text-info">Revision :</span> {{ $atc["revision"] }}</div>
                <div> <span class="text-info">Metar :</span>
                    @if ($atc["atis"][0] == null)
                    <span class="text-danger">No ATIS</span>
                    @else
                    <span class="">{{$atc["atis"][3]}}</span>
                    @endif
                </div>
                <div> <span class="text-info">ATIS :</span>
                    @if ($atc["atis"][0] == null)
                    <span class="text-danger">No ATIS</span>
                    @else
                    <span class="">{{$atc["atis"][4]}}</span>
                    @endif
                </div>
            </div>
            </p>
        </div>
    </div>
    <hr>
    <div class="card text-white bg-dark">
        <div class="card-body">
            <h4 class="card-title">Services ATC</h4>
            <p class="card-text">
            <div class="row">
                <!-- Information des ATCs sur le depart -->
                <div class="col-12 mt-2">
                    @if ($plateform["plateform"]["APP"] == null)
                    @else
                    <ul><button class="btn btn-success btn-sm">{{ $plateform["plateform"]["APP"]["callsign"] }} - {{ $plateform["plateform"]["APP"]["atcSession"]["frequency"] }} Mhz</button></ul>
                    @endif
                    @if ($plateform["plateform"]["TWR"] == null)
                    @else
                    <ul><button class="btn btn-success btn-sm">{{ $plateform["plateform"]["TWR"]["callsign"] }} - {{ $plateform["plateform"]["TWR"]["atcSession"]["frequency"] }} Mhz</button></ul>
                    @endif
                    @if ($plateform["plateform"]["GND"] == null)

                    @else
                    <ul><button class="btn btn-success btn-sm">{{ $plateform["plateform"]["GND"]["callsign"] }} - {{ $plateform["plateform"]["GND"]["atcSession"]["frequency"] }} Mhz</button></ul>
                    @endif
                    @if ($plateform["plateform"]["FSS"] == null)

                    @else
                    <ul><button class="btn btn-success btn-sm">{{ $plateform["plateform"]["FSS"]["callsign"] }} - {{ $plateform["plateform"]["FSS"]["atcSession"]["frequency"] }} Mhz</button></ul>
                    @endif
                </div>
            </div>
            </p>
        </div>
    </div>
    <hr>
    <div class="col-12">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h4 class="card-title">Pilots</h4>
                <p class="card-text">
                <div class="row">
                    <!-- Information des ATCs sur le depart -->
                    <div class="col-12 mt-2">
                        @if ($fly["fly"]["departure"]["count"] == 0)
                        <div class="alert alert-info" role="alert">
                            No departure
                        </div>
                        @else
                        <table class="table table-striped table-inverse table-responsive text-white">
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
                                @foreach ($fly["fly"]["departure"]["data"] as $PilotData)

                                <tr>
                                    <td class="text-white" scope="row">{{ $PilotData["callsign"] }}</td>
                                    <td class="text-white">{{ $PilotData["lastTrack"]["state"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["route"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["flightRules"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["arrivalId"] }}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                </p>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-12">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h4 class="card-title">Pilots</h4>
                <p class="card-text">
                <div class="row">
                    <!-- Information des ATCs sur le depart -->
                    <div class="col-12 mt-2">
                        @if ($fly["fly"]["arrivals"]["count"] == 0)
                        <div class="alert alert-info" role="alert">
                            No departure
                        </div>
                        @else
                        <table class="table table-striped table-inverse table-responsive text-white">
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
                                @foreach ($fly["fly"]["arrivals"]["data"] as $PilotData)

                                <tr>
                                    <td class="text-white" scope="row">{{ $PilotData["callsign"] }}</td>
                                    <td class="text-white">{{ $PilotData["lastTrack"]["state"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["route"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["flightRules"] }}</td>
                                    <td class="text-white">{{ $PilotData["flightPlan"]["arrivalId"] }}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        @endif
                </div>
                </p>
            </div>
        </div>
    </div>