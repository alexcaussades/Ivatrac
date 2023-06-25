@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $slug->name_rp }}</h1>
            <small><i class="bi bi-envelope"></i> {{ $users->email }}</small> <small><i class="bi bi-discord"></i> {{ $users->discord_users }}</small>
            <hr>
        </div>
    </div>
    <h3>Demande :</h3>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Profession</th>
                        <td>{{ $slug->Profession }}</td>
                    </tr>
                    <tr>
                        <th scope="row">ID de la demande</th>
                        <td>{{ $slug->id }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Date d'ajout</th>
                        <td>{{ $slug->created_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Date de modification</th>
                        <td>{{ $slug->updated_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        @if ($users->whiteList == 2)
                        <td><span class="badge text-bg-info">En attente de validation</span></td>
                        @endif
                        @if ($users->whiteList == 3)
                        <td><span class="badge text-bg-success">Accepté</span></td>
                        @endif
                    </tr>
                    <tr>
                        <th scope="row">ID du joueur</th>
                        <td>{{ $slug->id_users }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Naissance</th>
                        <td>{{ $slug->naissance}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td>{{ $slug->description}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
</div>

@endsection