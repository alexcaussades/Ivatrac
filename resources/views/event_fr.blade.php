
@if ($event_fr != null)
    <h5 class="fw-bold">IVAO Event FR</h5>
        
        <div class="row">
            @foreach($event_fr as $events)
                    @if (isset($events[0]["type"]) == "training" || isset($events[0]["type"]) == "exam")
                        <div class="col-6 mt-2">
                        <div class="card text-white bg-dark">
                            <div class="card-body">
                                @if ($events[0]["type"] == "training")
                                    @if (ENV('APP_ENV') == 'local')
                                    <img class="card-img-top" src="{{ asset("asset/img/training/training.png") }}" alt="">
                                    @else
                                    <img class="card-img-top" src="{{ asset("public/asset/img/training/training.png") }}" alt="">
                                    @endif  
                                @endif
                                @if ($events[0]["type"] == "exam")
                                    @if (ENV('APP_ENV') == 'local')
                                    <img class="card-img-top" src="{{ asset("asset/img/exam/exam.png") }}" alt="">
                                    @else
                                    <img class="card-img-top" src="{{ asset("public/asset/img/exam/exam.png") }}" alt="">
                                    @endif  
                                @endif
                                <p class="card-text text-center text-info mt-2">{{$events[0]["name"]}}</p>
                                <p class="card-text text-center ">{{$events[0]["description"]}}</p>
                                <p class="card-text text-center "><button class="btn btn-outline-success">{{Carbon::parse($events[0]["started_at"])->format('d-m H:i') }}Z</button> </p>
                                </div>
                            </div>
                        </div>
                    @endif
            @endforeach
        </div>
    </div>
    <hr>
@endif
