@extends("metar-base")

@section("title", "Bookings")


@include("navbar")

@section('content')

<div class="container">
    <div class="row">

    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8 mt-5">
            <h1 class="text-center">Bookings Service ATC</h1>
            <div class="alert alert-info" role="alert">
                <span><strong>This service</strong>  being deployed</span>
            </div>
            <table class="table table-striped table-inverse table-responsive mt-4">
                <thead class="thead-inverse">
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Voice</th>
                        <th>Training</th>
                        <th>Airport</th>
                        <th>VID</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($bookings as $book )
                    <tr>
                        <td scope="row">{{$book["Start_time"]}}</td>
                        <td>{{$book["End_time"]}}</td>
                        @if ($book["voice"] == true)
                        <td> x </td>
                        @else
                        <td></td>
                        @endif
                        @if ($book["training"] == true)
                        <td class="fw-bold"> x </td>
                        @else
                        <td></td>
                        @endif
                        <td>{{$book["airport"] ?? "NC"}}</td>
                        <td>{{$book["user"]["vid"]}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-2 mt-5">
            <button class="btn btn-success disabled"> Register Session</button>
        </div>
    </div>

</div>

@endsection