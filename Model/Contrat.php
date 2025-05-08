<?php
require_once 'connexion.php';
require_once(__DIR__ . '/Logger.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/../lib/PHPMailer/src/Exception.php');
require_once(__DIR__ . '/../lib/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/../lib/PHPMailer/src/SMTP.php');

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

    private static function sendEmailConfirmation($nom) {
        // Étape 3 : Création de l'instance PHPMailer
        $mail = new PHPMailer(true);

        try {
    // Paramètres du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'manelallagui7@gmail.com'; // Ton email
    $mail->Password = 'bdno pnwg ayzt fmxt'; // Ton mot de passe ou mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // Port pour TLSs

    // Destinataires
    $mail->setFrom('manelallagui7@gmail.com', 'Manel ALLAGUI');
    $mail->addAddress('manelallagui7@gmail.com', 'Manel ALLAGUI'); // Ajouter un destinataire

    // Contenu de l'email
    $mail->isHTML(true); // Email en HTML
            $mail->Subject = 'Confirmation de Contrat';
            $mail->Body    = "<h1>Bonjour, $nom!</h1><p>Votre contrat a été créé avec succès.</p>";
            $mail->AltBody = "Bonjour, $nom! Votre contrat a été créé avec succès.";

            // Étape 4 : Envoi de l'email
            if($mail->send()) {
                echo 'L\'email a été envoyé avec succès.';
            } else {
                echo 'Échec de l\'envoi de l\'email.';
            }

        } catch (Exception $e) {
            // Gestion des erreurs
            echo "L'email n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }
    }
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
           
            Logger::log('AJOUT', 'contrat'
            , $db->lastInsertId(), 'type: ' . $contrat->getTypeContrat() . ', montant: ' . $contrat->getMontant());
           
            self::sendEmailConfirmation('Manel Allagui');
            
        } catch (Exception $e) {
            echo "Erreur (ajout) : " . $e->getMessage();
        }
    }



  




    public static function updateContrat($contrat) {
        $dbSelect = config::getConnexion();
        $sqlSelect = "SELECT * FROM contrat WHERE idcontrat = :id";
        $querySelect = $dbSelect->prepare($sqlSelect);
        $querySelect->execute(['id' => $contrat->getId()]);
        $ancien = $querySelect->fetch();

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
            Logger::log('MODIFICATION', 
            'contrat', $ancien['idcontrat'] ,
            'type: ' . $ancien ['typecontrat'] .' duree : '. $ancien['dureecontrat'] . ' valeur :' .  $ancien['valeurStartup']);
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
            Logger::log('SUPPRESSION', 
            'contrat', $id);
        } catch (Exception $e) {
            echo "Erreur (suppression) : " . $e->getMessage();
        }
    }

    public static function getAllContrats() {
        $sql = "SELECT 
(select count(*) from investissement where investissement.contratid = c.idcontrat) as number ,
c.* , startup.nom as nomStartup FROM contrat c , 
        startup  WHERE c.idstartup = startup.id;";
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
        $sql = "SELECT 
(select count(*) from investissement where investissement.contratid = c.idcontrat) as number ,
c.* , startup.nom as nomStartup FROM contrat c , 
        startup  WHERE c.idstartup = startup.id
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
