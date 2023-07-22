@extends("index-base")

@section('content')


<div class="col-12">

    <div class="card border-dark">
        <div class="card-header bg-dark border-dark text-white text-opacity-90">
            <h1 class="container title d-flex align-items-center">IVAO - Tracker <span class="material-symbols-outlined fs-1">connecting_airports</span></h1>
        </div>
    </div>
</div>
</div>
<div class="container">
    @auth
    <h4 class="mt-3"> Welcome {{ auth()->user()->name }} ({{auth()->user()->vid}}) </h4>
    <div class="d-flex flex-row">
        <a href="{{ Route("serveur.index") }}" title="My account the website"><button type="submit" class="btn btn-primary btn-sm" >My account</button></a>
        <a href="{{ Route("auth.logout") }}" title="Disconect the website"><button type="submit" class="btn btn-danger btn-sm ms-2" >Logout</button></a>
        <!-- futur condition pour afficher si des amis sont en ligne est le nombres de en ce moment même  -->
        <a href="http://"><button type="button" class="btn btn-secondary btn-sm ms-2" disabled> Online Friends</button></a>
        <a href="https://webeye.ivao.aero/" target="_blank"><button type="button" class="btn btn-info btn-sm ms-2"> Webeye</button></a>
        <a href="https://www.ivao.aero/" target="_blank"><button type="button" class="btn btn-info btn-sm ms-2"> IVAO Website</button></a>
        <a href=""><button type="button" class="btn btn-primary btn-sm ms-2"> My FPL</button></a>
    </div>
    @endauth
    @guest
    <div class="d-flex flex-row mt-2">
        <a href="{{ Route("auth.login") }}"><button type="submit" class="btn btn-primary btn-sm" title="Disconnect your account">Login / Register</button></a>
        <!-- futur condition pour afficher si des amis sont en ligne est le nombres de en ce moment même  -->
        <a href="http://" title="register on the platform only"><button type="button" class="btn btn-secondary btn-sm ms-2" disabled> Online Friends</button></a>
        <a href="https://webeye.ivao.aero/" target="_blank"><button type="button" class="btn btn-info btn-sm ms-2"> Webeye</button></a>
        <a href="https://www.ivao.aero/" target="_blank"><button type="button" class="btn btn-info btn-sm ms-2"> IVAO Website</button></a>
        <a href="" title="register on the platform only"><button type="button" class="btn btn-secondary btn-sm ms-2" disabled> My FPL</button></a>

    </div>
    @endguest
    <div class="row mt-5">
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">Metar</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span> Search the metar at the plateform </p>
                    <p class="card-footer text-center"><a href="{{ Route("metars.index")}}" class="btn btn-success">Search Metar</a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">Pirep</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span> Register and store the pirep (FPL)</p>
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