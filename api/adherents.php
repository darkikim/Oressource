<?php

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/validation.php';

header("content-type:application/json");

function get_adherents(PDO $bdd) {
  $sql = 'SELECT id,nom,prenom FROM adherent';
  return fetch_all($sql, $bdd);
}

if (is_valid_session()) {
  require_once('../moteur/dbconfig.php');

  http_response_code(200); // Sucess.
  echo(json_encode(['success' => get_adherents($bdd)]));

}else {
  http_response_code(401); // Unauthorized.
  echo (json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
  die();
}
