@extends("metar-base")

@section("title", "Metar")

@include("navbar")

@section('content')


<div class="container">
    <div class="card mt-5 border-info shadow p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <p class="card-text">
            <div class="d-flex justify-content-center">
                <div class="mx-auto p-5 p-5 align-content-end flex-wrap">
                    <h1 class="display-1 mt-8">404</h1>
                    <h2 class="display-4">Page not found</h2>
                    <p class="lead">The page you are looking for was not found.</p>
                    <a href="{{ Route('home') }}" class="btn btn-primary">Go back to home</a> <a href="{{ Route('feedback.index') }}" class="btn btn-dark ms-2">Feedback</a>
                </div>
            </div>
            </p>
        </div>
    </div>
</div>