<?php

use Illuminate\Support\Carbon;
?>
@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')

<div class="container">
    <h3 class="mt-2">Flight Plan System</h3>
</div>

<div class="container px-4 text-center mt-5">
    <h4 class="text-primary">INTERNATIONAL FLIGHT PLAN</h4>
    <p class=" d-flex justify-content-start text-muted">Fligt Plan id: {{$json["id"]}}</p>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="number" class="text-primary d-flex justify-content-start">Identification : <p class="text-dark ms-2"> {{$json["callsign"]?? ''}}</p></label>

            </div>
            <div class="col">
                <label for="flightRules" class="text-primary d-flex justify-content-start">Flight Rules :<p class="text-dark ms-2">{{$json["flightRules"]}}</p></label>

            </div>
            <div class="col">
                <label for="typeOfFlight" class="text-primary d-flex justify-content-start">Type of Flight :<p class="text-dark ms-2">{{$json["flightType"]}}</p></label>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="aircraftType" class="text-primary d-flex justify-content-start">Aircraft Type :<p class="text-dark ms-2">{{$json["aircraftId"]}}</p></label>

            </div>
            <div class="col">
                <label for="wakeTurbulence" class="text-primary d-flex justify-content-start">Wake Turbulence :<p class="text-dark ms-2">{{$json["aircraftWakeTurbulence"]}}</p></label>

            </div>
            <div class="col">
                <label for="equipment" class="text-primary d-flex justify-content-start">Equipment :</label>
                @foreach ($json["aircraftEquipments"] as $item)
                {{$item}}
                @endforeach

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="departureAerodrome" class="text-primary d-flex justify-content-start">Departure Aerodrome :<p class="text-dark ms-2">{{$json["departureId"]}}</p></label>

            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Departure Time :<p class="text-dark ms-2"> {{Carbon::parse($json["departureTime"])->format('H:i') }}</p></label>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Speed
                    <div class="input-group mb-3">
                        <p class="text-dark ms-2">{{$json["cruisingSpeedType"] }} {{$json["cruisingSpeed"] }}</p>
                    </div>
                </label>
            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Level
                    <div class="input-group mb-3">
                        <p class="text-dark ms-2"> {{$json["altitudeType"]}} {{$json["cruisingSpeed"]}} </p>
                    </div>
                </label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Route :<p class="text-dark ms-2">{{$json["route"]}}</p></label>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="destinationAerodrome" class="text-primary d-flex justify-content-start">Destination Aerodrome :<p class="text-dark ms-2"> {{$json["arrivalId"]}}</p></label>

            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Total EET :<p class="text-dark ms-2">{{Carbon::parse($json["eet"])->format('H:i')}}</p></label>

            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Alternate Aerodrome :<p class="text-dark ms-2">{{$json["alternativeId"]}}</p></label>

            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">2nd Alternate Aerodrome :<p class="text-dark ms-2">{{$json["alternative2Id"]}}</p></label>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Other Information :<p class="text-dark ms-2">{{$json["remarks"]}}</p</label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Endurance :<p class="text-dark ms-2">{{Carbon::parse($json["endurance"])->format('H:i')}}</p></label>

            </div>
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Persons on Board :<p class="text-dark ms-2"> {{$json["pob"]}}</p></label>

            </div>
        </div>
        <hr>
    </div>

    @endsection