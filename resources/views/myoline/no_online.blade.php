@extends("metar-base")

@section("title", "No Online")


@include("navbar")

@section('content')



<div>
    <div class="container">
        <h1 class="mt-10 text-center"> The user is currently offline! </h1>

        <h4 class="mt-2"> New Search </h4>
        <form action="{{ route("metars.icao") }}" method="get">
            <div class="form-group">
                <label for=""><span class="d-flex align-items-center"><span class="material-symbols-outlined">search</span> Your Search</span></label>
                <input type="text" class="form-control" name="icao" placeholder="ICAO / LFBL | VID / 191514">
                <button type="submit" class="btn btn-success mt-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span>Search</span></button>
            </div>
        </form>
    </div>
</div>


@if (ENV('APP_ENV') == 'local')
<script src="{{ asset("asset/js/update_friend.js") }}"></script>
@else
<script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
@endif