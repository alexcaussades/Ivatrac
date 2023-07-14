@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')

<div class="container">
    <h3 class="mt-2">Flight Plan System</h3>
</div>

<div class="container px-4 text-center mt-5">
    <h4 class="text-primary">INTERNATIONAL FLIGHT PLAN</h4>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="number" class="text-primary d-flex justify-content-start">Identification :</label>
                {{$json->identification ?? ''}}
            </div>
            <div class="col">
                <label for="flightRules" class="text-primary d-flex justify-content-start">Flight Rules :</label>
                {{$json->flightRules}}
            </div>
            <div class="col">
                <label for="typeOfFlight" class="text-primary d-flex justify-content-start">Type of Flight :</label>
                {{$json->typeOfFlight}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="aircraftType" class="text-primary d-flex justify-content-start">Aircraft Type :</label>
                {{$json->aircraftType}}
            </div>
            <div class="col">
                <label for="wakeTurbulence" class="text-primary d-flex justify-content-start">Wake Turbulence :</label>
                {{$json->wakeTurbulence}}
            </div>
            <div class="col">
                <label for="equipment" class="text-primary d-flex justify-content-start">Equipment :</label>
                @if($oo->upload == 1)
                    {{$json->equipment}}
                @else
                    @foreach ($json->equipment as $item)
                        {{$item}}
                    @endforeach
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="departureAerodrome" class="text-primary d-flex justify-content-start">Departure Aerodrome :</label>
                {{$json->departureAerodrome}}
            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Departure Time :</label>
                {{$json->departureTime }}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Speed :</label>
                <div class="input-group mb-3">
                    {{$json->speed }}
                    {{$json->speednumber }}
                </div>
            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Level :</label>
                <div class="input-group mb-3">
                    {{$json->LevelFL}}
                    {{$json->level}}
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Route :</label>
                {{$json->route}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="destinationAerodrome" class="text-primary d-flex justify-content-start">Destination Aerodrome :</label>
                {{$json->destinationAerodrome}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Total EET :</label>
                {{$json->eet}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Alternate Aerodrome :</label>
                {{$json->Alternate}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">2nd Alternate Aerodrome :</label>
                {{$json->Alternate2}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Other Information :</label>
                {{$json->Other}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Endurance :</label>
                {{$json->endurance}}
            </div>
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Persons on Board :</label>
                {{$json->pob}}
            </div>
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Fuel on Board :</label>
                {{$json->fuel ?? ""}} Kg
            </div>
        </div>
        <hr>
    </div>

    @endsection