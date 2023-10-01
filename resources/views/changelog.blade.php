@extends("index-base")

@section('content')

<div class="col-12">
    <div class="card border-dark">
        <div class="card-header bg-dark border-dark text-white text-opacity-90">
            <h1 class="container title d-flex align-items-center">IVATRAC <span class="material-symbols-outlined fs-1">connecting_airports</span></h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="text-center mt-3 fw-bold fs-4">
            Recent IVATRAC Updates

        </div>
    </div>

    @foreach ($data as $info)
    <div class="card-body border-0">
        <h4 class="card-title text-primary text-opacity-75 fw-semibold">{{$info["name"]}}</h4>
        <p class="card-text">
            <span class="fw-bold fs-6">Release Date: {{$info["date"]}}</span>
            <br>
            @if ($info["version"] != null)
            <span class="fw-bold fs-6">Version: {{$info["version"]}}</span>
            @endif
            @foreach ($info["option"] as $k )
            <li class="mt-2"><span class="mt-5 "><button class="btn btn-sm rounded-1 btn-{{$k["btn"]}}">{{$k["type"]}}</button> <span class=" text-muted">{{$k["description"]}}</span></span></li>
            @endforeach
        </p>
    </div>
    <div class="card-footer text-muted border-0">
        <hr>
    </div>
    @endforeach