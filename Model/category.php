<?php
require_once __DIR__ . '/../config.php';

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = Config::GetConnexion();
    }

    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM categorie ORDER BY id";
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    public function addCategory($name) {
        try {
            $sql = "INSERT INTO categorie (name) VALUES (:name)";
            $query = $this->db->prepare($sql);
            return $query->execute(['name' => $name]);
        } catch (Exception $e) {
            error_log("Error in addCategory: " . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $name) {
        try {
            $sql = "UPDATE categorie SET name = :name WHERE id = :id";
            $query = $this->db->prepare($sql);
            return $query->execute(['id' => $id, 'name' => $name]);
        } catch (Exception $e) {
            error_log("Error in updateCategory: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // First delete related startups
            $sqlStartups = "DELETE FROM startup WHERE category_id = :id";
            $queryStartups = $this->db->prepare($sqlStartups);
            $queryStartups->execute(['id' => $id]);
            
            // Then delete the category
            $sqlCategory = "DELETE FROM categorie WHERE id = :id";
            $queryCategory = $this->db->prepare($sqlCategory);
            $result = $queryCategory->execute(['id' => $id]);
            
            // Commit transaction
            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            error_log("Error in deleteCategory: " . $e->getMessage());
            return false;
        }
    }

    public function getCategoryById($id) {
        try {
            $sql = "SELECT * FROM categorie WHERE id = :id";
            $query = $this->db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getCategoryById: " . $e->getMessage());
            return null;
        }
    }
}
?>
