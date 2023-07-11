@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')

<div class="container">
    <h3 class="mt-2">Flight Plan System</h3>
</div>

<div class="container px-4 text-center mt-5">
    <hr>
    <div class="row gx-5 mt-5">
        <div class="col">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <p class="card-text">
                        <a href="{{ Route("pirep.create") }}" class="btn btn-primary" title="CREATE the FPL on the system"> CREATE THE FPL </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <p class="card-text">
                        <a href="{{ Route("pirep.upload") }}" class="btn btn-warning" title="UPLOAD the file system"> UPLOAD THE FPL </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection