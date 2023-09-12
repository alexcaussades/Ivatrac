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
            </div>
            @if ($chart_crr != null)
                <div class="mt-2"><a href="{{$chart_crr}}" target="_blank"><button class="btn btn-warning btn-sm text-black"><span class=" d-flex d-inline"><span class="material-symbols-outlined">attach_file</span>FILE CCR</span></button></a></div>
            @endif
        </div>
    </div>
</div>

<div class="container">
    <hr>
    <h2>ATC ONLINE</h2>
    <div class="row">
        @foreach ($atc_online as $atcs)
        @if ($atcs["callsign"] != $atc["callsign"])
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title">{{ $atcs["callsign"] }}</h4>
                    <p class="card-text">
                        <span class="text-info">Frequency:</span> {{ $atcs["frequency"] }} Mhz <br>
                        <span class="text-info">Time Online:</span> {{ $atcs["time"] }} <br>
                        <span class="text-info">Revision:</span> {{ $atcs["revision"] }} <br>
                        @if ($atcs["metar"] != null)
                        <span class="text-info">Metar:</span> {{ $atcs["metar"] }} <br>
                        @endif
                        @if ($atcs["atis"]!= null)
                        <span class="text-info">ATIS:</span> {{ $atcs["atis"] }} <br>
                        @endif
                        @if ($atcs["chart_ivao"]!= null)
                             <div class="mt-2"><a href="{{$atcs["chart_ivao"]}}" target="_blank"><button class="btn btn-info btn-sm text-black"><span class=" d-flex d-inline"><span class="material-symbols-outlined">description</span>Memo IVAO</span></button></a></div>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        @endforeach
    </div>

</div>

@if (ENV('APP_ENV') == 'local')
<script src="{{ asset("asset/js/update_friend.js") }}"></script>
@else
<script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
@endif