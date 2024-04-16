<div class="container mt-2">
    <h5 class="fw-bold">IVAO Event World</h5>
    <div class="row">
        @foreach($event_worl as $eventss)
        <div class="col-6 mt-2">
            <div class="card text-white bg-dark">
                <div class="card-body">
                   <img src="{{$eventss['imageUrl']}}" style="width: 100%; height: 80%;" alt="" srcset="">
                    <h5 class="card-text text-center text-info mt-2">{{$eventss["title"]}} / {{$eventss["airports"][0]}} </h5>
                    <div class=" d-flex  justify-content-center">
                        @for ($i = 0 ; $i < count($eventss["divisions"]) ; $i++ )
                            <span class="badge bg-secondary fs-6 ms-2"> {{$eventss["divisions"][$i]}}</span>
                        @endfor
                    </div>
                    <p class="card-text text-center ">{{$eventss["description"]}}</p>
                    <a href="{{$eventss["infoUrl"]}}" class="float-end"><button class="btn btn-outline-primary">More Information</button></a>                        
                </div>
            </div>
        </div>
        @endforeach
    </div>
