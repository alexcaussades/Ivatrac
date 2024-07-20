<?php
use Illuminate\Support\Carbon;
?>
@extends("metar-base")

@section("title", "My Friends")


@include("navbar")

@section('content')


<div class="container mt-5">

    @if ($friends)
    <h1>My Friends Online</h1>
    <table class="table table-striped table-inverse table-responsive mt-5">
        <thead class="thead-inverse">
            <tr>
                <th scope="col">Friend</th>
                <th scope="col">VID</th>
                <th scope="col">Callsing</th>
                <th scope="col">Time Online</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < count($friends); $i++) <tr>
                <td>{{$friends[$i]["friend"]["firstName"]}} {{$friends[$i]["friend"]["lastName"]}}</td>
                <td>
                    <form action="{{Route("vid",[$friends[$i]["friendId"]])}}" method="get">
                        <button class="btn btn-dark btn-sm" type="submit">{{$friends[$i]["friendId"]}}</button>
                    </form>
                </td>
                <td>{{$friends[$i]["sessions"][0]["callsign"]}}</td>
                <td>{{Carbon::parse($friends[$i]["sessions"][0]["time"])->format('H:i')}}</td>
                </tr>
                @endfor
        </tbody>
    </table>

    @else
    <div class="row">
        <div class="col-12">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h4 class="card-title text-center">No Friend online </h4>
                    <p class="card-text">
                        <span class="mt-2"> Add Friends ? </span>
                        <!-- <img class="youtube" alt=""><img class="twitch ms-2" alt=""><img class="tiktok ms-2" alt=""> -->
                    <form action="{{ Route("friends.add.post.webeye") }}" method="get">
                        @csrf
                        <div class="mb-3">
                            <label for="vid_friend" class="form-label">VID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vid" name="vid">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


@if (ENV('APP_ENV') == 'local')
<script src="{{ asset("asset/js/update_friend.js") }}"></script>
@else
<script src="{{ asset("public/asset/js/update_friend.js") }}"></script>
@endif


@endsection