<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/projet/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/projet/Model/Evenement.php';

class EvenementController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function ajouterEvenement($nom, $date, $lieu, $organisateur)
    {
        // Ajouter un événement dans la base de données
        $query = "INSERT INTO evenement (nom_event, date_event, lieu, organisateur) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $date, $lieu, $organisateur]);
    }

    public function getEvenementById($id)
    {
        // Récupérer un événement par son ID
        $query = "SELECT * FROM evenement WHERE id_event = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEvenement($id, $nom, $date, $lieu, $organisateur)
    {
        // Modifier un événement existant
        $query = "UPDATE evenement SET nom_event = ?, date_event = ?, lieu = ?, organisateur = ? WHERE id_event = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $date, $lieu, $organisateur, $id]);
    }

    public function supprimerEvenement($id)
    {
        // Vérifier si l'événement existe
        $evenement = $this->getEvenementById($id);
        if (!$evenement) {
            return false;
        }

        // Supprimer les réservations associées à cet événement
        $this->supprimerReservationsParEvenement($id);

        // Supprimer l'événement
        $sql = "DELETE FROM evenement WHERE id_event = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    function afficherEvenements()
    {
        $sql = "SELECT * FROM evenement ORDER BY date_event DESC";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    function rechercherEvenements($keyword)
    {
        $sql = "SELECT * FROM evenement 
                WHERE nom_event LIKE :keyword 
                OR lieu LIKE :keyword 
                OR organisateur LIKE :keyword";
        try {
            $query = $this->db->prepare($sql);
            $searchTerm = "%$keyword%";
            $query->execute(['keyword' => $searchTerm]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    function getEvenementsAVenir()
    {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM evenement WHERE date_event >= :today ORDER BY date_event ASC";
        try {
            $query = $this->db->prepare($sql);
            $query->execute(['today' => $today]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    function getEvenementsPasses()
    {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM evenement WHERE date_event < :today ORDER BY date_event DESC";
        try {
            $query = $this->db->prepare($sql);
            $query->execute(['today' => $today]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    function trierEvenements($critere = 'date_event', $ordre = 'ASC')
    {
        $criteres_valides = ['date_event', 'nom_event', 'lieu', 'organisateur'];
        $ordres_valides = ['ASC', 'DESC'];
        
        if (!in_array($critere, $criteres_valides)) {
            $critere = 'date_event';
        }
        
        if (!in_array(strtoupper($ordre), $ordres_valides)) {
            $ordre = 'ASC';
        }
        
        $sql = "SELECT * FROM evenement ORDER BY $critere $ordre";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Supprime toutes les réservations associées à un événement donné
     * @param int $eventId ID de l'événement
     * @return bool Succès ou échec de l'opération
     */
    function supprimerReservationsParEvenement($eventId)
    {
        $sql = "DELETE FROM reservation WHERE id_event = :id_event";
        try {
            $query = $this->db->prepare($sql);
            $query->execute(['id_event' => $eventId]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }
}