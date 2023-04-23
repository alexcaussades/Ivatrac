@extends("base")

@section("title", "Serveur - Dashboard ")

@auth
@include("navbar")
@endauth

@guest
<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary mx-2">Sign in</button>
            <button type="button" class="btn btn-success">Sign up</button>
        </div>
        <div class="col-md-12 mt-2">
            <h1> Bienvenue sur le serveur de test</h1>
            <h3> Naviguer dans l'univers redoutable ! </h3>
        </div>
    </div>
</div>
@endguest

@section('content')


<div class="container mt-4">
    <div class="row">

        <div class="col">
            <h5>Name :</h5> {{ $slug->name_rp }}
        </div>
        <div class="col">
            <h5>Profession :</h5> {{ $slug->Profession }}
            <div>
            </div>

        </div>
        <div class="col">
            <h5> Photo :</h5>
            <img src="https://placehold.co/150x150.png" class="rounded shadow-lg p-1 mb-2 bg-body-tertiary rounded" alt="...">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <h5>Description :</h5>
            {{ $slug->description }}
        </div>

        <div class="row mt-4">
            <div class="col">
                <h5>Creation le :</h5>
                {{ date('d/m/Y', strtotime($slug->created_at)) }}
            </div>
            @auth("web")
            <div class="col">
                <h5>Modification le :</h5>
                {{ date('d/m/Y', strtotime($slug->updated_at)) }}
            </div>
            <!-- Button trigger modal -->
            <div class="col">
                <button type="button" class="btn btn-primary" disabled>
                    Modifier
                </button>
                <button type="button" class="btn btn-danger" disabled>
                    Supprimer
                </button>
                <button type="button" class="btn btn-warning" disabled>
                    Signaler
                </button>
            </div>

            @endauth

        </div>

        @endsection