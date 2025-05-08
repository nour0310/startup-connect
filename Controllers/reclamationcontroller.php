<?php
require_once '../models/ReponseModel.php';

/**
 * Contrôleur pour la gestion des réclamations
 */
class ReclamationController {
    private $model;

    public function __construct() {
        $this->model = new ReponseModel();
    }

    /**
     * Affiche le détail d'une réclamation
     * @param int $id - ID de la réclamation
     */
    public function show($id) {
        // Récupération des données
        $data = $this->model->getReclamationWithResponses($id);
        
        if (!$data) {
            die('Réclamation introuvable');
        }

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleResponseForm($id, $data);
        }

        // Affichage de la vue
        require '../views/back/reclamations/detail.php';
    }

    /**
     * Gère la soumission du formulaire de réponse
     */
    private function handleResponseForm($id, &$data) {
        if (!empty($_POST['response_text'])) {
            $adminId = 1; // Remplacer par l'ID de la session
            $success = $this->model->addResponse($id, $adminId, $_POST['response_text']);
            
            if ($success) {
                header("Location: ?action=show&id=$id");
                exit();
            }
        }
    }
}