@extends("metar-base")

@section("title", "Add Friends")


@include("navbar")

@section('content')

<div class="container">

<div class="row">
        <div class="col-12 mt-5">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <p class="card-text">
                        <span class="mt-2"> Add Friends ? </span>
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
</div>

@endsection