<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="navbar-brand"><a class="nav-link active" aria-current="page" href={{ Route("auth.login") }}>IVATRAC</a></div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href={{ Route("home") }}>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ Route("metars.index") }}">Metar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ Route("pirep.index") }}">Pirep</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://sdoaci.skydreamsoft.fr/" target="_blank">Sdoaci</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            IVAO
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://webeye.ivao.aero/" target="_blank">Webeye</a></li>
            <li><a class="dropdown-item" href="https://www.ivao.aero/" target="_blank">IVAO Website</a></li>
          </ul>
        </li>
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Account
          </a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ Route("login") }}">My Account</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{ Route("logout") }}">Logout</a></li>
          </ul>
        </li>
        @endauth
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Friends
          </a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ Route("friends.verify") }}">Online Friend </a></li>
            <li><a class="dropdown-item" href="{{ Route("friends.all") }}">Check Friend </a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{ Route("friends.add") }}">Add Friend </a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ Route("serveur.api") }}">API</a>
        </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>