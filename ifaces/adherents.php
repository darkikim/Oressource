<?php
session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

if (is_valid_session()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';
  $url = null;
  $nav = null;
  $info = null;
  if (!isset($_GET['id'])) {
    $urlPost = '../moteur/inscription_post.php';

    $nav = [
      'text' => "Gestion des adherents",
      'links' => [
        ['href' => 'adherents.php', 'text' => 'Inscription', 'state' => 'active'],
        ['href' => 'edition_adherents.php', 'text' => 'Édition']
      ]
    ];

    $info = [
      'type' => 'create',
      'nom' => $_GET['nom'] ?? '',
      'prenom' => $_GET['prenom'] ?? '',
      'date_naissance' => $_GET['date_naissance'] ?? '',
    ];
  } else {
    $urlPost = '../moteur/modification_adherents_post.php';
    $utilisateur = adherents_id($bdd, $_GET['id']);

    $nav = [
      'text' => "Édition du profil utilisateur n°: {$utilisateur['id']} - {$utilisateur['mail']}",
      'links' => [
        ['href' => 'adherents.php', 'text' => 'Inscription'],
        ['href' => 'edition_adherents.php', 'text' => 'Édition', 'state' => 'active']
      ]
    ];
    $info = array_merge($utilisateur, ['type' => 'edit']);
  }
  ?>
  <div class="container">
    <?= configNav($nav); ?>
    <form action="<?= $urlPost ?>" method="post" autocomplete="off">
      <?php if (isset($_GET['id'])) { ?>
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <?= configInfo($info) ?>
        </div>

        <div class="row">
          <div class="col-md-5 col-md-offset-5">
            <?php if (isset($_GET['id'])) { ?>
              <button class="btn btn-warning">Modifier</button>
              <a class="btn btn-default" href="edition_adherents.php">Annuler</a>
            </div>
          <?php } else { ?>
            <button class="btn btn-success">Créer</button>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
