<?php
require_once 'connexion.php';
require_once(__DIR__ . '/Logger.php');
class Investissement {
    private $id, $date_inv, $type_paiement, $contratid, $status_inv, $devise ;
            

    public function __construct($id = null, $date_inv, $type_paiement, $contratid, $status_inv, $devise) {
        $this->id = $id;
        $this->date_inv = $date_inv;
        $this->type_paiement = $type_paiement;
        $this->contratid = $contratid;
        $this->status_inv = $status_inv;
        $this->devise = $devise;

    }

    // Getters
    public function getId() { return $this->id; }
    public function getDateInvestissement() { return $this->date_inv; }
    public function getTypePaiement() { return $this->type_paiement; }
    public function getContratid() { return $this->contratid; }
    public function getStatus_inv() { return $this->status_inv; }
    public function getDevise() { return $this->devise; }
   

    // CRUD methods
    public static function addInvestissement($inv) {
        $sql = "INSERT INTO investissement (id, date_inv, type_paiement, contratid, status_inv,
                devise)
                VALUES (:id, :date_inv, :type_paiement, :contratid, :status_inv,
                :devise)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $inv->getId(),
                'date_inv' => $inv->getDateInvestissement(),
                'type_paiement' => $inv->getTypePaiement(),
                'contratid' => $inv->getContratid(),
                'status_inv' => $inv->getStatus_inv(),
                'devise' => $inv->getDevise()
            ]);
            Logger::log('AJOUT', 'investissement',
             $db->lastInsertId(), 'type: ' . $inv->getTypePaiement() . ', devise: ' . $inv->getDevise());

        } catch (Exception $e) {
            echo "Erreur (ajout) : " . $e->getMessage();
        }
    }

    public static function updateInvestissement($inv) {
        $dbSelect = config::getConnexion();
        $sqlSelect = "SELECT * FROM investissement WHERE id = :id";
        $querySelect = $dbSelect->prepare($sqlSelect);
        $querySelect->execute(['id' => $inv->getId()]);
        $ancien = $querySelect->fetch();

        $sql = "UPDATE investissement SET date_inv = :date_inv, type_paiement = :type_paiement, contratid = :contratid,
                status_inv = :status_inv, devise = :devise 
                WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare(query: $sql);
            $query->execute(params: [
                'id' => $inv->getId(),
                'date_inv' => $inv->getDateInvestissement(),
                'type_paiement' => $inv->getTypePaiement(),
                'contratid' => $inv->getContratid(),
                'status_inv' => $inv->getStatus_inv(),
                'devise' => $inv->getDevise()
            ]);
            Logger::log('MODIFICATION', 'investissement',
            $ancien['id'], 'type: ' . $ancien['type_paiement'] . ', devise: ' . $ancien['devise']);
        } catch (Exception $e) {
            echo "Erreur (modification) : " . $e->getMessage();
        }
    }

    public static function deleteInvestissement($id) {
        $sql = "DELETE FROM investissement WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            Logger::log('SUPPRESSION', 'investissement',$id);
        } catch (Exception $e) {
            echo "Erreur (suppression) : " . $e->getMessage();
        }
    }

    public static function getAllInv() {
        $sql = "SELECT investissement.* , contrat.montant as montant
          FROM investissement , contrat where investissement.contratid = contrat.idcontrat";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur (liste) : " . $e->getMessage();
        }
    }

    public static function getInvById($id) {
        $sql = "SELECT investissement.* , contrat.montant as montant
          FROM investissement , contrat  WHERE  id = :id and investissement.contratid = contrat.idcontrat";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur (recherche) : " . $e->getMessage();
        }
    }

    public static function getInvByIdForPDF($id) {
        $sql = "SELECT 
i.* ,c.* , s.*
FROM investissement i , startup s , contrat c

WHERE c.idcontrat=i.contratid and s.id=c.idstartup and i.id=:id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur (recherche) : " . $e->getMessage();
        }
    }


    public static function searchInv($keyword) {
        $sql = "SELECT  investissement.* , contrat.montant as montant
          FROM investissement , contrat   WHERE investissement.contratid = contrat.idcontrat
           and (type_paiement LIKE :kw 
                   OR status_inv LIKE :kw)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['kw' => '%' . $keyword . '%']);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur (recherche) : " . $e->getMessage();
        }
    }


    public static function changerStatut($id, $statut) {
        $sql = "UPDATE investissement SET status_inv = :statut WHERE id = :id";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'statut' => $statut,
                'id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    

}
