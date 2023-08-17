@extends("base")

@section("title", "Add - Panel de control Admins - Moderateurs ")

@include("navbar")

@section('content')

<div class="container">

    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>ID</th>
                <th>Users Name</th>
                <th>VID</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td scope="row">{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->vid}}</td>
                <td>{{$user->role}}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Management Action
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Administrateur</a></li>
                            <li><a class="dropdown-item" href="#">Modérateur</a></li>
                            <li><a class="dropdown-item" href="#">Modification</a></li>
                        </ul>
                    </div>
                </td>
                @endforeach
        </tbody>
    </table>

</div>