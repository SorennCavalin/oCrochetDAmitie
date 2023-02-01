<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= lien("accueil") ?>">Retour</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
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
          <a class="dropdown-item" href="<?= lien("projet") ?>">Liste</a>
          <a class="dropdown-item" href="<?= lien("projet","ajouter") ?>">Ajouter </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Vidéos
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?= lien("video") ?>">Liste</a>
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
      <li class="nav-item">
        <a class="nav-link" href="<?= lienAdmin("accueil") ?>">Statistiques</a>
      </li>
    </ul>
      <button class="form-inline my-2 my-lg-0 btn btn-outline-success my-2 my-sm-0" type="submit" id="search">effectuer un recherche</button>
  </div>
</nav>