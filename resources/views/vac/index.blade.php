@extends("metar-base")

@section("title", "Chart Vac")


@include("navbar")

@section('content')

<div class="container">
    <p class="fs-1">VAC / ULM </p>
    <form action="{{ route("vac.icao", ["icao"])}}" method="get">
        <div class="form-group">
            <label for="icao2">ICAO</label>
            <input type="text" class="form-control" id="searchicao" name="icao" placeholder="Enter ICAO">
            <div class="form-text" id="basic-addon4"></div>
            <button type="submit" id="submit-btn" class="btn btn-dark mt-2">Submit</button>
        </div>

    </form>


    <div id="status">
        <hr>
        <p class="fs-3"> Check statut </p>
    </div>

</div>



@endsection

<script>
    //documment ready change to innerHTML
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById("status").style.display = "none";
        document.getElementById("submit-btn").className = "btn btn-dark mt-2";
        document.getElementById("basic-addon4").innerHTML = "We are searching for the VAC of the aerodrome or ULM.";
        document.getElementById("searchicao").addEventListener("input", function(event) {
            document.getElementById("basic-addon4").innerHTML = "We are searching for the VAC of the aerodrome or ULM.";
            if (event.target.value.length == 4) {
                var searchicao = document.getElementById("searchicao").value;
                document.getElementById("basic-addon4").innerHTML = "We are searching for the VAC of the aerodrome";
                document.getElementById("submit-btn").className = "btn btn-success mt-2";
                document.getElementById("submit-btn").disabled = false;
            }
            if (event.target.value.length == 6) {
                var searchicao = document.getElementById("searchicao").value;
                document.getElementById("basic-addon4").innerHTML = "We are searching for the VAC of the ULM";
                document.getElementById("submit-btn").className = "btn btn-success mt-2";


            } else {
                
            }
        });
    });
</script>