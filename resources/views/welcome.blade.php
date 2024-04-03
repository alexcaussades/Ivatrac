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
        <div class="col-12">
            <div class="alert alert-info update_font" role="alert">
                <p class="update_txt"><strong> {{$update["date"]}} - Update {{$update["name"]}} </strong> view the full <a href="{{route("changelog")}}" class="text-reset">Changelog</a>.</p>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center text-info">Request information</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">air</span> <span class="ms-2"> Search for metars or users </span></p>
                    <p class="card-footer text-center"><a href="{{ Route("metars.index")}}" class="btn btn-success">Search IVAO</a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark">
                <div class="card-body bg-dark border-dark text-white text-opacity-75">
                    <h4 class="card-title text-center text-info">Fligth Plan</h4>
                    <p class="card-text d-flex align-items-center"><span class="material-symbols-outlined ms-2">description</span> <span class="ms-2"> View the FPL submit on IVAO</span></p>
                    @auth
                    <p class="card-footer text-center"><a href="{{ Route("pirep.index")}}" class="btn btn-success">See My Fligth Plan</a></p>
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

    @auth()
    <!-- <hr>

    <div class="container">
        <h5>My Favorites</h5>
        <div class="row">
            <div class="col-4 mt-2">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h4 class="card-title text-center fs-5">LFBL</h4>
                        <p class="card-text text-center text-info metar_font">LFBL 201930Z AUTO 25006KT 220V280 CAVOK 07/07 Q1017 TEMPO 3000 SHRA BKN004 BKN020TCU</p>
                        <p class="card-text text-center departures_font"> DEP: 15 / ARR: 10 </p>
                    </div>
                </div>
            </div>
            <div class="col-4 mt-2">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h4 class="card-title text-center fs-5">LFBL</h4>
                        <p class="card-text text-center text-info metar_font">LFBL 201930Z AUTO 25006KT 220V280 CAVOK 07/07 Q1017 TEMPO 3000 SHRA BKN004 BKN020TCU</p>
                        <p class="card-text text-center departures_font"> DEP: 15 / ARR: 10 </p>
                    </div>
                </div>
            </div>
            <div class="col-4 mt-2">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h4 class="card-title text-center fs-5">LFBL</h4>
                        <p class="card-text text-center text-info metar_font">LFBL 201930Z AUTO 25006KT 220V280 CAVOK 07/07 Q1017 TEMPO 3000 SHRA BKN004 BKN020TCU</p>
                        <p class="card-text text-center departures_font"> DEP: 15 / ARR: 10 </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 -->




    @endauth

    <hr>
    <div class="container mt-2">
        <h5>IVAO Event FR</h5>
        
        <div class="row">
            @foreach($event_fr as $events)
            
            <div class="col-6 mt-2">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        @if ($events[0]["type"] == "training")
                            @if (ENV('APP_ENV') == 'local')
                            <img class="card-img-top" src="{{ asset("asset/img/training/traning.webp") }}" alt="">
                            @else
                            <img class="card-img-top" src="{{ asset("public/asset/img/training/traning.webp") }}" alt="">
                            @endif  
                        @endif
                        @if ($events[0]["type"] == "exam")
                            @if (ENV('APP_ENV') == 'local')
                            <img class="card-img-top" style="width: 18vw; center" src="{{ asset("asset/img/exam/logo_exam.jpeg") }}" alt="">
                            @else
                            <img class="card-img-top" style="width: 18vw; center" src="{{ asset("public/asset/img/exam/logo_exam.jpeg") }}" alt="">
                            @endif  
                        @endif
                        <p class="card-text text-center text-info">{{$events[0]["name"]}}</p>
                        <p class="card-text text-center ">{{$events[0]["description"]}}</p>
                        <p class="card-text text-center "> {{$events[0]["started_at"]}} </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    @endsection