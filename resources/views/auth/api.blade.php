@extends("base")

@section("title", "API")

@include("navbar")

@section('content')

<div class="container">
    Votre compte est : {{ Auth::user()->name }} <br>

    <div class="d-flex justify-content-start">
        <form action="{{ Route("auth.logout") }}" method="get">
            <button type="submit" class="btn btn-danger btn-sm" title="Déconnection de votre compte du site intenet">Se déconnecter</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-primary btn-sm" title="Modification de votre profil utilisateur" disabled>Modifier</button>
        </form>
        <form action="#" method="get">
            <button type="submit" class="ms-1 btn btn-dark btn-sm" title="Une demande de supression de votre c'est ici !" disabled>Demande de supression</button>
        </form>

    </div>

    <div class="h3">votre demande de token </div>
</div>