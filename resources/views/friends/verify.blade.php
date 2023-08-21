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
                <th scope="col">Information</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < count($friends); $i++) <tr>
                <td>{{$friends[$i]["name"]}}</td>
                <td>{{$friends[$i]["VID"]}}</td>
                <td>{{$friends[$i]["callsign"]}}</td>
                <td>{{$friends[$i]["time"]}}</td>
                <td>{{$friends[$i]["info"]}}</td>
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
                    <form action="{{ Route("friends.add.post") }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="vid_friend" class="form-label">VID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vid_friend" name="vid_friend">
                        </div>
                        <div class="mb-3">
                            <label for="name_friend" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_friend" name="name_friend">
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