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
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Metar</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="fw-bolder fs-5 text-primary text-center text-uppercase mt-5">{{$metar["metar"]}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Departure</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="fw-bolder fs-5 text-primary text-center text-uppercase mt-5">{{$atc["departure"]["count"]}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Arrival</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="fw-bolder fs-5 text-primary text-center text-uppercase mt-5">{{$atc["arrival"]["count"]}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>