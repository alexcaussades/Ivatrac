@extends("metar-base")

@section("title", "Online")


@include("navbar")

@section('content')

<div class="container mt-5">
    <h2>{{$pilot["callsign"]}}</h2>
    <div class="text-muted">Tracker Id : {{$pilot['id_session']}}</div>

    <div class="card text-white bg-dark">
        <div class="card-body">
            <h4 class="card-title d-flex align-items-center">On Live <span class="material-symbols-outlined text-danger ms-2 blink">radio_button_checked</span></h4>
            <p class="card-text">
            <div class="row">
                <div class="col"> <span class="text-info">Altitude :</span> {{ $pilot["lastTrack"]["altitude"] }} ft </div>
                <div class="col"> <span class="text-info">Transponder :</span> {{ $pilot["lastTrack"]["transponder"] }} </div>
                <div class="col"> <span class="text-info">Arrival Distance :</span> {{ $pilot["lastTrack"]["arrivalDistance"] }} NM</div>
                <div class="col"> <span class="text-info">ETA : </span>{{ $pilot["arrival_time"] }} UTC </div>
                <div class="col"> <span class="text-info">State : </span>{{ $pilot["lastTrack"]["state"] }} </div>
                <div class="col"> <span class="text-info">Time Online : </span>{{ $pilot["lastTrack"]["time"] }} </div>
            </div>
            </p>
        </div>
    </div>
    <hr>
    <!-- Service metar -->
    <div class="row">
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <p class="card-text">
                        <span class="text-info"> Departure : </span> {{ $pilot["flightPlan"]['departureId'] }}
                        <hr>
                        <span class="text-info"> METAR : </span> {{ $metar["departure"]["metar"] }}
                        <hr>
                        <span class="text-info"> TAF : </span>{{ $metar["departure"]["taf"] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <p class="card-text">

                        <span class="text-info"> Arrival : </span> {{ $pilot["flightPlan"]['arrivalId'] }}
                        <hr>
                        <span class="text-info"> METAR : </span>{{ $metar["arrival"]["metar"] }}
                        <hr>
                        <span class="text-info"> TAF : </span>{{ $metar["arrival"]["taf"] }}
                        <hr>


                        <span class="text-info"> ILS : </span>
                        @foreach ($chart["arrival"]["airac"]["ils"] as $ils)
                        @if ($ils["type"] != "T")
                    <div><span class="text-whyte">RWY: {{$ils["loc_runway_name"]}} | CAT: {{$ils["type"]}} | FRQ: {{$ils["frequency"]}} | HDG: {{$ils["loc_heading"]}} </span></div>
                    @endif
                    @endforeach
                    <span class="text-info"> RNAV : </span>
                    @foreach ($chart["arrival"]["airac"]["ils"] as $ils)
                    @if ($ils["type"] == "T")
                    <div><span class="text-whyte">RWY: {{$ils["loc_runway_name"]}} | FRQ: {{$ils["frequency"]}} | HDG: {{$ils["loc_heading"]}} </span></div>
                    @endif
                    @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <!-- Service ATCS -->
    <div class="card text-white bg-dark">
        <div class="card-body">
            <h4 class="card-title">Services ATC</h4>
            <p class="card-text">
            <div class="row">
                <div class="col">
                    <span class="text-info">Departure :</span><br>
                    <div class="row">
                        <div class="col-6">
                            
                        </div>
                    </div>
                </div>
                <div class="col">
                    <span class="text-info">Arrival :</span><br>
                    <div class="row">
                        <div class="col-6">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Information des ATCs sur le depart -->
                <div class="col mt-2">
                    @if ($atc["depature"][0]["atc_open"]!= null)
                    @foreach ($atc["depature"][0]["atc_open"] as $openatc)
                    <li class="mt-2"><button class="btn btn-success btn-sm">{{ $openatc["composePosition"] }} - {{ $openatc["frequency"] }} Mhz</button></li>
                    @endforeach
                    @endif
                </div>
                <!-- Information des ATCs sur l'arrivée -->
                <div class="col mt-2">
                    @if ($atc["arrival"][0]["atc_open"]!= null)
                    @foreach ($atc["arrival"][0]["atc_open"] as $openatc)
                    <li class="mt-2"><button class="btn btn-success btn-sm">{{ $openatc["composePosition"] }} - {{ $openatc["frequency"] }} Mhz</button></li>
                    @endforeach
                    @endif
                </div>
            </div>
            <!-- Cartes des aréodromes -->
            @if ($chart["departure"]["IFR"] == null)

            @else
            <div class="row">
                <div class="col">
                    <span class="text-info">Chart :</span><br>
                    <a href="{{$chart["departure"]["IFR"]}}" target="_blank"> <button type="submit" class="btn btn-info text-dark ms-5"><span class="d-flex align-items-center"><span class="material-symbols-outlined">assistant_navigation</span>&nbsp IFR</span></button></a>
                    <a href="{{$chart["departure"]["VFR"]}}" target="_blank"> <button type="submit" class="btn btn-info text-dark ms-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">navigation</span> VFR</span></button></a>
                </div>
                <div class="col">
                    <span class="text-info">Chart :</span><br>
                    <a href="{{$chart["arrival"]["IFR"]}}" target="_blank"> <button type="submit" class="btn btn-info text-dark ms-5"><span class="d-flex align-items-center"><span class="material-symbols-outlined">assistant_navigation</span>&nbsp IFR</span></button></a>
                    <a href="{{$chart["arrival"]["VFR"]}}" target="_blank"> <button type="submit" class="btn btn-info text-dark ms-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">navigation</span> VFR</span></button></a>
                </div>
            </div>
            @endif
            </p>
        </div>
    </div>
    <hr>
    <!-- Détails du plans de vol -->
    <div>
        <div class="row mt-2">
            <div class="col">
                <div class="form-group">
                    <label for="" class="text-primary">Departure :</label>
                    <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['departureId'] }}" disabled>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">Arrival :</label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['arrivalId'] }}" disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">Alternate :</label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['alternateId'] }}" disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">flight Rules : </label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['flightRules'] }}" disabled>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">Speed :</label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['speed'] }}" disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">Level (FL) :</label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['level'] }}" disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">POB : </label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['personsOnBoard'] }}" disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="" class="text-primary">Depature Time : (UTC) </label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['departureTime'] }}" disabled>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-2">
                    <div class="form-group">
                        <label for="" class="text-primary">Aircraft :</label>
                        <input type="text" class="form-control" value="{{ $pilot["aircraft"]}}" disabled>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="" class="text-primary">Aircraft Equipments :</label>
                        <input type="text" class="form-control" value="{{ $pilot["flightPlan"]['aircraftEquipments'] }}" disabled>
                    </div>
                </div>
                <div class="col">

                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <label for="Route" class="text-primary">Route : </label>
                    <textarea class="form-control" rows="3" disabled>{{ $pilot["flightPlan"]["route"] }}</textarea>
                </div>
            </div>
            <hr>
        </div>

        @if (ENV('APP_ENV') == 'local')
        <script src="{{ asset("asset/js/update_friend.js") }}"></script>
        @else
        <script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
        @endif