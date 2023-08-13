@extends("base")

@section("title", "Login")

@include("navbar")

@section('content')

<div class="container">
    <h1> Login The Intranet </h1>

    <div class="row">
        <div class="col-sm">
            <form action="{{ route("auth.login") }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email"><span class="d-flex align-items-center"><span class="material-symbols-outlined">mail</span> Email <span class="text-danger fw-bold ms-2">*</span></span></label>
                    @if( Cookie::get("email-Users") )
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId" value="{{ Cookie::get("email-Users") }}" required>
                    @else
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId">
                    @endif
                    <small id="helpId" class="text-muted">Help text</small>
                </div>
                <div class="form-group">
                    <label for="password"><span class="d-flex align-items-center"><span class="material-symbols-outlined">key</span> Password <span class="text-danger fw-bold ms-2">*</span></span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="helpId" required>
                    <small id="helpId" class="text-muted">Help text</small>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"> Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary"><span class="d-flex align-items-center"><span class="material-symbols-outlined">lock_open</span>Login</span></button>
            </form>
        </div>

        <div class="col-sm mt-3">

            <!-- Button trigger modal -->
            <a href="{{ route('auth.register') }}"> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <span class="d-flex align-items-center"><span class="material-symbols-outlined">person_add</span>Register</span>
                </button></a>
            <a href="{{ route('auth.forget') }}"> <button type="button" class="btn btn-warning">
                    <span class="d-flex align-items-center"><span class="material-symbols-outlined">lock_reset</span>Forget Password</span>
                </button></a>
        </div>
        @error("error")
        <div class="alert alert-danger mt-2" role="alert">
            {{ $message }}
        </div>
        @enderror


    </div>


</div>


@endsection