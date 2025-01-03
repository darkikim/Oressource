<?php

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_users()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $adherents = adherents($bdd);
  $info = [
    'text' => "Gestion des Adhérents",
    'links' => [
      ['href' => 'adherents.php', 'text' => 'Inscription'],
      ['href' => 'edition_adherents.php', 'text' => 'Édition', 'state' => 'active']
    ]
  ];
  ?>

  <div class="container">
    <?= configNav($info) ?>
    <table class="table">
      <thead>
      <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date de naissance</th>
        <th>Adresse</th>
        <th>Genre</th>
        <th>Commentaire</th>
        <th>Éditer</th>
        <th>Supprimer</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($adherents as $u) { ?>
        <tr>
          <td><?= $u['id']; ?></td>
          <td><?= $u['nom']; ?></td>
          <td><?= $u['prenom']; ?></td>
          <td><?= $u['date_naissance']; ?></td>
          <td><?= $u['localisation']; ?></td>
          <td><?= $u['genre']; ?></td>
          <td><?= $u['commentaire']; ?></td>
          <td>
            <a href="adherents.php?id=<?= $u['id']; ?>" class="btn btn-warning btn-sm">Éditer</a>
          </td>
          <td>
            <form action="../moteur/sup_adherents.php" method="post">
              <input type="hidden" name="id" value="<?= $u['id']; ?>">
              <button class="btn btn-danger btn-sm">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
