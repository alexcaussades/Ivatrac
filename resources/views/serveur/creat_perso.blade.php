<div class="container">
  <form action="#" method="post">
    @csrf
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Type de jeux :</label>
          <select class="form-control" name="" id="">
            <option>Civil</option>
            <option>Medic / Gendarmerie</option>
            <option>Contreband</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Date de naissance :</label>
          <input type="date" name="dateofnaissance" id="" class="form-control" aria-describedby="helpId">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Profession :</label>
          <input type="text" name="profession" id="" class="form-control" placeholder="Ex: commercant" aria-describedby="helpId" value="{{ old("profession")}}">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Chose à savoir :</label>
          <input type="text" name="savoir" id="" class="form-control" placeholder="Ex: Votre atout ou faiblesse" aria-describedby="helpId" value="{{ old("savoir")}}">
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group mt-3">
        <label for="">Votre personnage : </label>
        <textarea class="form-control" name="description" id="" rows="5" min="180" placeholder="Faite une présentation du backgroud de votre personnage 180 caractères minimum" value="{{ old("description")}}"></textarea>
      </div>
    </div>
    <input type="hidden" name="id_users" value="{{ $users->id }}">
    <input type="hidden" name="name_rp" value="{{ $users->name_rp }}">
    <button type="submit" class="d-flex justify-content-end mt-2 btn btn-primary">Envoyer ma demande</button>
  </form>
</div>

@endsection