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
                <div> <span class="text-info">ATIS :</span>
                    @for ($i = 2; $i < $atis; $i++)
                        @if ($i == 0)
                            {{ $atc["atis"][$i] }}
                        @else
                            <br>{{ $atc["atis"][$i] }}
                        @endif
                        
                    @endfor
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