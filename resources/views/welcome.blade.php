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
                    <h4 class="card-title text-center text-info">Metar</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">air</span> <span class="ms-2"> Search the metar at the plateform </span></p>
                    <p class="card-footer text-center"><a href="{{ Route("metars.index")}}" class="btn btn-success">Search Metar</a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center text-info">FPL</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">description</span> <span class="ms-2"> Register and store the FPL</span></p>
                    @auth
                    <p class="card-footer text-center"><a href="#" class="btn btn-success">Comming soon</a></p>
                    @endauth
                    @guest
                    <p class="card-footer text-center"><a href="{{ Route("auth.login")}}" title="register on the platform only" class="btn btn-secondary">Register Only</a></p>
                    @endguest
                </div>
            </div>
        </div>
        <div class="col-6 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center text-info">Bookings ATC World</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined">calendar_month</span> <span class="ms-2">See the ATC world reserves of the day</span></p>
                    @auth
                    <p class="card-footer text-center"><a href="{{ Route("ivao.bookings")}}" class="btn btn-success">See Bookings</a></p>
                    @endauth
                    @guest
                    <p class="card-footer text-center"><a href="{{ Route("auth.login")}}" title="register on the platform only" class="btn btn-secondary">Register Only</a></p>
                    @endguest
                </div>
            </div>
        </div>
        <div class="col-6 mt-2">
            <div class="card text-white bg-dark">
                @if (isset($online["callsign"]))
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center text-info">ONLINE</h4>
                    <p class="card-text text-center"><span class="ms-2">{{$online["callsign"]}}</span></p>
                    <p class="card-footer text-center"><a href="{{Route("vid", [$online["user"]["id"]])}}" class="btn btn-success">See my online</a></p>
                </div>
                @elseif (Session::get("ivao_tokens"))
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">OFFLINE</h4>
                    <p class="card-text text-center"><span class="ms-2">Are you not online on IVAO to start a pilot or controller session? </span></p>
                    <p class="card-footer text-center">
                        <a href="https://www.ivao.aero/" target="_blank" title="ivao world website" class="btn btn-primary">IVAO</a>
                        <a href="https://wiki.ivao.aero/en/home" target="_blank" title="ivao wiki website" class="btn btn-primary">Wiki IVAO</a>
                    </p>
                </div>
                @else
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center">My IVAO account</h4>
                    <p class="card-text text-center"><span class="ms-2">Login to your ivao account is required for several actions on this website.</span></p>
                    <p class="card-footer text-center"><a href="{{ Route("ivao.connect")}}" title="register on the platform only" class="btn btn-primary">SSO IVAO</a></p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center text-info">Total</h4>
                    <p class="card-text text-center text-info">{{ $whazzup["total"] }}</p>
                </div>
            </div>
        </div>
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center text-info">ATC</h4>
                    <p class="card-text text-center text-info">{{ $whazzup["atc"] }}</p>
                </div>
            </div>
        </div>
        <div class="col-4 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center text-info">Pilot</h4>
                    <p class="card-text text-center text-info">{{ $whazzup["pilot"] }}</p>
                </div>
            </div>
        </div>
    </div>



    @endsection