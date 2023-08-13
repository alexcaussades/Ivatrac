@extends("base")

@section("title", "Login")

@include("navbar")

@section('content')

<div class="container">
    <h1> Forgot Password </h1>

    <div class="row">
        <div class="col-sm">
            <form action="{{ route("auth.forget") }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email"><span class="d-flex align-items-center"><span class="material-symbols-outlined">mail</span> Email <span class="text-danger fw-bold ms-2">*</span></span></label>
                    @if( Cookie::get("email-Users") )
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId" value="{{ Cookie::get("email-Users") }}" required>
                    @else
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId" required>
                    @endif
                </div>
                <button type="submit" class="btn btn-danger mt-2"><span class="d-flex align-items-center"><span class="material-symbols-outlined">lock_reset</span> Request New Password</span></button>
            </form>
        </div>
    </div>
</div>

@endsection