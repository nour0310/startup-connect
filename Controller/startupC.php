<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/startup.php';

class StartupController {
    private $model;

    public function __construct() {
        $this->model = new StartupModel();
    }

    // Ajouter une startups
    public function addStartup($name, $description, $categoryId, $image = null) {
        $this->model->addStartup($name, $description, $categoryId, $image);
    }

    // Afficher toutes les startups
    public function getAllStartups() {
        try {
            // Add debug logging
            error_log("StartupController: Attempting to get all startups");
            
            if (!$this->model) {
                error_log("Error: Model not initialized");
                return [];
            }

            $startups = $this->model->getAllStartups();
            
            // Debug: Log the number of startups retrieved
            error_log("Number of startups retrieved: " . count($startups));
            
            if (empty($startups)) {
                error_log("No startups found in database");
            }
            
            return $startups;
        } catch (Exception $e) {
            error_log("Controller error in getAllStartups: " . $e->getMessage());
            return [];
        }
    }

    // Modifier une startup
    public function updateStartup($id, $name, $description, $categoryId, $image = null) {
        try {
            if (!$id) {
                throw new Exception("ID de startup non valide");
            }

            $success = $this->model->updateStartup($id, $name, $description, $categoryId, $image);
            if (!$success) {
                throw new Exception("Échec de la mise à jour de la startup");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in updateStartup: " . $e->getMessage());
            throw $e;
        }
    }

    // Supprimer une startup
    public function deleteStartup($id) {
        try {
            if (!$id) {
                throw new Exception("ID de startup non valide");
            }
            
            $startup = $this->getStartupById($id);
            if (!$startup) {
                throw new Exception("Startup non trouvée");
            }
            
            $this->model->deleteStartup($id);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting startup: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer une startup par ID
    public function getStartupById($id) {
        return $this->model->getStartupById($id);
    }

    // Add rating functionality
    public function rateStartup($startupId, $rating, $comment = null) {
        try {
            // For now, we'll use a simple rating without user tracking
            $sql = "INSERT INTO startup_ratings (startup_id, rating, comment) 
                    VALUES (:startup_id, :rating, :comment)";
            $stmt = $this->model->getDb()->prepare($sql);
            return $stmt->execute([
                'startup_id' => $startupId,
                'rating' => $rating,
                'comment' => $comment
            ]);
        } catch (Exception $e) {
            error_log("Error in rateStartup: " . $e->getMessage());
            return false;
        }
    }

    // Get average rating for a startup
    public function getStartupRating($startupId) {
        try {
            $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings 
                    FROM startup_ratings 
                    WHERE startup_id = :startup_id";
            $stmt = $this->model->getDb()->prepare($sql);
            $stmt->execute(['startup_id' => $startupId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getStartupRating: " . $e->getMessage());
            return ['average_rating' => 0, 'total_ratings' => 0];
        }
    }

    // Get startup details with ratings
    public function getStartupDetails($id) {
        try {
            $startup = $this->model->getStartupById($id);
            if (!$startup) {
                throw new Exception("Startup non trouvée");
            }

            // Get startup category name
            $sql = "SELECT name FROM categorie WHERE id = :id";
            $stmt = $this->model->getDb()->prepare($sql);
            $stmt->execute(['id' => $startup['category_id']]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            $startup['category_name'] = $category['name'] ?? '';

            // Get ratings with comments
            $sql = "SELECT rating, comment, created_at 
                   FROM startup_ratings 
                   WHERE startup_id = :startup_id 
                   ORDER BY created_at DESC";
            $stmt = $this->model->getDb()->prepare($sql);
            $stmt->execute(['startup_id' => $id]);
            $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'startup' => $startup,
                'ratings' => $ratings
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Handle form submissions
    public function handleRequest() {
        try {
            // Add this at the beginning of handleRequest method
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
                if ($_GET['action'] === 'get_startup_details' && isset($_GET['id'])) {
                    $details = $this->getStartupDetails($_GET['id']);
                    header('Content-Type: application/json');
                    echo json_encode($details);
                    exit;
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                error_log("POST request received: " . print_r($_POST, true));

                // Handle file upload
                $uploadedFile = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/startupConnect-website/uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Create defaults directory if it doesn't exist
                    $defaultsDir = $uploadDir . 'defaults/';
                    if (!file_exists($defaultsDir)) {
                        mkdir($defaultsDir, 0777, true);
                    }

                    // Copy default image if it doesn't exist
                    $defaultImage = $defaultsDir . 'default-startup.png';
                    if (!file_exists($defaultImage)) {
                        // You can either copy a default image here or create one
                        copy(__DIR__ . '/../assets/default-startup.png', $defaultImage);
                    }

                    $fileInfo = pathinfo($_FILES['image']['name']);
                    $extension = strtolower($fileInfo['extension']);
                    
                    // Validate file type
                    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        throw new Exception("Format de fichier non autorisé. Utilisez JPG ou PNG.");
                    }
                    
                    // Generate unique filename
                    $filename = uniqid() . '.' . $extension;
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Store relative path in database
                        $uploadedFile = '/startupConnect-website/uploads/' . $filename;
                        error_log("File uploaded successfully to: " . $uploadPath);
                        error_log("Stored path in database: " . $uploadedFile);
                    } else {
                        throw new Exception("Erreur lors du téléchargement de l'image.");
                    }
                }

                // Handle delete operation
                if (isset($_POST['delete_startup']) && isset($_POST['startup_id'])) {
                    error_log("Delete startup request received for ID: " . $_POST['startup_id']);
                    if ($this->deleteStartup($_POST['startup_id'])) {
                        $_SESSION['success_message'] = "Startup supprimée avec succès!";
                    } else {
                        throw new Exception("Erreur lors de la suppression de la startup.");
                    }
                    exit();
                }

                // Handle add operation
                if (isset($_POST['add_startup'])) {
                    error_log("Add startup request received");
                    
                    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category_id'])) {
                        throw new Exception("Tous les champs sont requis");
                    }

                    $result = $this->model->addStartup(
                        trim($_POST['name']),
                        trim($_POST['description']),
                        $_POST['category_id'],
                        $uploadedFile
                    );

                    if ($result) {
                        error_log("Startup added successfully");
                        echo "success"; // Send response back to AJAX call
                        exit();
                    } else {
                        throw new Exception("Erreur lors de l'ajout de la startup");
                    }
                }

                // Handle update operation
                if (isset($_POST['update_startup'])) {
                    error_log("Update startup request received");
                    
                    if (empty($_POST['startup_id']) || empty($_POST['name']) || 
                        empty($_POST['description']) || empty($_POST['category_id'])) {
                        throw new Exception("Tous les champs sont requis");
                    }

                    $result = $this->updateStartup(
                        $_POST['startup_id'],
                        trim($_POST['name']),
                        trim($_POST['description']),
                        $_POST['category_id'],
                        $uploadedFile
                    );

                    if ($result) {
                        error_log("Startup updated successfully");
                        echo "success";
                        exit();
                    } else {
                        throw new Exception("Erreur lors de la modification de la startup");
                    }
                }

                // Handle rate operation
                if (isset($_POST['action']) && $_POST['action'] === 'rate_startup') {
                    try {
                        $startupId = $_POST['startup_id'];
                        $rating = $_POST['rating'];
                        $comment = $_POST['comment'] ?? null;

                        if ($this->rateStartup($startupId, $rating, $comment)) {
                            echo json_encode(['success' => true]);
                        } else {
                            throw new Exception('Failed to save rating');
                        }
                    } catch (Exception $e) {
                        echo json_encode([
                            'success' => false,
                            'message' => $e->getMessage()
                        ]);
                    }
                    exit;
                }
            }
        } catch (Exception $e) {
            error_log("Error in handleRequest: " . $e->getMessage());
            http_response_code(500);
            echo $e->getMessage();
            exit();
        }
    }
}

// Initialize controller and handle requests
$controller = new StartupController();
$controller->handleRequest();
?>