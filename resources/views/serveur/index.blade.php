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
    <h1> Bienvenue {{ auth()->user()->name }}</h1>

    @auth('admin')
    <div class="alert alert-info" role="alert">
        Type de compte est : Administrateur ! Besoin des logs ? <a href="{{ Route("logs") }}" class="alert-link">Cliquez ici</a>.

    </div>

    @endauth
    @auth('modo')
    <div class="alert alert-info" role="alert">
        Type de compte est : Modérateur ! Besoin des logs ? <a href="{{ Route("logs.modo") }}" class="alert-link">Cliquez ici</a>.
    </div>

    @endauth

    <div class="d-flex justify-content-start">
        <form action="{{ Route("auth.logout") }}" method="get">
            <button type="submit" class="btn btn-danger btn-sm" title="Déconnection de votre compte du site intenet">Se déconnecter</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-dark btn-sm" title="Une demande de supression de votre c'est ici !" disabled>Demande de supression</button>
        </form>
        <form action="{{ Route("serveur.api") }}">
            <button type="submit" class="ms-1 btn btn-info btn-sm position-relative" title="Accès l'API">API <span class="badge rounded-pill text-bg-danger">New</span></button>
        </form>
        @auth('admin')
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-success btn-sm" title="Accès au panel administrateur" disabled>Panel Admin</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-warning btn-sm" title="Accès au panel modérateur" disabled>Panel Modo</button>
        </form>
        @endauth
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
                        @if (auth()->user()->email_verified_at == 0)
                        <li> <span class="text-danger"> Your Adress E-mail is not verified</span></li>
                        @else
                        <li> <span class="text-success">Your Adress E-mail is verified</span></li>
                        @endif
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
                    </ul>
                    </p>
                </div>
            </div>
        </div>
        <hr class="mt-2">



    </div>