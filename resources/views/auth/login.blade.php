@extends("base")

@section("title", "Connect to the Intranet")

@include("navbar")

@section('content')

<div class="container">
    <h1> Login The Intranet </h1>

    <div class="row">
    <div class="col-sm">
        <form action="{{ route("auth.login") }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId">
                <small id="helpId" class="text-muted">Help text</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="helpId">
                <small id="helpId" class="text-muted">Help text</small>
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <div class="col-sm mt-3">

        <!-- Button trigger modal -->
        <a href="{{ route('auth.register') }}"> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Register
        </button></a>

    </div>
    </div>
</div>


@endsection