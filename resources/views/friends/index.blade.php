@extends("metar-base")

@section("title", "My Friends")


@include("navbar")

@section('content')


<div class="container mt-5">
    @if (session("success"))
    <div class="alert alert-success" role="alert">
        {{session("success")}}
    </div>
    @endif

    <form action="{{route("friends.add.post.webeye")}}" method="get" class="row g-3 align-items-center">
        @csrf
        <div class="row col-auto">
            <label for="vid_friend" class="form-label">VID <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="vid_friend" name="vid" required>
        </div>
        <div class="row col-auto ms-2 mt-4">
            <button type="submit" class="btn btn-primary">Add Friends</button>
        </div>

    </form>

    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>VID</th>
                <th>Name</th>
                <th>Online</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($friends as $key => $value)
            <tr>
                <td scope="row">{{$value["vid_friend"]}}</td>
                @if ($value["name_friend"] == null)
                <td>Not Found</td>
                @else
                <td>{{$value["name_friend"]}}</td>
                @endif
                @if ($value["session"] == null)
                    <td></td>
                @else
                <td>{{$value["session"]["callsign"]}}</td>
                @endif
                <td class="d-flex inline-flex">
                    <form action="#" method="get">
                        <input type="hidden" name="vid" value="{{$value["vid_friend"]}}">
                        <button type="submit" class="btn btn-danger btn-sm">DELETE</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection