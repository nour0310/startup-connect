<?php
require_once __DIR__ . '/../config.php';

class StartupModel {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion();
    }

    // Add this method
    public function getDb() {
        return $this->db;
    }

    // Lire toutes les startups avec leurs catégories
    public function getAllStartups() {
        try {
            $sql = "SELECT s.*, c.name as category_name,
                           (SELECT AVG(rating) FROM startup_ratings WHERE startup_id = s.id) as average_rating,
                           (SELECT COUNT(*) FROM startup_ratings WHERE startup_id = s.id) as total_ratings
                    FROM startup s 
                    LEFT JOIN categorie c ON s.category_id = c.id 
                    ORDER BY s.id DESC";
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Query results: " . print_r($results, true));
            
            return $results;
        } catch (Exception $e) {
            error_log("Error in getAllStartups: " . $e->getMessage());
            error_log("SQL: " . $sql);
            return [];
        }
    }

    // Ajouter une startup avec vérification
    public function addStartup($name, $description, $categoryId, $imagePath = null) {
        try {
            error_log("Starting addStartup with parameters: " . print_r([
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId,
                'image_path' => $imagePath
            ], true));

            // Validate inputs
            if (empty($name) || empty($description) || empty($categoryId)) {
                error_log("Invalid input parameters");
                return false;
            }

            $sql = "INSERT INTO startup (name, description, category_id, image_path) 
                    VALUES (:name, :description, :category_id, :image_path)";
            $query = $this->db->prepare($sql);
            
            error_log("Executing SQL: " . $sql);
            
            $params = [
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId,
                'image_path' => $imagePath
            ];
            
            $result = $query->execute($params);
            
            if (!$result) {
                error_log("Database error: " . print_r($query->errorInfo(), true));
                return false;
            }
            
            $newId = $this->db->lastInsertId();
            error_log("New startup added with ID: " . $newId);
            
            return true;
        } catch (Exception $e) {
            error_log("Exception in addStartup: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        $sql = "SELECT * FROM startup WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    // Mettre à jour une startup
    public function updateStartup($id, $name, $description, $categoryId, $imagePath = null) {
        try {
            error_log("Starting updateStartup with parameters: " . print_r([
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId,
                'image_path' => $imagePath
            ], true));

            if (empty($id) || empty($name) || empty($description) || empty($categoryId)) {
                error_log("Invalid input parameters for update");
                return false;
            }

            $sql = "UPDATE startup SET name = :name, description = :description, 
                    category_id = :category_id";
            $params = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'category_id' => $categoryId
            ];
            
            if ($imagePath !== null) {
                $sql .= ", image_path = :image_path";
                $params['image_path'] = $imagePath;
            }
            
            $sql .= " WHERE id = :id";
            $query = $this->db->prepare($sql);
            
            error_log("Executing update SQL: " . $sql);
            
            $result = $query->execute($params);

            if (!$result) {
                error_log("Database error during update: " . print_r($query->errorInfo(), true));
                return false;
            }

            if ($query->rowCount() === 0) {
                error_log("No startup was updated with id: " . $id);
                return false;
            }

            error_log("Startup updated successfully");
            return true;
        } catch (Exception $e) {
            error_log("Exception in updateStartup: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        $sql = "DELETE FROM startup WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
    }
}
?>