@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')


<div class="container">

    <div class="row">
        <div class="col-12 mt-2">
            <h1>Flight Plan System </h1>
            <div class="alert alert-info" role="alert">
                <strong>The system is operational !</strong>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">
                            <form action="{{ Route('pirep.upload') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for=""><span class="d-flex align-items-center"><h3> Upload FPL</h3></span></label>
                                    <input type="file" class="form-control" name="fpl">
                                    <input type="hidden" name="upload" value="1">
                                    <div><small class="text-muted">Using file ".fpl" </small></div>
                                    <button type="submit" class="btn btn-primary mt-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span>Submit</span></button>
                                </div>
                            </form>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection