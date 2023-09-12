<nav class="navbar bg-primary navbar-expand-lg mt-2 rounded">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Account
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ Route("serveur.index") }}" title="My account the website">Dashboard</a></li>
            <li><a class="dropdown-item" href="{{ Route("serveur.api") }}">API</a></li>
            <li><a class="dropdown-item" href="{{ Route("auth.logout") }}" title="Disconect the website">Logout</a></li>
          </ul>
        </li>
        @endauth
        @guest
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ Route("auth.login")}}">Login</a>
        </li>
        @endguest
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ Route("friends.verify")}}">Online Friends</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="https://webeye.ivao.aero/" target="_blank">Webeye</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="https://www.ivao.aero/" target="_blank">IVAO Website</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="https://sdoaci.skydreamsoft.fr/" target="_blank">SDOACI</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            FPL
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ Route("pirep.index")}}">Pirep</a></li>
            <li><a class="dropdown-item" href="#">View FPL</a></li>
            <li><a class="dropdown-item" href="{{ Route("pirep.create")}}" title="register on the platform only">Creat FPL</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Projet
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://github.com/alexcaussades/L10">GitHub</a></li>
            <li><a class="dropdown-item" href="https://discord.gg/CDntF5H">Discord</a></li>
          </ul>
        </li>
        
      </ul>
    </div>
    <button class="btn btn-success my-2 my-lg-0 rounded">AIRAC 2211</button>
  </div>
</nav>