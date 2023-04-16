<div class="container">
  <form action="#" method="post">
    @csrf
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Type de jeux:</label>
          <select class="form-control" name="" id="">
            <option>Civil</option>
            <option>Medic / Gendarmerie</option>
            <option>Contreband</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Date de naissance</label>
          <input type="date" name="date" id="" class="form-control" placeholder="" aria-describedby="helpId">
          <small id="helpId" class="text-muted">Help text</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Profession</label>
          <input type="text" name="" id="" class="form-control" placeholder="" aria-describedby="helpId">
          <small id="helpId" class="text-muted">Help text</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Chose à savoir</label>
          <input type="text" name="text" id="" class="form-control" placeholder="" aria-describedby="helpId">
          <small id="helpId" class="text-muted">Help text</small>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="">Description de votre personnage ! </label>
        <textarea class="form-control" name="" id="" rows="5"></textarea>
      </div>
    </div>
    <button type="submit" class="mt-2 btn btn-primary">Submit</button>
  </form>
</div>