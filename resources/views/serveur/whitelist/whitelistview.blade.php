@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Whitelist (Gestion)</h1>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Pseudo</th>
                                <th scope="col">ID</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($whitelist as $whitelist)
                            <tr>
                                <td>{{$whitelist->name_rp}}</td>
                                <td>{{$whitelist->id_users}}</td>
                                <td>{{$whitelist->created_at}}</td>
                                <td>
                                    <div class="d-flex flex-row">
                                    <div class="p-2">
                                    <form action="{{ Route('whitelist-admin.check', $whitelist->slug)}}" method="get">
                                        @csrf
                                        <button class="btn btn-primary">View</button>
                                    </form>
                                    </div>
                                    <div class="p-2">
                                    <form action="{{ Route('whitelist-admin.edit', $whitelist->slug)}}" method="get">
                                        @csrf
                                        <button class="btn btn-success">Accepter</button>
                                    </form>
                                    </div>
                                    <div class="p-2">
                                    <form action="{{ Route('whitelist-admin.edit', $whitelist->slug)}}" method="get">
                                        @csrf
                                        <button class="btn btn-danger">Refuser</button>
                                    </form>
                                    <div>
                                </div>                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary" href="#" title="Ajout d'un membre manuellement" disabled>Ajout d'un menbre</button>
                </div>
            </div>
        </div>
    </div>
</div>
