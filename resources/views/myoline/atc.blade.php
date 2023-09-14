@extends("metar-base")

@section("title", "Online")


@include("navbar")

@section('content')

<div class="container mt-5">
    <h2>{{$atc["callsign"]}} </h2>
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
                    <span class="text-danger">No Metar</span>
                    @else
                    <span class="">{{$atc["metar"]}}</span>
                    @endif
                </div>
                <div> <span class="text-info">Taf :</span>
                    @if ($atc["atis"][0] == null)
                    <span class="text-danger">No taf</span>
                    @else
                    <span class="">{{$atc["taf"]}}</span>
                    @endif
                </div>
                <div> <span class="text-info">ATIS :</span>
                    @if ($atc["atis"][0] == null)
                    <span class="text-danger">No ATIS</span>
                    @else
                    <span class="">{{$atc["atis"]}}</span>
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
                @if ($chart_ivao != null)
                   <div class="mt-2"><a href="{{$chart_ivao}}" target="_blank"><button class="btn btn-info btn-sm text-black"><span class=" d-flex d-inline"><span class="material-symbols-outlined">description</span>Memo IVAO</span></button></a></div>
                @endif
            <div class="row">
                <!-- Information des ATCs sur le depart -->
                <div class="col-12 mt-2">
                    @if ($plateform["atc_open"]!= null)
                        @foreach ($plateform["atc_open"] as $openatc)
                            @if ($openatc["composePosition"] != $atc["callsign"])
                                <li class="mt-2"><button class="btn btn-success btn-sm">{{ $openatc["composePosition"] }} - {{ $openatc["frequency"] }} Mhz</button></li>
                            @endif
                        @endforeach
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
                <h4 class="card-title">Departure</h4>
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
                <h4 class="card-title">Arrival</h4>
                <p class="card-text">
                <div class="row">
                    <!-- Information des ATCs sur le depart -->
                    <div class="col-12 mt-2">
                        @if ($fly["fly"]["arrivals"]["count"] == 0)
                        <div class="alert alert-info" role="alert">
                            No Arrival
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
    </div>
</div>
