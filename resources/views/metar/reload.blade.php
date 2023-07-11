@extends("metar-base")

@section("title", "Metar and TAF and IVAO")

@include("navbar-metar")

@section('content')

<div class="container">
    <div class="row mt-5 border border-warning">
      <div class="col-md-6">
      <img class="card-img-top rounded" style="max-width: 100%; height: auto;" src="{{ asset('storage/404.jpg') }}" alt="">
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <div class="card-body">
          <h5 class="card-title">Oops, something went wrong!</h5>
          <p class="card-text">Please try again later.</p>
          <a href="{{ Route('metars.icao', ["icao" => $icao]) }}" class="btn btn-success"><span class="d-flex align-items-center"><span class="material-symbols-outlined">sync</span> Refresh</span></a>
        </div>
      </div>
    </div>
</div>

@endsection