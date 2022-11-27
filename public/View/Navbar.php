<!-- Navbar -->
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
    	 <img src="<?=DOMAIN."/PNG/"?>nav-icon.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-top">
    	 <p><?=APP_NAME?> v<?=APP_VERSION?></p>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 300px;">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" id="nav-link-home" href="<?=DOMAIN."/Home"?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" id="nav-link-clientes" href="<?=DOMAIN."/clientes"?>">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" id="nav-link-veiculos" href="<?=DOMAIN."/veiculos"?>">Veículos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" id="nav-link-funcionarios" href="<?=DOMAIN."/funcionarios"?>">Funcionários</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" id="nav-link-ordens" href="<?=DOMAIN."/ordens"?>">O.S.</a>
        </li>
        <li class="nav-item" style="text-decoration:underline;">
          <a class="nav-link" href="<?=DOMAIN."/login/logout"?>">Sair</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar -->