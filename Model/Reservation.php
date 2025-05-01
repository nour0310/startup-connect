<?php
require_once 'Evenement.php';

class Reservation {
    private ?int $id_reservation = null;
    private int $id_event;
    private string $nom_client;
    private string $email;
    private string $date_reservation;
    private int $nb_places;

    /**
     * Constructeur de la classe Reservation
     * 
     * @param int $id_event ID de l'événement associé
     * @param string $nom_client Nom du client
     * @param string $email Email du client
     * @param string $date_reservation Date de la réservation
     * @param int $nb_places Nombre de places réservées
     * @param int|null $id_reservation ID de la réservation (null pour une nouvelle réservation)
     */
    public function __construct(int $id_event, string $nom_client, string $email, string $date_reservation, int $nb_places, ?int $id_reservation = null) {
        $this->id_reservation = $id_reservation;
        $this->id_event = $id_event;
        $this->nom_client = $nom_client;
        $this->email = $email;
        $this->date_reservation = $date_reservation;
        $this->nb_places = $nb_places;
    }
//GETTERS
    
    public function getId() { return $this->id_reservation; }
    public function getIdEvent() { return $this->id_event; }
    public function getNomClient() { return $this->nom_client; }
    public function getEmail() { return $this->email; }
    public function getDateReservation() { return $this->date_reservation; }
    public function getNbPlaces() { return $this->nb_places; }

    //SETTERS
    public function setIdReservation($id_reservation) { $this->id_reservation = $id_reservation; }
    public function setIdEvent($id_event) { $this->id_event = $id_event; }
    public function setNomClient($nom_client) { $this->nom_client = $nom_client; }
    public function setEmail($email) { $this->email = $email; }
    public function setDateReservation($date_reservation) { $this->date_reservation = $date_reservation; }
    public function setNbPlaces($nb_places) { $this->nb_places = $nb_places; }
}
