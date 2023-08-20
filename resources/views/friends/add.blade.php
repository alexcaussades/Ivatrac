@extends("metar-base")

@section("title", "Add Friends")


@include("navbar")

@section('content')

<div class="container">

    <form action="{{ Route("friends.add.post") }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="vid_friend" class="form-label">VID</label>
            <input type="text" class="form-control" id="vid_friend" name="vid_friend">
        </div>
        <div class="mb-3">
            <label for="name_friend" class="form-label">Name</label>
            <input type="text" class="form-control" id="name_friend" name="name_friend">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection