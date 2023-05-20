@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')


<div class="container">
    <table class="table table-striped table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>id</th>
                <th>type</th>
                <th>message</th>
                <th>user</th>
                @auth('admin')
                    <th>ip</th>    
                @endauth
                <th>date</th>
                @auth('admin')
                    <th>action</th>                    
                @endauth
                <!-- @auth('modo')
                    <th>action</th>                    
                @endauth -->
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
                        <!-- @auth('modo')
                            <td>
                                <form action="{{ Route("logs.modo.delete", $log->id) }}" method="post">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="btn btn-warning btn-sm" title="Supprimer ce log">testing</button>
                                </form>
                            </td>
                        @endauth -->
                    </tr>
                @endforeach
            </tbody>
    </table>
</div>

@endsection