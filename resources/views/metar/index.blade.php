@extends("metar-base")

@section("title", "Metar")


@include("navbar")

@section('content')


<div class="container">

    <div class="row">
        <div class="col-12 mt-2">
            <h1>Search on IVAO</h1>
            
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 mt-2">

                    <form action="{{ route("metars.icao") }}" method="get">
                        <div class="form-group">
                            <label for=""><span class="d-flex align-items-center"><span class="material-symbols-outlined">search</span> Your Search</span></label>
                            <input type="text" class="form-control" name="icao" placeholder="ICAO / LFBL | VID / 191514">
                            <button type="submit" class="btn btn-success mt-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span>Search</span></button>
                        </div>
                </div>
                </form>

            </div>
        </div>
        <hr>
        <h3 class="mt-2">Weather map </h3>
        <div class="col-6 mt-2">
            <div class="card text-white bg-dark" style="max-width: 520px;">
                @if (ENV('APP_ENV') == 'local')
                <img class="card-img-top" src="{{ asset("asset/img/webiste/temsi.png") }}" alt="">
                @else
                <img class="card-img-top" src="{{ asset("public/asset/img/webiste/temsi.png") }}" alt="">
                @endif
                <div class="card-body">
                    <p class="card-text text-center"><a href="{{Route("download.tempsi")}}" target="_blank" rel="noopener noreferrer"><button class="btn btn-success btn-sm text-white">View TEMSI</button></a></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-white bg-dark" style="max-width: 520px;">
                @if (ENV('APP_ENV') == 'local')
                <img class="card-img-top" src="{{ asset("asset/img/webiste/wintep.png") }}" alt="">
                @else
                <img class="card-img-top" src="{{ asset("public/asset/img/webiste/wintep.png") }}" alt="">
                @endif
                <div class="card-body">
                    <p class="card-text text-center text-light"><a href="{{Route("download.wintemp")}}" target="_blank" rel="noopener noreferrer"><button class="btn btn-success btn-sm text-white">View Wintep</button></a></p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection