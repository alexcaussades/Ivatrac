@extends("base")

@section("title", "Serveur - Dashboard ")

@include("navbar")

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Request Access API Serveur</h4>
            @if($authorisation != 0 || null)
            <button type="button" class="btn btn-danger btn-sm">Delect My Api Keys</button>
            @endif
            <hr>
            @if($authorisation != 0 || null)
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
                            <p><code>dkqsjdlizdjqlzidqlzidjqizjdlqzidjiqzdjlqzidjlqizjdlqizdljqzldijqlzd</code></p>
                        </div>
                        <div>
                            <h5>Client ID</h5>
                            <p><code>dmlkqsdozkdozkdmqlzkdoqkzdmqzkdmqozkd</code></p>
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
                        @if($authorisation == 1)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-danger mx-2">Write</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        <button type="button" class="btn btn-danger mx-2">Update</button>
                        @elseif($authorisation == 2)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-success mx-2">Write</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        <button type="button" class="btn btn-danger mx-2">Update</button>
                        @elseif($authorisation == 3)
                        <button type="button" class="btn btn-success mx-2">Read</button>
                        <button type="button" class="btn btn-success mx-2">Write</button>
                        <button type="button" class="btn btn-success mx-2">Update</button>
                        <button type="button" class="btn btn-danger mx-2">Delect</button>
                        @elseif($authorisation == 4)
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
            @if($authorisation == 0)
            <div class="card-text">
                <p> Request My API Keys </p>
                <div class="alert alert-info" role="alert">
                    <strong>Only one key per user can be issued</strong>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-success"> Create my API keys </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>