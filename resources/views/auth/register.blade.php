@extends("base")

@section("title", "Sing Up")

@include("navbar")

@section('content')


<div class="container">

    <h1>Sign Up</h1>

    @if ($errors->any())
      
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      
    @endif
   
    <form action="#" method="POST">
        @csrf
        <div class="form-group mt-2">
            <label for="name">First Name</label>
            <input type="text" name="name_first" id="name_first" class="form-control" placeholder="Your First Name" aria-describedby="helpId" value="{{ old("name_rp") }}">
        </div>

        <div class="form-group mt-2">
            <label for="name">Last Name</label>
            <input type="text" name="name_last" id="name_last" class="form-control" placeholder="Your Last Name" aria-describedby="helpId" value="{{ old("name_rp") }}">
        </div>

        <div class="form-group mt-2">
            <label for="name">VID</label>
            <input type="text" name="vid" id="vid" class="form-control" placeholder="Your Vid (https://ivao.aero/)" aria-describedby="helpId" value="{{ old("name_rp") }}">
        </div>
        
        <div class="form-group mt-2">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" aria-describedby="helpId" value="{{ old("email") }}">
            
        </div>
        <div class="form-group mt-2">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="helpId">
            
        </div>
        <div class="form-group mt-2">
            <label for="password_confirmation">Password confirmation</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Password confirmation" aria-describedby="helpId">
            
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="age" id="" value="1">
            J'ai plus de 16 ans
          </label>
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="condition" id="" value="1">
            J'accepte les conditions d'utilisation <a href="#">ici</a>
          </label>
        </div>
        
        <div class="alert alert-warning mt-2" role="alert">
            <strong> Formulaire en cour de déployement !</strong>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Register</button>


</div>


@endsection