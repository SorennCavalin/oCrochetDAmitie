
    


<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <a class="navbar-brand" href="<?= lien("accueil") ?>">Retour</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Utilisateurs
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lien("user") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("user","ajouter") ?>">Ajouter </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Projets
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lienAdmin("projet") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("projet","ajouter") ?>">Ajouter </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Vidéos
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lienAdmin("video") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("video","ajouter") ?>">Ajouter </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Dons
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lien("don") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("don","ajouter") ?>">Ajouter </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Concours
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lien("concours") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("concours","ajouter") ?>">Ajouter </a>
      </li>
      </ul>
      <button class="form-inline my-2 my-lg-0 btn btn-outline-success my-2 my-sm-0" type="submit" id="search">effectuer une recherche</button>
    </div>
  </div>
</nav>

<!-- <li><hr class="dropdown-divider"></li> -->
<!-- <li><a class="dropdown-item" href="#">Something else here</a></li> -->


<!-- prototype navbar dynamique pas le temps -->
  <!-- < ?php foreach ($tables as $table): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            < ?= $table["TABLE_NAME"] ?>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="< ?= lien($table["TABLE_NAME"]) ?>">Liste</a>
            <a class="dropdown-item" href="< ?= lien($table["TABLE_NAME"],"ajouter") ?>">Ajouter </a>
        </li>
      < ?php endforeach; ?> -->