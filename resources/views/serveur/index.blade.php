@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')


<style>
    .card-text,
    status {
        color: blue;
        font-weight: bold;
    }

    .card-text,
    status-off {
        color: grey;
        font-weight: bold;
    }

    .card-text,
    status-good {
        color: green;
        font-weight: bold;
    }

    .card-title .account {
        color: green;
    }
</style>

<div class="container">
    <h1> Bienvenue  </h1>

    <div class="d-flex justify-content-start">
        <form action="{{ Route("auth.logout") }}" method="get">
            <button type="submit" class="btn btn-danger btn-sm">Se déconnecter</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-primary btn-sm">Modifier</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-dark btn-sm">Demande de supression</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card text-left">
                <div class="card-body">
                    <h4 class="card-title account">Information de contact</h4>
                    <p class="card-text">
                    <ul>
                        <li> <strong>Email :</strong> {{ auth()->user()->email }} </li>
                        @if (auth()->user()->whiteList == 2)
                        <li><strong> Whitelist :</strong>
                            <status>En attente de validation</status>
                        </li>
                        @elseif (auth()->user()->whiteList == 3)
                        <li><strong> Whitelist :</strong> <status-good>Accepté</status-good> </li>
                        @else
                        <li><strong> Whitelist :</strong> <a href="#"><button type="submit" class="btn btn-success btn-sm">Je fais ma demande</button></a></li>
                        @endif
                        <li><strong> Discord :</strong> {{ auth()->user()->discord_users }} </li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-left">
                <div class="card-body">
                    <h4 class="card-title">Information de compte</h4>
                    <p class="card-text">
                    <ul>
                        <li> <strong>Role :</strong> {{ $role->name }} </li>
                        <li> </li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
        <hr class="mt-2">
        @if (auth()->user()->whiteList == 1)
        <div class="container">
            <h4 class="mt-2"> Crée votre Personnage </h4>

            @include("serveur.creat_perso")
        </div>
        @endif

        @if (auth()->user()->whiteList == 2)
        <div class="container">
            <h4 class="mt-2"> Votre Personnage </h4>
        </div>
        @endif



    </div>
    @endsection