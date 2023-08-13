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
                    {{ $error }}
                @endforeach
            </ul>
        </div>
      
    @endif
   
    <form action="#" method="POST">
        @csrf
        <div class="form-group mt-2">
            <label for="name">First Name <span class="text-danger fw-bold ms-2">*</span></label>
            <input type="text" name="name_first" id="name_first" class="form-control" placeholder="Your First Name" aria-describedby="helpId" value="{{ old("name_first") }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="name">Last Name <span class="text-danger fw-bold ms-2">*</span></label>
            <input type="text" name="name_last" id="name_last" class="form-control" placeholder="Your Last Name" aria-describedby="helpId" value="{{ old("name_last") }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="name">VID <span class="text-danger fw-bold ms-2">*</span></label>
            <input type="text" name="vid" id="vid" class="form-control" placeholder="Your Vid (https://ivao.aero/)" aria-describedby="helpId" value="{{ old("vid") }}" required>
        </div>
        
        <div class="form-group mt-2">
            <label for="email">Email <span class="text-danger fw-bold ms-2">*</span></label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" aria-describedby="helpId" value="{{ old("email") }}" required>
            
        </div>
        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="age" id="" value="1">
            J'ai plus de 16 ans <span class="text-danger fw-bold ms-2">*</span>
          </label>
        </div>

        <div class="form-check mt-2">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="condition" id="" value="1">
            J'accepte les conditions d'utilisation <a href="#">ici</a> <span class="text-danger fw-bold ms-2">*</span>
          </label>
        </div>
        
        <div class="alert alert-warning mt-2" role="alert">
            <strong> Form being deployed! </strong>
        </div>
        <div>
            <span class="text-danger fw-bold ms-2">*</span> <span class="text-muted"> input is required form</span>
        </div>
        

        <button type="submit" class="btn btn-primary mt-2">Register</button>


</div>


@endsection