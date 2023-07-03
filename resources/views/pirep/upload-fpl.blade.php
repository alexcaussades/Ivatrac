@extends("metar-base")

@section("title", "Metar and TAF IVAO")


@include("navbar")

@section('content')


<div class="container">

    <div class="row">
        <div class="col-12">
            <h1>Metar and TAF</h1>
            <div class="alert alert-warning" role="alert">
                <strong>The information provided by the website is not to be used for flights ! </strong>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <form action="{{ Route('pirep.index') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for=""><span class="d-flex align-items-center"><span class="material-symbols-outlined">file_open</span> Your upload FPL</span></label>
                            <input type="file" class="form-control" name="fpl">
                            <button type="submit" class="btn btn-success mt-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">arrow_forward_ios</span>Search</span></button>
                        </div>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection