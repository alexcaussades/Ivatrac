@extends("base")

@section("title", "WhiteList du serveur")

@include("navbar")

@section('content')

<div class="container">

    <div class="row">
        <div class="col">
            <h1>WhiteList du Serveur</h1>
        </div>
        <div class="col mt-2">
            <a href="{{ route('auth.register') }}"> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Register
            </button></a>
        </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Avantage du serveur</h3>
            <img class="card-img-top" src="https://wallpapercave.com/wp/wp7644854.jpg" alt="">
            <p class="card-text mt-2">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, dolores. Culpa voluptatem labore, ut earum mollitia soluta neque id iste adipisci sequi modi quo doloribus corporis eligendi, ea laudantium cumque?</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Rejoindre la liste</h3>
            <img class="card-img-top" src="https://wallpapercave.com/wp/wp7644858.png" alt="">
            <p class="card-text mt-2">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Architecto quisquam alias ipsa tempora distinctio natus magni minima ut libero aliquid! Ab dolorem, vitae in tempora quidem temporibus explicabo recusandae numquam.</p>
          </div>
        </div>
      </div>

      <div class="card text-white bg-dark mt-3">
        <div class="card-body">
          <h4 class="card-title">La liste d'attente</h4>
          <p class="card-text mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad porro sit molestias numquam a. Assumenda magnam maiores totam illum? Excepturi, quisquam. Voluptatibus quibusdam, officiis nemo quas obcaecati ea consectetur! Quibusdam.</p>
        </div>
      </div>
    </div>

</div>