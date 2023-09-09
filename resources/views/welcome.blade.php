@extends("index-base")

@section('content')


<div class="col-12">

    <div class="card border-dark">
        <div class="card-header bg-dark border-dark text-white text-opacity-90">
            <h1 class="container title d-flex align-items-center">IVATRAC <span class="material-symbols-outlined fs-1">connecting_airports</span></h1>
        </div>
    </div>
</div>
</div>
<div class="container">

    @auth
    <h4 class="mt-3"> Welcome {{ auth()->user()->name }} ({{auth()->user()->vid}})</h4>
    @endauth
    @include('nav-wellcome')
    <div class="row mt-2">
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">Metar</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">air</span> <span class="ms-2"> Search the metar at the plateform </span></p>
                    <p class="card-footer text-center"><a href="{{ Route("metars.index")}}" class="btn btn-success">Search Metar</a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">Pirep</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">description</span> <span class="ms-2"> Register and store the pirep (FPL)</span></p>
                    @auth
                    <p class="card-footer text-center"><a href="{{ Route("pirep.index")}}" class="btn btn-success">Creat FPL</a></p>
                    @endauth
                    @guest
                    <p class="card-footer text-center"><a href="{{ Route("auth.login")}}" title="register on the platform only" class="btn btn-secondary">Creat FPL</a></p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="d-flex align-items-center align-items-end "><span class="material-symbols-outlined">database </span> Version: {{$idlast["id"]}} <span class="material-symbols-outlined ms-2">update</span> Next update at: {{$heurechange}} UTC</div>
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center">Total</h4>
                    <p class="card-text text-center">{{ $whazzup["total"] }}</p>
                </div>
            </div>
        </div>
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center">ATC</h4>
                    <p class="card-text text-center">{{ $whazzup["atc"] }}</p>
                </div>
            </div>
        </div>
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center">Pilot</h4>
                    <p class="card-text text-center">{{ $whazzup["pilot"] }}</p>
                </div>
            </div>
        </div>
    </div>



    @endsection