<?php
session_start();
require_once '../../Controller/startupC.php';

// Initialiser le contrôleur et récupérer les startups
$controller = new StartupController();
$startups = $controller->getAllStartups();

// Messages de notification
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Nettoyer les messages de session
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - Startup Management</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Stylesheets and fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #06A3DA;
            --secondary: #5D8FC9;
            --dark: #0B2154;
            --light: #F2F8FE;
            --success: #0ABF30;
            --warning: #FFC107;
            --danger: #DC3545;
            --border-radius: 10px;
            --box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fd;
            overflow-x: hidden;
        }
        
        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 15px 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand h1 {
            font-size: 1.75rem;
            color: white;
            font-weight: 700;
        }
        
        .navbar-toggler {
            border: none;
            color: white;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            margin-left: 20px;
            transition: var(--transition);
            padding: 8px 15px;
            border-radius: 5px;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Dashboard Header */
        .dashboard-header {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }
        
        .dashboard-header h2 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .dashboard-header p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Table Styling */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 30px;
            transition: var(--transition);
        }
        
        .table-container:hover {
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.15);
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            color: var(--dark);
            font-weight: 700;
            border-bottom: 2px solid #eef0f5;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #eef0f5;
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background-color: rgba(6, 163, 218, 0.05);
            transform: translateY(-2px);
        }
        
        .table td {
            padding: 18px 15px;
            vertical-align: middle;
        }
        
        /* Action Buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            margin-right: 5px;
            border: none;
        }
        
        .btn-edit {
            background-color: rgba(93, 143, 201, 0.1);
            color: var(--secondary);
        }
        
        .btn-edit:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }
        
        .btn-delete:hover {
            background-color: var(--danger);
            color: white;
        }
        
        /* Floating Action Button */
        .floating-btn {
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(6, 163, 218, 0.4);
            transition: var(--transition);
            z-index: 1000;
            border: none;
        }
        
        .floating-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(6, 163, 218, 0.5);
        }
        
        .floating-btn i {
            font-size: 1.5rem;
        }
        
        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            border-bottom: 1px solid #eef0f5;
            padding: 20px 30px;
        }
        
        .modal-title {
            font-weight: 700;
            color: var(--dark);
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            border-top: 1px solid #eef0f5;
            padding: 20px 30px;
        }
        
        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #eef0f5;
            border-radius: 8px;
            padding: 12px 15px;
            transition: var(--transition);
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(6, 163, 218, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        /* Buttons */
        .btn {
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 6px;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0588b8 0%, #4a7ab0 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(6, 163, 218, 0.3);
        }
        
        .btn-secondary {
            background-color: #eef0f5;
            color: #6c757d;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #dde0e5;
            color: #495057;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }
        
        /* Responsiveness */
        @media (max-width: 992px) {
            .navbar-nav {
                margin-top: 15px;
            }
            
            .navbar-nav .nav-link {
                margin-left: 0;
                margin-bottom: 5px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-header,
            .table-container {
                padding: 20px;
            }
            
            .floating-btn {
                width: 55px;
                height: 55px;
                bottom: 30px;
                right: 30px;
            }
            
            .table thead th,
            .table td {
                padding: 12px 10px;
            }
            
            .btn {
                padding: 8px 18px;
            }
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }
        
        .toast {
            background-color: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            overflow: hidden;
            border: none;
            margin-bottom: 10px;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.4s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast-header {
            background-color: transparent;
            border-bottom: 1px solid #eef0f5;
            padding: 12px 15px;
        }
        
        .toast-body {
            padding: 15px;
        }
        
        .toast-success {
            border-left: 4px solid var(--success);
        }
        
        .toast-error {
            border-left: 4px solid var(--danger);
        }

        .startup-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #eef0f5;
        }

        /* Enhanced Search Styles */
        .search-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-wrapper .input-group {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .search-wrapper .input-group-text {
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
        }

        #searchInput {
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            box-shadow: none;
        }

        #searchInput:focus {
            border-color: var(--primary);
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 5px;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eef0f5;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-result-item:hover {
            background-color: rgba(6, 163, 218, 0.1);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .highlight {
            background-color: rgba(6, 163, 218, 0.2);
            padding: 2px 4px;
            border-radius: 3px;
        }

        #clearSearch {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Category Filter Styles */
        #categoryFilter {
            height: 100%;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        #categoryFilter:focus {
            border-color: var(--primary);
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a href="dashboard-startup.php" class="navbar-brand">
                <h1 class="m-0"><i class="fa fa-rocket me-2"></i>Admin Dashboard</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="../Front/index.html" class="nav-item nav-link">
                        <i class="fa fa-home me-1"></i> Retour au site
                    </a>
                    <a href="../Front/login.html" class="nav-item nav-link">
                        <i class="fa fa-sign-out-alt me-1"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Toast Container for Notifications -->
    <div class="toast-container">
        <!-- Success Toast -->
        <div class="toast toast-success" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-check-circle text-success me-2"></i> Succès</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                L'opération a été effectuée avec succès!
            </div>
        </div>
        
        <!-- Error Toast -->
        <div class="toast toast-error" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-exclamation-circle text-danger me-2"></i> Erreur</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Une erreur s'est produite. Veuillez réessayer.
            </div>
        </div>
    </div>

    <!-- Dashboard Content Start -->
    <div class="container-fluid py-4 px-4">
        <div class="dashboard-header fade-in">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2><i class="fas fa-layer-group me-2"></i>Gestion des Startups</h2>
                    <p>Gérez vos startups, ajoutez ou modifiez les informations facilement</p>
                </div>
                <div class="col-lg-6 text-end">
                    <div class="btn-group" role="group">
                        <button class="btn btn-light" id="refreshBtn">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                        <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addStartupModal">
                            <i class="fas fa-plus me-2"></i>Ajouter une Startup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar Start -->
        <div class="row mb-4 fade-in">
            <div class="col-md-8">
                <div class="search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0" 
                               placeholder="Rechercher par nom, description ou catégorie...">
                        <button class="btn btn-primary" id="clearSearch" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="searchResults" class="search-results" style="display: none;"></div>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="categoryFilter">
                    <option value="">Toutes les catégories</option>
                    <option value="1">Technologie</option>
                    <option value="2">Santé</option>
                    <option value="3">Éducation</option>
                    <option value="4">Finance</option>
                    <option value="5">E-commerce</option>
                </select>
            </div>
        </div>
        <!-- Search Bar End -->

        <div class="table-container fade-in">
            <div class="table-responsive">
                <table class="table" id="startupTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Image</th>
                            <th width="15%">Nom</th>
                            <th width="40%">Description</th>
                            <th width="10%">Catégorie</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($startups)): ?>
                            <?php foreach ($startups as $startup): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($startup['id']); ?></td>
                                    <td>
                                        <?php 
                                            $defaultImage = '/startupConnect-website/uploads/defaults/default-startup.png';
                                            if (!empty($startup['image_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $startup['image_path'])) {
                                                $imagePath = $startup['image_path'];
                                            } else {
                                                $imagePath = $defaultImage;
                                            }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                             alt="<?php echo htmlspecialchars($startup['name']); ?>" 
                                             class="startup-image"
                                             onerror="this.onerror=null; this.src='<?php echo $defaultImage; ?>';">
                                    </td>
                                    <td><?php echo htmlspecialchars($startup['name']); ?></td>
                                    <td><?php echo htmlspecialchars($startup['description']); ?></td>
                                    <td>
                                        <span class="badge bg-info" data-category-id="<?php echo htmlspecialchars($startup['category_id']); ?>">
                                            <?php echo htmlspecialchars($startup['category_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="editStartup(<?php echo $startup['id']; ?>, '<?php echo addslashes($startup['name']); ?>', '<?php echo addslashes($startup['description']); ?>', '<?php echo $startup['category_id']; ?>')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="deleteStartup(<?php echo $startup['id']; ?>)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucune startup trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Dashboard Content End -->

    <!-- Floating Action Button -->
    <button class="floating-btn" data-bs-toggle="modal" data-bs-target="#addStartupModal">
        <i class="fa fa-plus"></i>
    </button>

    <!-- Add Startup Modal -->
    <div class="modal fade" id="addStartupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Ajouter une Startup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../Controller/startupC.php" method="POST" id="addStartupForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nom de la Startup</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom de la startup" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Décrivez la startup et son activité" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" disabled selected>Sélectionnez une catégorie</option>
                                <option value="1">Technologie</option>
                                <option value="2">Santé</option>
                                <option value="3">Éducation</option>
                                <option value="4">Finance</option>
                                <option value="5">E-commerce</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="form-label">Image de la Startup</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <small class="text-muted">Format accepté: JPG, PNG. Taille max: 5MB</small>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="add_startup" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Startup Modal -->
    <div class="modal fade" id="editStartupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Modifier la Startup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../Controller/startupC.php" method="POST" id="editStartupForm" enctype="multipart/form-data">
                        <input type="hidden" id="edit_startup_id" name="startup_id">
                        <div class="mb-4">
                            <label for="edit_name" class="form-label">Nom de la Startup</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-4">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="4" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="edit_category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="1">Technologie</option>
                                <option value="2">Santé</option>
                                <option value="3">Éducation</option>
                                <option value="4">Finance</option>
                                <option value="5">E-commerce</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_image" class="form-label">Image de la Startup</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <small class="text-muted">Laissez vide pour conserver l'image existante</small>
                            <div id="current_image_preview" class="mt-2"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="update_startup" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add deleteStartup function
        function deleteStartup(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette startup ?')) {
                fetch('../../Controller/startupC.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'delete_startup=1&startup_id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Delete response:', data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la suppression');
                });
            }
        }

        // Staggered animation for table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
            
            // Show success toast if there's a success parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                successToast.show();
                
                // Remove the parameter from URL
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: newUrl}, '', newUrl);
            }
            
            if (urlParams.has('error')) {
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.querySelector('#errorToast .toast-body').textContent = decodeURIComponent(urlParams.get('error'));
                errorToast.show();
                
                // Remove the parameter from URL
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: newUrl}, '', newUrl);
            }
        });
        
        // Refresh button functionality
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });
        
        // Edit Startup Function
        function editStartup(id, name, description, categoryId) {
            document.getElementById('edit_startup_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_category_id').value = categoryId;
            
            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editStartupModal'));
            editModal.show();
        }
        
        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Custom validation for "Nom de la Startup"
        function validateStartupForm(form, isEdit = false) {
            let nameInput = form.querySelector(isEdit ? '#edit_name' : '#name');
            let descInput = form.querySelector(isEdit ? '#edit_description' : '#description');
            let catInput = form.querySelector(isEdit ? '#edit_category_id' : '#category_id');
            let name = nameInput.value.trim();
            let desc = descInput.value.trim();
            let cat = catInput.value;

            // Check if name is empty or a number
            if (!name || !isNaN(name)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de validation',
                    text: 'Le nom de la startup ne doit pas être vide ni un nombre.',
                    confirmButtonColor: '#06A3DA'
                });
                nameInput.focus();
                return false;
            }
            // Check if description is empty
            if (!desc) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de validation',
                    text: 'Veuillez remplir la description.',
                    confirmButtonColor: '#06A3DA'
                });
                descInput.focus();
                return false;
            }
            // Check if category is selected
            if (!cat) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de validation',
                    text: 'Veuillez sélectionner une catégorie.',
                    confirmButtonColor: '#06A3DA'
                });
                catInput.focus();
                return false;
            }
            // For add form, check if image is selected
            if (!isEdit) {
                let imgInput = form.querySelector('#image');
                if (!imgInput.files.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur de validation',
                        text: 'Veuillez sélectionner une image.',
                        confirmButtonColor: '#06A3DA'
                    });
                    imgInput.focus();
                    return false;
                }
            }
            return true;
        }

        // Add Startup Form Handler with validation
        document.getElementById('addStartupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateStartupForm(this, false)) return;
            console.log('Form submission started');
            
            const formData = new FormData(this);
            formData.append('add_startup', '1'); // Add this line to indicate add operation
            
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch('../../Controller/startupC.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('Server response:', data);
                // Show success message
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                document.querySelector('#successToast .toast-body').textContent = "Startup ajoutée avec succès!";
                successToast.show();
                
                // Close modal and refresh page after short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStartupModal'));
                    modal.hide();
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.querySelector('#errorToast .toast-body').textContent = "Une erreur s'est produite lors de l'ajout de la startup.";
                errorToast.show();
            });
        });

        // Edit Startup Form Handler with validation
        document.getElementById('editStartupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateStartupForm(this, true)) return;
            console.log('Edit form submission started');
            
            const formData = new FormData(this);
            formData.append('update_startup', '1');
            
            console.log('Edit form data:', Object.fromEntries(formData));
            
            fetch('../../Controller/startupC.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('Server response:', data);
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                document.querySelector('#successToast .toast-body').textContent = "Startup modifiée avec succès!";
                successToast.show();
                
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editStartupModal'));
                    modal.hide();
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.querySelector('#errorToast .toast-body').textContent = "Une erreur s'est produite lors de la modification de la startup.";
                errorToast.show();
            });
        });

        // Show success message if present
        <?php if (isset($_SESSION['success_message'])): ?>
            const successToast = new bootstrap.Toast(document.getElementById('successToast'));
            document.querySelector('#successToast .toast-body').textContent = <?php echo json_encode($_SESSION['success_message']); ?>;
            successToast.show();
        <?php endif; ?>

        // Show error message if present
        <?php if (isset($_SESSION['error_message'])): ?>
            const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
            document.querySelector('#errorToast .toast-body').textContent = <?php echo json_encode($_SESSION['error_message']); ?>;
            errorToast.show();
        <?php endif; ?>

        // Add image preview functionality with error handling
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.style.maxWidth = '200px';
                    preview.style.marginTop = '10px';
                    preview.className = 'startup-image';
                    preview.onerror = function() {
                        this.onerror = null;
                        this.src = '/startupConnect-website/uploads/defaults/default-startup.png';
                    };
                    const container = document.getElementById('image').parentNode;
                    const oldPreview = container.querySelector('img');
                    if (oldPreview) {
                        container.removeChild(oldPreview);
                    }
                    container.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });

        // Instant search/filter for startups table
        $(document).ready(function() {
            const searchInput = $('#searchInput');
            const clearSearch = $('#clearSearch');
            const categoryFilter = $('#categoryFilter');
            let searchTimeout;

            function highlightText(text, search) {
                const pattern = new RegExp(`(${search})`, 'gi');
                return text.replace(pattern, '<span class="highlight">$1</span>');
            }

            function filterTable() {
                const searchText = searchInput.val().toLowerCase();
                const categoryValue = categoryFilter.val();
                let hasResults = false;

                $('#startupTable tbody tr').each(function() {
                    const row = $(this);
                    const name = row.find('td:nth-child(3)').text().toLowerCase();
                    const description = row.find('td:nth-child(4)').text().toLowerCase();
                    const category = row.find('td:nth-child(5)').text().toLowerCase();
                    const categoryId = row.find('td:nth-child(5)').data('category-id');

                    const matchesSearch = searchText === '' || 
                        name.includes(searchText) || 
                        description.includes(searchText) || 
                        category.includes(searchText);

                    const matchesCategory = categoryValue === '' || 
                        categoryId === categoryValue;

                    if (matchesSearch && matchesCategory) {
                        row.show();
                        hasResults = true;
                        
                        // Highlight matching text if there's a search term
                        if (searchText) {
                            row.find('td:nth-child(3)').html(highlightText(name, searchText));
                            row.find('td:nth-child(4)').html(highlightText(description, searchText));
                            row.find('td:nth-child(5) .badge').html(highlightText(category, searchText));
                        }
                    } else {
                        row.hide();
                    }
                });

                // Show/hide no results message
                if (!hasResults) {
                    if ($('#noResults').length === 0) {
                        $('#startupTable tbody').append(
                            '<tr id="noResults"><td colspan="6" class="text-center">Aucun résultat trouvé</td></tr>'
                        );
                    }
                } else {
                    $('#noResults').remove();
                }
            }

            // Search input handler with debouncing
            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const searchText = $(this).val();
                clearSearch.toggle(searchText.length > 0);

                searchTimeout = setTimeout(filterTable, 300);
            });

            // Category filter handler
            categoryFilter.on('change', filterTable);

            // Clear search button handler
            clearSearch.on('click', function() {
                searchInput.val('');
                $(this).hide();
                filterTable();
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>