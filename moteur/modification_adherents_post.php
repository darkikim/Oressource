<?php

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';

if (is_valid_session() && is_allowed_users()) {
  require_once '../moteur/dbconfig.php';
  try {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $localisation = $_POST['localisation'];
    $genre = $_POST['genre'];
    $commentaire = $_POST['commentaire']?:'';

    adherent_update($bdd, new_adherent( $nom, $prenom, $date_naissance, $localisation, $genre, $commentaire ), $_POST['id']);
    header('Location: ../ifaces/edition_adherents.php?msg=Adherent modifié avec succes!');
  } catch (PDOException $e) {
    if ($e->getCode() == '23000') {
      header('Location:../ifaces/adherents.php?err=Une erreur est survenue, code 23000');
      die;
    }
    throw $e;
  }
} else {
  header('Location:../moteur/destroy.php');
}
