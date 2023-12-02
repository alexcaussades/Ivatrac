<?php

use Illuminate\Support\Carbon;
?>
@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')

<div class="container">
    <h3 class="mt-2">My Flight Plan</h3>
</div>

<div class="container">
    <div class="row">
        <hr>
        <div class="col-12">
            <table class="table table-striped table-inverse table-responsive mt-5">
                <thead class="thead-inverse">
                    <tr>
                        <th>Date</th>
                        <th>DEP / ARR</th>
                        <th>Callsign</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @for ($i = 0; $i < count($pireps); $i++) <tr>
                        <td scope="row">{{Carbon::parse($pireps[$i]["eobt"])->format("Y-m-d H:m")}}</td>
                        <td>{{$pireps[$i]["departureId"] ?? "N.C"}} / {{$pireps[$i]["arrivalId"] ?? "N.C"}}</td>
                        <td>{{$pireps[$i]["callsign"]}}</td>
                        <td>
                            <form action="{{Route("pirep.show", ["id" => $pireps[$i]["id"]])}}" method="get">
                                <button class="btn btn-dark btn-sm" type="submit">View</button>
                            </form>
                        </td>
                        </tr>
                        @endfor

                </tbody>
            </table>
        </div>
    </div>

</div>



@endsection