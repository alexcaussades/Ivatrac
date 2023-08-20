@extends("metar-base")

@section("title", "edit Friends")


@include("navbar")

@section('content')

<div class="container">

    <form action="{{ Route("friends.edit.post") }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="vid_friend" class="form-label">VID</label>
            <input type="text" class="form-control" id="vid_friend" name="vid_friend" value="{{$friends["vid_friend"]}}" disabled>
        </div>
        <div class="mb-3">
            <label for="name_friend" class="form-label">Name</label>
            <input type="text" class="form-control" id="name_friend" name="name_friend" value="{{$friends["name_friend"]}}">
        </div>
        <input type="hidden" name="id" value="{{$friends["id"]}}">
        <input type="hidden" name="vid_friend" value="{{$friends["vid_friend"]}}">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection