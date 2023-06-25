<div class="container mt-2">
    <div class="row">

        <div class="col">
            <h5>name :</h5> {{ $whitelist->name_rp }}
        </div>
        <div class="col">
            <h5>Profession :</h5> {{ $whitelist->Profession }}
            <div>
            </div>

        </div>
        <div class="col">
            <h5> Photo :</h5>
            <img src="https://placehold.co/150x150.png" class="rounded shadow-lg p-1 mb-2 bg-body-tertiary rounded" alt="...">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <h5>Votre description :</h5>
            {{ $whitelist->description }}
        </div>

        <div class="row mt-4">
            <div class="col">
                <h5>Creation le :</h5>
                {{ date('d/m/Y', strtotime($whitelist->created_at)) }}
            </div>
            <div class="col">
                <h5>Modification le :</h5>
                {{ date('d/m/Y', strtotime($whitelist->updated_at)) }}
            </div>
        </div>
        
@endsection