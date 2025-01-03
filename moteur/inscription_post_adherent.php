<?php

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';

if (is_valid_session() && is_allowed_users()) {
  require_once '../moteur/dbconfig.php';
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_naissance = $_POST['date_naissance'];
  $localisation = $_POST['localisation'];
  $genre = $_POST['genre'];
  $commentaire = $_POST['commentaire']?:'';

  $adherent = new_adherent( $nom, $prenom, $date_naissance, $localisation, $genre, $commentaire );
  adherent_insert($bdd, $adherent);
  header('Location: ../ifaces/adherents.php?msg=Adherent ajouté avec succes!');
} else {
  header('Location:../moteur/destroy.php');
}
