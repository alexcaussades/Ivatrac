@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')


<div class="container">
    <table class="table table-striped table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Message</th>
                <th>User</th>
                @auth('admin')
                <th>IP Public</th>
                @endauth
                <th>Date</th>
                @auth('admin')
                <th>Suppression</th>
                @endauth
                @auth('admin')
                <th>View</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td scope="row">{{ $log->id }}</td>
                <td>{{ $log->type }}</td>
                <td>{{ $log->message }}</td>
                <td>{{ $log->user }}</td>
                @auth('admin')
                <td>{{ $log->ip }}</td>
                @endauth
                <td>{{ $log->created_at }}</td>
                @auth('admin')
                <td>
                    <form action="{{ Route("logs.delete", $log->id)}}" method="post">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer ce log">Supprimer</button>
                    </form>
                </td>
                @endauth
                @auth('admin')
                <td>
                    <form action="{{ Route("logs.modo.id", $log->id) }}" method="get">
                        
                        <button type="submit" class="btn btn-success btn-sm" title="Voir ce log">View</button>
                    </form>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection