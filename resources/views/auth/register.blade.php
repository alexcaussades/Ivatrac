@extends("base")

@section("title", "register")

@include("navbar")

@section('content')


<div class="container">

    <h1>Enregistrement</h1>

    <form action="#" method="POST">
        @csrf
        <div class="form-group mt-2">
            <label for="name">Name RP</label>
            <input type="text" name="name_rp" id="name_rp" class="form-control" placeholder="name RP" aria-describedby="helpId">
            
        </div>
        <div class="form-group mt-2">
            <label for="name">Discord Name</label>
            <input type="text" name="discordusers" id="discord" class="form-control" placeholder="username#5525" aria-describedby="helpId">
            
        </div>
        <div class="form-group mt-2">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId">
            
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
            <input type="checkbox" class="form-check-input" name="ages" id="" value="age">
            J'ai plus de 16 ans
          </label>
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="condition" id="" value="cgu">
            J'accepte les conditions d'utilisation <a href="#">ici</a>
          </label>
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="whiteList" id="" value="checkedValue">
            Je fait ma demande sur la whiteList du serveur !
          </label>
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="discord" id="" value="checkedValue">
            Je suis sur le discord du serveur !
          </label>
        </div>
        
        <div class="alert alert-warning mt-2" role="alert">
            <strong> Formulaire en cour de déployement !</strong>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Register</button>


</div>


@endsection