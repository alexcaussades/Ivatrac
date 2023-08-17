@extends("base")

@section("title", "Gestion - Panel de control Admins - Moderateurs ")

@include("navbar")

@section('content')


<div class="container">
    <div class="fs-3 mt-2">Management Panel</div>
    <hr>
</div>

@auth(Auth()->user()->role == "9")
<div class="container">
    <div class="fs-4 mt-2">Manager</div>
    <div class="row mt-2">
        <div class="col">
            <div class="card mb-3" style="max-width: 18rem;">
                <div class="card-header">Promot Users</div>
                <div class="card-body">
                    <p class="card-text d-flex justify-content-center"><a href="{{ Route("serveur.secrity.add") }}"><button class="btn btn-success">Administrator or Moderator</button></a></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3" style="max-width: 18rem;">
                <div class="card-header">Reverse administrator</div>
                <div class="card-body">
                    <p class="card-text d-flex justify-content-center"><a href="#"><button class="btn btn-warning">Reverse Administrator</button></a></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3" style="max-width: 18rem;">
                <div class="card-header">Remove Users</div>
                <div class="card-body">
                    <p class="card-text d-flex justify-content-center"><a href="#"><button class="btn btn-danger">Remove Users</button></a></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3" style="max-width: 18rem;">
                <div class="card-header">Modify Users</div>
                <div class="card-body">
                    <p class="card-text d-flex justify-content-center"><a href="#"><button class="btn btn-info">Modify and Update Users</button></a></p>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@endauth

@endsection