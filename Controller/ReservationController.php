<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/projet/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/projet/Model/Reservation.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/projet/Model/Evenement.php';

class ReservationController
{
   
    function ajouterReservation($reservation)
    {
        $sql = "INSERT INTO reservation (id_event, nom_client, email, date_reservation, nb_places) 
                VALUES (:id_event, :nom_client, :email, :date_reservation, :nb_places)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_event' => $reservation->getIdEvent(),
                'nom_client' => $reservation->getNomClient(),
                'email' => $reservation->getEmail(),
                'date_reservation' => $reservation->getDateReservation(),
                'nb_places' => $reservation->getNbPlaces()
            ]);
            return $db->lastInsertId();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

   
    function addReservation($reservationData)
    {
        $sql = "INSERT INTO reservation (id_event, nom_client, email, date_reservation, nb_places) 
                VALUES (:id_event, :nom_client, :email, :date_reservation, :nb_places)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_event' => $reservationData['id_event'],
                'nom_client' => $reservationData['nom_client'],
                'email' => $reservationData['email'],
                'date_reservation' => $reservationData['date_reservation'],
                'nb_places' => $reservationData['nb_places']
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }


    function getReservationById($id)
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event, e.lieu, e.organisateur 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                WHERE r.id_reservation = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return null;
        }
    }

    
    function supprimerReservation($id)
    {
        
        $reservation = $this->getReservationById($id);
        if (!$reservation) {
            return false;
        }

        $sql = "DELETE FROM reservation WHERE id_reservation = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

  
    function afficherReservations()
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event, e.lieu, e.organisateur 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                ORDER BY r.date_reservation DESC";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

   
    function modifierReservation($reservation)
    {
        $sql = "UPDATE reservation SET 
                id_event = :id_event, 
                nom_client = :nom_client, 
                email = :email, 
                nb_places = :nb_places 
                WHERE id_reservation = :id_reservation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_event' => $reservation->getIdEvent(),
                'nom_client' => $reservation->getNomClient(),
                'email' => $reservation->getEmail(),
                'nb_places' => $reservation->getNbPlaces(),
                'id_reservation' => $reservation->getId()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

   
    function getReservationsParEvenement($eventId)
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                WHERE r.id_event = :eventId 
                ORDER BY r.date_reservation DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['eventId' => $eventId]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

   
    function getReservationsParEmail($email)
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event, e.lieu, e.organisateur 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                WHERE r.email = :email 
                ORDER BY r.date_reservation DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['email' => $email]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

   
    function getTotalPlacesReservees($eventId)
    {
        $sql = "SELECT SUM(nb_places) as total FROM reservation 
                WHERE id_event = :eventId";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['eventId' => $eventId]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?: 0;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return 0;
        }
    }

    
    function rechercherReservations($keyword)
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                WHERE r.nom_client LIKE :keyword 
                OR r.email LIKE :keyword 
                OR e.nom_event LIKE :keyword";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $searchTerm = "%$keyword%";
            $query->execute(['keyword' => $searchTerm]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    
    function getTotalReservations()
    {
        $sql = "SELECT COUNT(*) as total FROM reservation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return 0;
        }
    }

   
    function getReservationsParPage($depart, $reservationsParPage)
    {
        $sql = "SELECT r.*, e.nom_event, e.date_event 
                FROM reservation r 
                INNER JOIN evenement e ON r.id_event = e.id_event 
                ORDER BY r.date_reservation DESC 
                LIMIT :depart, :nombre";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':depart', $depart, PDO::PARAM_INT);
            $query->bindValue(':nombre', $reservationsParPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

    //STAT
    function getStatistiquesReservationsParEvenement()
    {
        $sql = "SELECT e.id_event, e.nom_event, COUNT(r.id_reservation) as nb_reservations, 
                SUM(r.nb_places) as total_places 
                FROM evenement e 
                LEFT JOIN reservation r ON e.id_event = r.id_event 
                GROUP BY e.id_event 
                ORDER BY nb_reservations DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return [];
        }
    }

   
    function verifierReservationExistante($email, $eventId)
    {
        $sql = "SELECT COUNT(*) as nb FROM reservation 
                WHERE email = :email AND id_event = :eventId";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'email' => $email,
                'eventId' => $eventId
            ]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['nb'] > 0;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }
} 