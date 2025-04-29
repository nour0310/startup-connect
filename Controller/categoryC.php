<?php
require_once __DIR__ . '/../Model/category.php';

class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    public function getAllCategories() {
        return $this->model->getAllCategories();
    }

    public function handleRequest() {
        try {
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['action']) && $_GET['action'] === 'getAll') {
                    $categories = $this->getAllCategories();
                    echo json_encode([
                        'success' => true,
                        'data' => $categories,
                        'icons' => $this->getCategoryIcons()
                    ]);
                    exit;
                }
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $response = ['success' => false, 'message' => ''];

                if (isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case 'add':
                            if (empty($_POST['name'])) {
                                throw new Exception("Le nom de la catégorie est requis");
                            }
                            
                            // Add validation for category name
                            $categoryName = trim($_POST['name']);
                            if (strlen($categoryName) > 10) {
                                throw new Exception("Le nom de la catégorie ne doit pas dépasser 10 caractères");
                            }
                            if (is_numeric($categoryName)) {
                                throw new Exception("Le nom de la catégorie ne peut pas être un nombre");
                            }
                            if (preg_match('/\d/', $categoryName)) {
                                throw new Exception("Le nom de la catégorie ne peut pas contenir de chiffres");
                            }
                            
                            if ($this->model->addCategory($categoryName)) {
                                $response = ['success' => true, 'message' => 'Catégorie ajoutée avec succès'];
                            }
                            break;

                        case 'update':
                            if (empty($_POST['id']) || empty($_POST['name'])) {
                                throw new Exception("ID et nom sont requis");
                            }
                            if ($this->model->updateCategory($_POST['id'], $_POST['name'])) {
                                $response = ['success' => true, 'message' => 'Catégorie mise à jour avec succès'];
                            }
                            break;

                        case 'delete':
                            if (empty($_POST['id'])) {
                                throw new Exception("ID est requis");
                            }
                            if ($this->model->deleteCategory($_POST['id'])) {
                                $response = ['success' => true, 'message' => 'Catégorie supprimée avec succès'];
                            }
                            break;
                    }
                }

                echo json_encode($response);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    private function getCategoryIcons() {
        return [
            1 => 'fas fa-microchip',
            2 => 'fas fa-heartbeat',
            3 => 'fas fa-graduation-cap',
            4 => 'fas fa-chart-line',
            5 => 'fas fa-shopping-cart'
        ];
    }
}

// Only initialize and handle requests if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $controller = new CategoryController();
    $controller->handleRequest();
}
?>
