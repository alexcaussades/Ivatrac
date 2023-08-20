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

    <form action="{{route("friends.add.post")}}" method="post" class="row g-3 align-items-center">
        @csrf
        <div class="row col-auto">
            <label for="vid_friend" class="form-label">VID <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="vid_friend" name="vid_friend" required>
        </div>
        <div class="row col-auto ms-2">
            <label for="name_friend" class="form-label">Name</label>
            <input type="text" class="form-control" id="name_friend" name="name_friend">
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
                <td class="d-flex inline-flex">
                    <form action="{{route("friends.destroy", $value["id"])}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <form action="{{route("friends.edit")}}" method="get" class="ms-2">
                        <input type="hidden" name="id" value="{{$value["id"]}}">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection