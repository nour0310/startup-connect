<?php
require_once '../../Model/Investissement.php';

$id = $_GET['id'] ?? null;
$fiche = Investissement::getInvByIdForPDF($id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Fiche Contrat & Investissement</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .fiche-container {
      max-width: 800px;
      margin: auto;
      border: 1px solid #ccc;
      padding: 30px;
      margin-top: 30px;
      background-color: #fdfdfd;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .row {
      margin-bottom: 10px;
    }
    .label {
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="fiche-container">
  <h2>Fiche Contrat & Investissement</h2>

  <div class="row">
    <div class="col-md-6"><span class="label">Date Investissement :</span> <?= $fiche['date_inv'] ?></div>
    <div class="col-md-6"><span class="label">Type Paiement :</span> <?= $fiche['type_paiement'] ?></div>
  </div>

  <div class="row">
    <div class="col-md-6"><span class="label">Montant :</span> <?= $fiche['montant'] . ' ' . $fiche['devise'] ?></div>
    <div class="col-md-6"><span class="label">Statut :</span> <?= $fiche['status_inv'] ?></div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-6"><span class="label">Type Contrat :</span> <?= $fiche['typecontrat'] ?></div>
    <div class="col-md-6"><span class="label">Durée :</span> <?= $fiche['dureecontrat'] ?> mois</div>
  </div>

  <div class="row">
    <div class="col-md-6"><span class="label">Valeur Startup :</span> <?= $fiche['valeurStartup'] ?></div>
    <div class="col-md-6"><span class="label">Clause Sortie :</span> <?= $fiche['clauseSortie'] ?></div>
  </div>

  <div class="row">
    <div class="col-md-12"><span class="label">Conditions :</span> <?= $fiche['conditionsSpecifique'] ?></div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-6"><span class="label">Nom Startup :</span> <?= $fiche['nom'] ?></div>
    <div class="col-md-6"><span class="label">Statut Contrat :</span> <?= $fiche['statusContrat'] ?></div>
  </div>

  <div class="row">
    <div class="col-md-12"><span class="label">Description :</span><br><?= $fiche['description'] ?></div>
  </div>
</div>

</body>
</html>