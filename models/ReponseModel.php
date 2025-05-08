<?php
/**
 * Modèle pour gérer les réclamations et réponses
 */
class ReclamationModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /**
     * Récupère une réclamation avec ses réponses (Jointure)
     * @param int $id - ID de la réclamation
     * @return array - Structure unifiée des données
     */
    public function getReclamationWithResponses($id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.*,
                rr.id as response_id,
                rr.reponse,
                rr.date_reponse,
                a.nom as admin_nom
            FROM reclamations r
            LEFT JOIN reponses_reclamations rr ON r.id = rr.reclamation_id
            LEFT JOIN admins a ON rr.admin_id = a.id
            WHERE r.id = ?
            ORDER BY rr.date_reponse ASC
        ");
        $stmt->execute([$id]);
        
        $data = $stmt->fetchAll();
        
        if (empty($data)) return null;

        // Structure les données
        $result = [
            'reclamation' => [
                'id' => $data[0]['id'],
                'sujet' => $data[0]['subject'],
                'description' => $data[0]['description'],
                // ... autres champs
            ],
            'reponses' => []
        ];

        foreach ($data as $row) {
            if ($row['response_id']) {
                $result['reponses'][] = [
                    'id' => $row['response_id'],
                    'text' => $row['reponse'],
                    'date' => $row['date_reponse'],
                    'admin' => $row['admin_nom']
                ];
            }
        }

        return $result;
    }

    /**
     * Ajoute une réponse à une réclamation
     * @param int $reclamation_id
     * @param int $admin_id
     * @param string $reponse
     * @return bool - Succès de l'opération
     */
    public function addResponse($reclamation_id, $admin_id, $reponse) {
        $stmt = $this->pdo->prepare("
            INSERT INTO reponses_reclamations 
            (reclamation_id, admin_id, reponse, date_reponse)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$reclamation_id, $admin_id, $reponse]);
    }
}