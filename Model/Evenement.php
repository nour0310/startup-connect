<?php

class Evenement {
    private ?int $id_event = null;
    private string $nom_event;
    private string $date_event;
    private ?string $lieu;
    private ?string $organisateur;

    public function __construct($nom_event, $date_event, $lieu = null, $organisateur = null, $id_event = null) {
        $this->id_event = $id_event;
        $this->nom_event = $nom_event;
        $this->date_event = $date_event;
        $this->lieu = $lieu;
        $this->organisateur = $organisateur;
    }

    
    public function getId() { return $this->id_event; }
    public function getNomEvent() { return $this->nom_event; }
    public function getDateEvent() { return $this->date_event; }
    public function getLieu() { return $this->lieu; }
    public function getOrganisateur() { return $this->organisateur; }

  
    public function setIdEvent($id_event) { $this->id_event = $id_event; }
    public function setNomEvent($nom_event) { $this->nom_event = $nom_event; }
    public function setDateEvent($date_event) { $this->date_event = $date_event; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setOrganisateur($organisateur) { $this->organisateur = $organisateur; }
}
