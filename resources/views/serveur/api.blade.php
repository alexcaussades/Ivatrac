@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Request Access API Serveur</h4>
            @if($information["role"] != 0 || null)
            <form action="./api/delete" method="post">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"> Delect My Api Keys </button>
            </form>
            @endif
            <hr>
            @if($information["role"] != 0 || null)
            <div class="card-text">
                <div class="row">
                    <div class="col">
                        <div>
                            <h5>Event Time</h5>
                            <p>12-09-2023 15:57:22 (UTC+2:00)</p>
                        </div>
                        <div>
                            <h5>User Name</h5>
                            <p>testeur</p>
                        </div>
                        <div>
                            <h5>Event Name</h5>
                            <p>Request API serveur !</p>
                        </div>
                    </div>
                    <div class="col">
                        <div>
                            <h5>Access Keys</h5>
                            @if($information['visible'] == false)
                                <p><code>{{Str::mask($information["token"], '*', 10)}}</code></p>
                            @else
                               <p><code>{{$information["token"]}}</code></p>
                            @endif
                            
                            
                        </div>
                        <div>
                            <h5>Client ID</h5>
                            @if($information['visible'] == false)
                                <p><code>{{Str::mask($information["client_id"], '*', 10)}}</code></p>
                            @else
                               <p><code>{{$information["client_id"]}}</code></p> 
                            @endif
                        </div>
                        <div>
                            <h5>Source IP adress</h5>
                            <p>127.0.0.1</p>
                        </div>

                    </div>
                    <div class="col">
                        <h5>Region API</h5>
                        <p>European</p>

                        <div>
                            <h5>Error Code </h5>
                            <p>-</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-1-12">
                    <h5>Authorisation</h5>

                    <div class="d-flex justify-content-center">
                        @if($information["role"] == 1)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-danger mx-2">Write</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        <button type="button" class="btn btn-danger mx-2">Update</button>
                        @elseif($information["role"] == 2)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-success mx-2">Write</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        <button type="button" class="btn btn-danger mx-2">Update</button>
                        @elseif($information["role"] == 3)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-success mx-2">Write</button>
                        <button type="button" class="btn btn-success mx-2">Update</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        @elseif($information["role"] == 4)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-success mx-2">Write</button>
                        <button type="button" class="btn btn-success mx-2">Update</button>
                        <button type="button" class="btn btn-success mx-2">Delect</button>
                        @else
                        <button type="button" class="btn btn-danger mx-2">Read</button>
                        <button type="button" class="btn btn-danger mx-2">Write</button>
                        <button type="button" class="btn btn-danger mx-2">Update</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @if($information["role"] == 0)
            <div class="card-text">
                <p> Request My API Keys </p>
                <div class="alert alert-info" role="alert">
                    <strong>Only one key per user can be issued</strong>
                </div>
                <div class="d-flex justify-content-center">
                    <form action="./api" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success"> Create my API keys </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>