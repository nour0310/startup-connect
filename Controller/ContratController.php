<?php
require_once '../Model/Contrat.php';

if (isset($_POST['ajouter'])) {
    $contrat = new Contrat(
        $_POST['idutilisateur'],
        $_POST['idstartup'],
        $_POST['typecontrat'],
        $_POST['datecontrat'],
        $_POST['dureecontrat'],
        $_POST['clauseSortie'],
        $_POST['pourcentageCaptiale'],
        $_POST['valeurStartup'],
        $_POST['conditionsSpecifique'],
        'en attente',
        $_POST['montant'],
    );
    Contrat::addContrat($contrat);
    header(header: 'Location: ../View/FrontOffice/ListContratFront.php');
}

if (isset($_POST['modifier'])) {
    $contrat = new Contrat(
        $_POST['idutilisateur'],
        $_POST['idstartup'],
        $_POST['typecontrat'],
        $_POST['datecontrat'],
        $_POST['dureecontrat'],
        $_POST['clauseSortie'],
        $_POST['pourcentageCaptiale'],
        $_POST['valeurStartup'],
        $_POST['conditionsSpecifique'],
        $_POST['statusContrat'],
        $_POST['montant'],
        $_POST['idcontrat']
    );
    Contrat::updateContrat($contrat);
    header(header: 'Location: ../View/FrontOffice/ListContratFront.php');
}

if (isset($_GET['supprimer'])) {
    Contrat::deleteContrat($_GET['supprimer']);
    header(header: 'Location: ../View/FrontOffice/ListContratFront.php');
}

if (isset($_POST['annuler'])) {
    header(header: 'Location: ../View/FrontOffice/ListContratFront.php');
}

if (isset($_POST['annulerAjout'])) {
    header(header: 'Location: ../View/FrontOffice/startupDetail.html');
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $contrats = Contrat::searchContrats(keyword: $_GET['search']);
} else {
    $contrats = Contrat::getAllContrats(); // méthode classique
}

if (isset($_GET['changerStatut'], $_GET['statut'])) {
    $id = $_GET['changerStatut'];
    $statut = $_GET['statut'];
   contrat::changerStatut($id, $statut);
    header('Location: ../View/backoffice/ListContratBack.php');
    exit;
}




include '../View/FrontOffice/ListContratFront.php';