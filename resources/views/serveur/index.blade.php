@extends("base")

@section("title", "Serveur - $users->name_rp ")

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
    <h1> Bienvenue {{ $users->name_rp }} </h1>
    <form action="{{ Route("auth.logout") }}" method="get">
        <button type="submit" class="btn btn-danger">Se déconnecter</button>
    </form>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card text-left">
                <div class="card-body">
                    <h4 class="card-title account">Information de contact</h4>
                    <p class="card-text">
                    <ul>
                        <li> <strong>Email :</strong> {{ $users->email }} </li>
                        @if ($users->whiteList == 1)
                        <li><strong> Whitelist :</strong>
                            <status>En attente</status>
                        </li>
                        @elseif ($users->whiteList == 2)
                        <li><strong> Whitelist :</strong> <status-good>Accepté</status-good> </li>
                        @else
                        <li><strong> Whitelist :</strong> <status-off> Pas de demande </status-off></li>
                        @endif
                        <li><strong> Discord :</strong> {{ $users->discord_users }} </li>
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
                        @if ($users->age == 1)
                        <li> <strong>Age :</strong> <status-good>Check condition</status-good> </li>
                        @endif
                        @if ($users->condition == 1)
                        <li> <strong>Condition :</strong> <status-good>Check condition</status-good> </li>
                        @endif
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endsection