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
          @for ($i = 0; $i < count($friends); $i++)
              <tr>
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
    
@endif

</div>

@endsection