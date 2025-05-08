<?php
require_once '../Model/Investissement.php';


if (isset($_POST['ajouterInv'])) {
    $inv = new Investissement(
        $_POST['id'],
        $_POST['date_inv'],
        $_POST['type_paiement'],
         $_POST['contratid'],
        'en attente',
        $_POST['devise'],
    );
    Investissement::addInvestissement($inv);
    header(header: 'Location: ../View/FrontOffice/ListInvestissementFront.php');
}

if (isset($_POST['modifierInv'])) {
    $inv = new Investissement(
        $_POST['id'],
        $_POST['date_inv'],
        $_POST['type_paiement'],
        $_POST['contratid'],
        $_POST['status_inv'],
        $_POST['devise'],
    );
    Investissement::updateInvestissement($inv);
    header(header: 'Location: ../View/FrontOffice/ListInvestissementFront.php');
}

if (isset($_GET['supprimer'])) {
    Investissement::deleteInvestissement($_GET['supprimer']);
    header(header: 'Location: ../View/FrontOffice/ListInvestissementFront.php');
}

if (isset($_GET['supprimerBack'])) {
    Investissement::deleteInvestissement($_GET['supprimerBack']);
    header(header: 'Location: ../View/BackOffice/ListInvestissementBack.php');
}
if (isset($_POST['annuler'])) {
    header(header: 'Location: ../View/FrontOffice/ListInvestissementFront.php');
}

if (isset($_POST['annulerAjout'])) {
    header(header: 'Location: ../View/FrontOffice/ListInvestissementFront.php');
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $inv = Investissement::searchInv(keyword: $_GET['search']);
} else {
    $inv = Investissement::getAllInv(); // méthode classique
}

if (isset($_GET['changerStatut'], $_GET['statut'])) {
    $id = $_GET['changerStatut'];
    $statut = $_GET['statut'];
    Investissement::changerStatut($id, $statut);
    header('Location: ../View/backoffice/ListInvestissementBack.php');
    exit;
}




include '../View/FrontOffice/ListInvestissementFront.php';
