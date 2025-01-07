<?php

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/validation.php';

header("content-type:application/json");

function get_adherents(PDO $bdd) {
  $sql = 'SELECT id,nom,prenom,date_naissance,localisation FROM adherent';
  $adherents = fetch_all($sql, $bdd);
  if ($adherents){
    for ($i = 0;$i < sizeof($adherents); $i++) {
      preg_match('/\d{5}( [\p{L}\p{P}\p{Zs}]+)?$/u', $adherents[$i]["localisation"], $matches, PREG_OFFSET_CAPTURE);
      if($matches) {
        $adherents[$i]["localisation"] = $matches[0];
      }else {
        $adherents[$i]["localisation"] = 'CP Invalide';
      }
    }
    return $adherents;
   }
  return [];
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
