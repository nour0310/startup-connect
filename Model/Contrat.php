<?php
require_once 'connexion.php';

class Contrat {
    private $idcontrat, $idutilisateur, $idstartup, $typecontrat, $datecontrat, $dureecontrat,
            $clauseSortie, $pourcentageCaptiale, $valeurStartup, $conditionsSpecifique, $statusContrat, $montant;

    public function __construct($idutilisateur, $idstartup, $typecontrat, $datecontrat, $dureecontrat,
                                $clauseSortie, $pourcentageCaptiale, $valeurStartup, $conditionsSpecifique, $statusContrat, $montant, $idcontrat = null) {
        $this->idcontrat = $idcontrat;
        $this->idutilisateur = $idutilisateur;
        $this->idstartup = $idstartup;
        $this->typecontrat = $typecontrat;
        $this->datecontrat = $datecontrat;
        $this->dureecontrat = $dureecontrat;
        $this->clauseSortie = $clauseSortie;
        $this->pourcentageCaptiale = $pourcentageCaptiale;
        $this->valeurStartup = $valeurStartup;
        $this->conditionsSpecifique = $conditionsSpecifique;
        $this->statusContrat = $statusContrat;
        $this->montant = $montant;
    }

    // Getters
    public function getId() { return $this->idcontrat; }
    public function getIdUtilisateur() { return $this->idutilisateur; }
    public function getIdStartup() { return $this->idstartup; }
    public function getTypeContrat() { return $this->typecontrat; }
    public function getDateContrat() { return $this->datecontrat; }
    public function getDureeContrat() { return $this->dureecontrat; }
    public function getClauseSortie() { return $this->clauseSortie; }
    public function getPourcentageCaptiale() { return $this->pourcentageCaptiale; }
    public function getValeurStartup() { return $this->valeurStartup; }
    public function getConditionsSpecifique() { return $this->conditionsSpecifique; }
    public function getStatusContrat() { return $this->statusContrat; }
    public function getmontant() { return $this->montant; }

    // CRUD methods
    public static function addContrat($contrat) {
        $sql = "INSERT INTO contrat (idutilisateur, idstartup, typecontrat, datecontrat, dureecontrat,
                clauseSortie, pourcentageCaptiale, valeurStartup, conditionsSpecifique, statusContrat, montant)
                VALUES (:idutilisateur, :idstartup, :typecontrat, :datecontrat, :dureecontrat,
                :clauseSortie, :pourcentageCaptiale, :valeurStartup, :conditionsSpecifique, :statusContrat, :montant)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'idutilisateur' => $contrat->getIdUtilisateur(),
                'idstartup' => $contrat->getIdStartup(),
                'typecontrat' => $contrat->getTypeContrat(),
                'datecontrat' => $contrat->getDateContrat(),
                'dureecontrat' => $contrat->getDureeContrat(),
                'clauseSortie' => $contrat->getClauseSortie(),
                'pourcentageCaptiale' => $contrat->getPourcentageCaptiale(),
                'valeurStartup' => $contrat->getValeurStartup(),
                'conditionsSpecifique' => $contrat->getConditionsSpecifique(),
                'statusContrat' => $contrat->getStatusContrat(),
                'montant' => $contrat->getmontant()
            ]);
        } catch (Exception $e) {
            echo "Erreur (ajout) : " . $e->getMessage();
        }
    }

    public static function updateContrat($contrat) {
        $sql = "UPDATE contrat SET idutilisateur = :idutilisateur, idstartup = :idstartup, typecontrat = :typecontrat,
                datecontrat = :datecontrat, dureecontrat = :dureecontrat, clauseSortie = :clauseSortie,
                pourcentageCaptiale = :pourcentageCaptiale, valeurStartup = :valeurStartup,
                conditionsSpecifique = :conditionsSpecifique, statusContrat = :statusContrat, montant = :montant
                WHERE idcontrat = :idcontrat";
        $db = config::getConnexion();
        try {
            $query = $db->prepare(query: $sql);
            $query->execute(params: [
                'idutilisateur' => $contrat->getIdUtilisateur(),
                'idstartup' => $contrat->getIdStartup(),
                'typecontrat' => $contrat->getTypeContrat(),
                'datecontrat' => $contrat->getDateContrat(),
                'dureecontrat' => $contrat->getDureeContrat(),
                'clauseSortie' => $contrat->getClauseSortie(),
                'pourcentageCaptiale' => $contrat->getPourcentageCaptiale(),
                'valeurStartup' => $contrat->getValeurStartup(),
                'conditionsSpecifique' => $contrat->getConditionsSpecifique(),
                'statusContrat' => $contrat->getStatusContrat(),
                'idcontrat' => $contrat->getId(),
                'montant' => $contrat->getmontant()
            ]);
        } catch (Exception $e) {
            echo "Erreur (modification) : " . $e->getMessage();
        }
    }

    public static function deleteContrat($id) {
        $sql = "DELETE FROM contrat WHERE idcontrat = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Erreur (suppression) : " . $e->getMessage();
        }
    }

    public static function getAllContrats() {
        $sql = "SELECT contrat.* , startup.nom as nomStartup FROM contrat , 
        startup WHERE contrat.idstartup = startup.id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur (liste) : " . $e->getMessage();
        }
    }

    public static function getContratById($id) {
        $sql = "SELECT contrat.* , startup.nom as nomStartup FROM contrat , 
        startup WHERE contrat.idstartup = startup.id and idcontrat = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur (recherche) : " . $e->getMessage();
        }
    }


    public static function searchContrats($keyword) {
        $sql = "SELECT contrat.* , startup.nom as nomStartup FROM contrat , 
        startup WHERE contrat.idstartup = startup.id
                AND typecontrat LIKE :kw 
                   OR statusContrat LIKE :kw";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['kw' => '%' . $keyword . '%']);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur (recherche) : " . $e->getMessage();
        }
    }


    public static function changerStatut($idcontrat, $statut) {
        $sql = "UPDATE contrat SET statusContrat = :statut WHERE idcontrat = :id";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'statut' => $statut,
                'id' => $idcontrat
            ]);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    

}
