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
                {{$json->identification ?? $json[0]->ID}}
            </div>
            <div class="col">
                <label for="flightRules" class="text-primary d-flex justify-content-start">Flight Rules :</label>
                {{$json->flightRules ?? $json[0]->RULES}}
            </div>
            <div class="col">
                <label for="typeOfFlight" class="text-primary d-flex justify-content-start">Type of Flight :</label>
                {{$json->typeOfFlight ?? $json[0]->FLIGHTTYPE}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="aircraftType" class="text-primary d-flex justify-content-start">Aircraft Type :</label>
                {{$json->aircraftType ?? $json[0]->ACTYPE}}
            </div>
            <div class="col">
                <label for="wakeTurbulence" class="text-primary d-flex justify-content-start">Wake Turbulence :</label>
                {{$json->wakeTurbulence ?? $json[0]->WAKECAT}}
            </div>
            <div class="col">
                <label for="equipment" class="text-primary d-flex justify-content-start">Equipment :</label>
                @if($json->equipment ?? $json[0]->EQUIPMENT)
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
                {{$json->departureAerodrome ?? $json[0]->DEPICAO}}
            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Departure Time :</label>
                {{$json->departureTime ?? $json[0]->DEPTIME}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Speed :</label>
                <div class="input-group mb-3">
                    {{$json->speed ?? $json[0]->SPEEDTYPE}}
                    {{$json->speednumber ?? $json[0]->SPEED}}
                </div>
            </div>
            <div class="col">
                <label for="departureTime" class="text-primary d-flex justify-content-start">Level :</label>
                <div class="input-group mb-3">
                    {{$json->level ?? $json[0]->LEVELTYPE}}
                    {{$json->LevelFL ?? $json[0]->LEVEL }}
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Route :</label>
                {{$json->route ?? $json[0]->ROUTE}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="destinationAerodrome" class="text-primary d-flex justify-content-start">Destination Aerodrome :</label>
                {{$json->destinationAerodrome ?? $json[0]->DESTICAO}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Total EET :</label>
                {{$json->eet ?? $json[0]->EET}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">Alternate Aerodrome :</label>
                {{$json->Alternate ?? $json[0]->ALTICAO}}
            </div>
            <div class="col">
                <label for="destinationTime" class="text-primary d-flex justify-content-start">2nd Alternate Aerodrome :</label>
                {{$json->Alternate2 ?? $json[0]->ALTICAO2}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Other Information :</label>
                {{$json->Other ?? $json[0]->OTHER}}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Endurance :</label>
                {{$json->endurance ?? $json[0]->ENDURANCE}}
            </div>
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Persons on Board :</label>
                {{$json->pob ?? $json[0]->POB }}
            </div>
            <div class="col">
                <label for="Route" class="text-primary d-flex justify-content-start">Fuel on Board :</label>
                {{$json->fuel ?? "Null"}} Kg
            </div>
        </div>
        <hr>
    </div>

    @endsection