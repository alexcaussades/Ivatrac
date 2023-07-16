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
    @endauth
    @guest
    <div class="d-flex flex-row-reverse">
        <form action="{{ Route("auth.login") }}" method="get">
            <button type="submit" class="btn btn-primary mt-2" title="Connect your account">Login</button>
        </form>
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
                    <p class="card-footer text-center"><a href="{{ Route("pirep.index")}}" class="btn btn-success">Creat FPL</a></p>
                </div>
            </div>
        </div>
    </div>

    @endsection