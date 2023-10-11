<?php

use Illuminate\Support\Carbon;
?>
@extends("metar-base")

@section("title", "Event-Ximea")

@include("navbar")

<div class="container">
    <div><span class="material-symbols-outlined fs-1">event_note</span> <span class="areoport_font">LFMT</span></div>
</div>

<div class="container">
    <div class="row d-flex ">
        <div class="col-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center text-warning"><span class="material-symbols-outlined">flight_land</span> ARRIVALS</h4>
                    <p class="card-text areoport_font text-center">{{$r["count"]}}</p>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card text-white bg-dark w-100">
              <div class="card-body">
                <p class="card-text">
                    <span> <span class="text-info">METAR:</span> {{$metar}}</span>
                    <hr>
                    <span> <span class="text-info">TAF:</span> {{$taf}}</span>
                </p>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-2">
    <div class="row">
        <div class="col-8">
            <h4 class="text-center">Traffics</h4>
            <hr>
            <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                        <th>Callsign</th>
                        <th>Star</th>
                        <th>Wake Turbulence</th>
                        <th>ETA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($r["data"] as $traffic)
                    <tr>
                        <td scope="row">{{$traffic["callsign"]}}</td>
                        <td>{{$traffic["star"]}}</td>
                        <td>{{$traffic["wakeTurbulence"]}}</td>
                        <td>{{$traffic["eta"]}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
    </div>

</div>

@section('content')