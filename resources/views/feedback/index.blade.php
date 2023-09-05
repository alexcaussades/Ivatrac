@extends("metar-base")

@section("title", "Feedback")

@include("navbar")

@section('content')


<div class="container">

    <h2 class="mt-2">FeedBack</h2>
    <form action="{{ Route("feedback.post") }}" method="post" class="mt-5 ms-5">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label text-primary">
                        <h5>Categorie</h5>
                    </label>
                    <div class="text-muted"> Several choices possible </div>
                    <select class="form-control" name="labels">
                        <option value="bug">Bug</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="invalid">Invalid</option>
                        <option value="question" selected>Question</option>
                    </select>
                </div>
                <div class="mt-2">
                    <label class="form-label text-primary">
                        <h5>Your remark!</h5>
                    </label>
                    <div class="text-muted"> Please be as precise as possible </div>
                    <textarea class="form-control" name="body" id="" rows="5" minlength="25" maxlength="255"></textarea>
                </div>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="old" value="{{ old('url') }}">
            </div>
        </div>
        <button class="btn btn-dark mt-3 d-flex flex-wrap-reverse" type="submit"> Send feedback </button>

    </form>
    @error("success")
    <div class="alert alert-success mt-2" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>