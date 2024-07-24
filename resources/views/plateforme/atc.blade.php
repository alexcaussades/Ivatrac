@extends("metar-base")

@section("title", "Information Air Traffic Control Ivao")

@include("navbar-metar")

@section('content')

<div class="container">
    <p class="text-center">
    <div class="fw-bolder fs-3 text-primary text-center text-uppercase mt-5">{{$icao}} Information</div>
    </p>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-primary text-center">Metar</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <div class="text-uppercase">{{$metar["metar"]}}</div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-primary text-center">{{ $atc["departure"]["count"] < 1 ? "Departure" : "Departures" }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <div class="fw-bolder fs-5 text-center text-uppercase">{{$atc["departure"]["count"]}}</div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-primary text-center">{{ $atc["arrival"]["count"] < 1 ? "Arrival" : "Arrivals" }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <div class="fw-bolder fs-5 text-center text-uppercase">{{ $atc["arrival"]["count"]}}</div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-2">
    <hr>
</div>

<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">ATC online</h5>
                </div>
                <div class="card-body d-flex justify-content-center">
                    @foreach ( $atc["atc"] as $atcs )
                    <div class="row ms-2 ps-2">
                        <div class="card text-white bg-success ">
                            <div class="card-body">
                                <h4 class="card-title fs-6 ">{{$atcs["callsign"]}}</h4>
                                <p class="card-text">
                                <div class="fs-6 text-center">{{$atcs["frequency"]}} Mhz</div>
                                <div class="fs-6 text-center">Online: {{$atcs["time"]}}</div>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach ( $atc["fir"] as $atcs )
                    <div class="row ms-2 ps-2">
                        <div class="card text-white bg-primary ">
                            <div class="card-body">
                                <h4 class="card-title fs-6 ">{{$atcs["callsign"]}}</h4>
                                <p class="card-text">
                                <div class="fs-6 text-center">{{$atcs["frequency"]}} Mhz</div>
                                <div class="fs-6 text-center">Online: {{$atcs["time"]}}</div>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-2">
    <hr>
</div>

<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Plane</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Flight number</th>
                                        <th>Time of departure :</th>
                                        <th>Arrivals </th>
                                        <th>Type Aircraft</th>
                                        <th>Rules</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($atc["departure"]["data"] as $departures)
                                    <tr>
                                        <td>{{$departures["callsign"]}}</td>
                                        <td>{{$departures["departureTime"]}}</td>
                                        <td>{{$departures["arrival"]}}</td>
                                        <td>{{$departures["model"]}}</td>
                                        <td>{{$departures["rule"]}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Flight number</th>
                                        <th>Time of arrival :</th>
                                        <th>Type Aircraft</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($atc["arrival"]["data"] as $arrivals)
                                    <tr>
                                        <td>{{$arrivals["callsign"]}}</td>
                                        <td>{{$arrivals["eta"]}}</td>
                                        <td>{{$arrivals["wakeTurbulence"]}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>