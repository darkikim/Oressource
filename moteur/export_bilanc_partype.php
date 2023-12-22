<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'bi') !== false)) {
  require_once '../moteur/dbconfig.php';
  $id_point_collecte = intval($_GET['numero']);
  $txt1 = $_GET['date1'];
  $date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
  $time_debut = $date1ft->format('Y-m-d');
  $time_debut = $time_debut . ' 00:00:00';

  $txt2 = $_GET['date2'];
  $date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
  $time_fin = $date2ft->format('Y-m-d');
  $time_fin = $time_fin . ' 23:59:59';

  //Premiere ligne = nom des champs (
  // on affiche la periode visée
  if ($_GET['date1'] === $_GET['date2']) {
    $csv_output = ' Le ' . $_GET['date1'] . "\t";
  } else {
    $csv_output = ' Du ' . $_GET['date1'] . ' au ' . $_GET['date2'] . "\t";
  }
  $csv_output .= "\n\r";
  $csv_output .= ($id_point_collecte === 0 ? 'Pour tout les points de collecte' . "\t" : 'Pour le point numero:  ' . $id_point_collecte . "\t");
  $csv_output .= "\n\r";
  $csv_output .= "\n\r";
  $csv_output .= "\n\r";
  $csv_output .= 'type de collecte:' . "\t" . 'masse collecté:' . "\t" . 'nombre de collectes:' . "\t";
  $csv_output .= "\n\r";
  $cond = ($id_point_collecte > 0 ? " AND collectes.id_point_collecte = $id_point_collecte " : ' ');
  $reponse = $bdd->prepare("SELECT
    type_collecte.id,
    type_collecte.nom,
    SUM(pesees_collectes.masse) AS somme,
    pesees_collectes.timestamp,
    COUNT(DISTINCT collectes.id) AS ncol
    FROM type_collecte
    INNER JOIN collectes
    ON type_collecte.id =  collectes.id_type_collecte 
    $cond
    INNER JOIN pesees_collectes
    ON pesees_collectes.id_collecte = collectes.id
    WHERE pesees_collectes.timestamp 
    BETWEEN :du AND :au 
    GROUP BY type_collecte.id
    ");
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

  while ($donnees = $reponse->fetch()) {
    $csv_output .= $donnees['nom'] . "\t" . $donnees['somme'] . "\t" . $donnees['ncol'] . "\t" . "\n";
    $reponse2 = $bdd->prepare("SELECT 
    type_dechets.nom,
    sum(pesees_collectes.masse) AS somme
    FROM type_dechets, pesees_collectes, type_collecte, collectes
    WHERE pesees_collectes.timestamp 
    BETWEEN :du AND :au
    AND type_dechets.id = pesees_collectes.id_type_dechet
    AND type_collecte.id =  collectes.id_type_collecte 
    AND pesees_collectes.id_collecte = collectes.id
    AND type_collecte.id = :id_type_collecte
    $cond
    GROUP BY nom
    ORDER BY somme DESC");
    $reponse2->execute(['du' => $time_debut, 'au' => $time_fin, 'id_type_collecte' => $donnees['id']]);

    $csv_output .= 'objets collectés pour ce type de collecte:' . "\t" . 'masse collecté:' . "\t";
    $csv_output .= "\n\r";

    while ($donnees2 = $reponse2->fetch()) {
      $csv_output .= $donnees2['nom'] . "\t" . $donnees2['somme'] . "\t" . "\n";
    }

    $reponse2->closeCursor();
    $csv_output .= "\n\r";
  }
  $reponse->closeCursor();

  $encoded_csv = mb_convert_encoding($csv_output, 'UTF-16LE', 'UTF-8');
  header('Content-Description: File Transfer');
  header('Content-Type: application/vnd.ms-excel');
  header('Content-type: application/vnd.ms-excel');
  header('Content-disposition: attachment; filename=collectes_par_types_objet_' . date('Ymd') . '.csv');
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  echo chr(255) . chr(254) . $encoded_csv;
  exit;
}
header('Location:../moteur/destroy.php');
