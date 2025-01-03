<?php

session_start();
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'l') !== false)) {
  require_once '../moteur/dbconfig.php';
  try {
    $req = $bdd->prepare('DELETE FROM adherent WHERE id = :id');
    $req->execute(['id' => $_POST['id']]);
    $req->closeCursor();
    header('Location:../ifaces/edition_adherents.php?msg=Adherent definitivement supprimé.');
  } catch (PDOException $e) {
    header('Location:../ifaces/edition_adherents.php?err=Adherent non supprimable.');
  }
} else {
  header('Location:../moteur/destroy.php');
}
