<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../../config.php';
require_once '../../Controller/startupC.php';
require_once '../../Model/category.php';

$controller = new StartupController();
$startups = $controller->getAllStartups();

$categoryModel = new CategoryModel();
$categories = $categoryModel->getAllCategories();

// Message handling
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear the messages
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Function to get category icon
function getCategoryIcon($categoryId) {
    $icons = [
        1 => 'fas fa-microchip',
        2 => 'fas fa-heartbeat',
        3 => 'fas fa-graduation-cap',
        4 => 'fas fa-chart-line',
        5 => 'fas fa-shopping-cart'
    ];
    return $icons[$categoryId] ?? 'fas fa-folder'; // Default icon if no match
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Startup - Gestion des Startups</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Gestion des startups" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Enhanced custom styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(6, 163, 218, 0.2);
        }

        .startup-image {
            height: 220px;
            transition: all 0.5s ease;
        }

        .card:hover .startup-image {
            transform: scale(1.05);
        }

        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        #searchInput {
            border-radius: 25px;
            padding-left: 20px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        #searchInput:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 0 0.2rem rgba(6, 163, 218, 0.25);
        }

        .filter-btn {
            border-radius: 25px;
            padding: 8px 25px;
            background: #06A3DA;
            border: none;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: #058bb8;
            transform: translateY(-2px);
        }

        .sidebar {
            background: linear-gradient(145deg, #ffffff, #f5f5f5);
            border-radius: 15px;
        }

        .sidebar ul li a {
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: rgba(6, 163, 218, 0.1);
            padding-left: 20px;
        }

        .sidebar ul li a.active {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
        }

        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }

        .btn-primary {
            border-radius: 25px;
            padding: 8px 20px;
            background: #06A3DA;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #058bb8;
            transform: translateY(-2px);
        }

        /* Modal enhancements */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 25px;
        }

        /* Alert animations */
        .alert {
            border-radius: 10px;
            animation: slideInDown 0.5s ease;
        }

        /* Category icons */
        .category-icon {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .rating-input .stars {
            font-size: 2em;
            cursor: pointer;
        }

        .rating-input .stars i {
            padding: 0.2em;
            color: #ffc107;
            transition: all 0.2s ease;
        }

        .rating-input .stars i:hover,
        .rating-input .stars i.active {
            transform: scale(1.2);
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }

        .rating-display .stars {
            color: #ffc107;
            font-size: 1.1em;
        }

        .rating-value {
            font-weight: bold;
            color: #6c757d;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .rating-success {
            animation: pulse 0.5s ease;
        }

        .form-select {
            padding: 0.75rem 1rem;
            border-radius: 25px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-select:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 0 0.2rem rgba(6, 163, 218, 0.25);
        }

        #ratingFilter {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .category-filter {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .category-filter:hover {
            background: rgba(6, 163, 218, 0.1);
            color: #06A3DA;
            text-decoration: none;
            transform: translateX(5px);
        }

        .category-filter.active {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
            color: white;
        }

        .category-icon {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Rue Tunis, Tunisie, TN</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 29 999 999</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>startupconnect@gmail.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;"></div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="background-color: #06A3DA;">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.html" class="nav-item nav-link">Acceuil</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="dashboard.html" class="dropdown-item">Dashboard</a>
                            <a href="#" class="dropdown-item">Gestion utilisateurs</a>
                            <a href="#" class="dropdown-item">Gestion profiles</a>
                            <a href="gestion-startup.php" class="dropdown-item">Gestion Startup</a>
                            <a href="#" class="dropdown-item">Gestion evénements</a>
                            <a href="gestionInvestissement.html" class="dropdown-item">Gestion des investissements</a>
                            <a href="#" class="dropdown-item">Gestion documents</a>
                        </div>
                    </div>
                    <a href="login.html" class="nav-item nav-link">Connexion</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Main Content -->
    <div class="container py-5" style="margin-top: 100px !important;">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Catégories</h4>
                    <ul class="list-unstyled" id="categoryList">
                        <li>
                            <a href="#" class="category-filter active" data-category="0">
                                <i class="fas fa-th category-icon"></i>Toutes les catégories
                            </a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="#" class="category-filter" data-category="<?php echo htmlspecialchars($category['id']); ?>">
                                    <i class="<?php echo getCategoryIcon($category['id']); ?> category-icon"></i>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-12">
                        <h1>Liste des Startups</h1>
                    </div>
                </div>

                <!-- Search Bar and Filter -->
                <div class="row search-bar mt-3 animate__animated animate__fadeIn">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une startup...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="ratingFilter">
                            <option value="">Trier par étoiles</option>
                            <option value="5">5 étoiles</option>
                            <option value="4">4+ étoiles</option>
                            <option value="3">3+ étoiles</option>
                            <option value="2">2+ étoiles</option>
                            <option value="1">1+ étoile</option>
                            <option value="most">Les mieux notées</option>
                        </select>
                    </div>
                </div>

                <!-- Gallery of Startups -->
                <div class="row mt-4" id="startupGallery">
                    <?php if(empty($startups)): ?>
                        <div class="col-12 text-center">
                            <p>Aucune startup trouvée.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($startups as $startup): ?>
                            <div class="col-md-4 mb-4 startup-card" data-category="<?php echo $startup['category_id']; ?>">
                                <div class="card">
                                    <?php 
                                        // Handle image path
                                        $defaultImage = '/startupConnect-website/uploads/defaults/default-startup.png';
                                        if (!empty($startup['image_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $startup['image_path'])) {
                                            $imagePath = $startup['image_path'];
                                        } else {
                                            $imagePath = $defaultImage;
                                        }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         class="startup-image" 
                                         alt="<?php echo htmlspecialchars($startup['name']); ?>"
                                         onerror="this.onerror=null; this.src='<?php echo $defaultImage; ?>';">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($startup['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($startup['description'], 0, 100)) . '...'; ?></p>
                                        <p class="badge bg-info"><?php echo htmlspecialchars($startup['category_name']); ?></p>
                                        <div class="rating-display mb-2">
                                            <div class="stars">
                                                <?php
                                                    $rating = isset($startup['average_rating']) ? floatval($startup['average_rating']) : 0;
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $rating) {
                                                            echo '<i class="fas fa-star text-warning"></i>';
                                                        } elseif ($i - 0.5 <= $rating) {
                                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star text-warning"></i>';
                                                        }
                                                    }
                                                ?>
                                                <span class="rating-value ms-2"><?php echo number_format($rating, 1); ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-outline-warning btn-sm me-2" onclick="rateStartup(<?php echo $startup['id']; ?>)">
                                                <i class="fas fa-star me-1"></i>Noter
                                            </button>
                                            <a href="#" class="btn btn-primary btn-sm view-startup" data-id="<?php echo $startup['id']; ?>">Voir plus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Noter la startup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rating-input text-center mb-4">
                        <div class="stars">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <span class="selected-rating mt-2 d-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="ratingComment" class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" id="ratingComment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="submitRating">Envoyer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Startup Details Modal -->
    <div class="modal fade" id="startupDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la Startup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="" alt="" class="img-fluid startup-detail-image mb-3">
                        </div>
                        <div class="col-md-6">
                            <h3 class="startup-name"></h3>
                            <div class="rating-display mb-3">
                                <div class="stars"></div>
                                <span class="rating-count"></span>
                            </div>
                            <p class="startup-description"></p>
                            <p class="startup-category"><strong>Catégorie: </strong><span></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        // Rating functionality
        let currentStartupId = null;
        let selectedRating = 0;

        function rateStartup(startupId) {
            currentStartupId = startupId;
            selectedRating = 0;
            updateStarDisplay(0);
            $('#ratingComment').val('');
            $('#ratingModal').modal('show');
        }

        // Star rating interaction
        $('.rating-input .stars i').hover(
            function() {
                const rating = $(this).data('rating');
                updateStarDisplay(rating);
            },
            function() {
                updateStarDisplay(selectedRating);
            }
        ).click(function() {
            selectedRating = $(this).data('rating');
            updateStarDisplay(selectedRating);
            $('.selected-rating').text(`${selectedRating} étoiles`);
        });

        function updateStarDisplay(rating) {
            $('.rating-input .stars i').each(function(index) {
                $(this).toggleClass('fas', index < rating)
                    .toggleClass('far', index >= rating);
            });
        }

        // Submit rating
        $('#submitRating').click(function() {
            if (!selectedRating) {
                alert('Veuillez sélectionner une note');
                return;
            }

            const formData = {
                action: 'rate_startup',
                startup_id: currentStartupId,
                rating: selectedRating,
                comment: $('#ratingComment').val()
            };

            console.log('Sending data:', formData); // Debug log

            $.ajax({
                url: '../../Controller/startupC.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    
                    let data;
                    try {
                        data = typeof response === 'string' ? JSON.parse(response) : response;
                        console.log('Parsed data:', data); // Debug log
                        
                        if (data.success) {
                            $('#ratingModal').modal('hide');
                            updateStartupCard(currentStartupId, data.newRating);
                            showAlert('success', 'Merci pour votre évaluation !');
                            
                            // Reset the form
                            selectedRating = 0;
                            $('#ratingComment').val('');
                            updateStarDisplay(0);
                        } else {
                            showAlert('danger', data.message || 'Une erreur est survenue');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        showAlert('danger', 'Une erreur est survenue lors du traitement de la réponse');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {xhr, status, error});
                    showAlert('danger', 'Une erreur est survenue lors de l\'envoi de votre évaluation');
                }
            });
        });

        // View startup details
        $('.view-startup').click(function(e) {
            e.preventDefault();
            const startupId = $(this).data('id');
            
            $.ajax({
                url: '../../Controller/startupC.php',
                method: 'GET',
                data: {
                    action: 'get_startup_details',
                    id: startupId
                },
                success: function(response) {
                    try {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (data.success) {
                            const startup = data.startup;
                            
                            // Mettre à jour les détails dans le modal
                            $('#startupDetailsModal .startup-name').text(startup.name || '');
                            $('#startupDetailsModal .startup-description').text(startup.description || '');
                            $('#startupDetailsModal .startup-category span').text(startup.category_name || '');
                            
                            // Gérer l'image avec fallback
                            const imagePath = startup.image_path || '/startupConnect-website/uploads/defaults/default-startup.png';
                            $('#startupDetailsModal .startup-detail-image')
                                .attr('src', imagePath)
                                .attr('alt', startup.name)
                                .on('error', function() {
                                    $(this).attr('src', '/startupConnect-website/uploads/defaults/default-startup.png');
                                });
                            
                            // Mettre à jour l'affichage des étoiles
                            const rating = parseFloat(startup.average_rating) || 0;
                            let starsHtml = '';
                            for (let i = 1; i <= 5; i++) {
                                if (i <= rating) {
                                    starsHtml += '<i class="fas fa-star text-warning"></i>';
                                } else if (i - 0.5 <= rating) {
                                    starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
                                } else {
                                    starsHtml += '<i class="far fa-star text-warning"></i>';
                                }
                            }
                            $('#startupDetailsModal .stars').html(starsHtml);
                            $('#startupDetailsModal .rating-count').text(`(${startup.rating_count || 0} évaluations)`);
                            
                            // Afficher le modal
                            $('#startupDetailsModal').modal('show');
                        } else {
                            showAlert('danger', data.message || 'Erreur lors du chargement des détails');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        showAlert('danger', 'Erreur lors du traitement de la réponse');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {xhr, status, error});
                    showAlert('danger', 'Erreur lors du chargement des détails');
                }
            });
        });

        function updateStartupCard(startupId, newRating) {
            const card = $(`.startup-card[data-id="${startupId}"]`);
            const starsContainer = card.find('.rating-display .stars');
            let starsHtml = '';
            
            for (let i = 1; i <= 5; i++) {
                if (i <= newRating) {
                    starsHtml += '<i class="fas fa-star text-warning"></i>';
                } else if (i - 0.5 <= newRating) {
                    starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
                } else {
                    starsHtml += '<i class="far fa-star text-warning"></i>';
                }
            }
            
            starsContainer.html(starsHtml);
            card.find('.rating-value').text(newRating.toFixed(1));
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.container').prepend(alertHtml);
            
            // Auto-hide alert after 3 seconds
            setTimeout(() => {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>

    <style>
        /* Rating modal enhancements */
        .rating-input .stars {
            font-size: 2.5em;
            cursor: pointer;
            text-align: center;
        }

        .rating-input .stars i {
            padding: 0.2em;
            color: #ffc107;
            transition: transform 0.2s ease;
        }

        .rating-input .stars i:hover {
            transform: scale(1.2);
        }

        .selected-rating {
            color: #666;
            font-size: 1.1em;
        }

        /* Startup details modal enhancements */
        .startup-detail-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 25px;
        }

        .startup-name {
            color: #333;
            margin-bottom: 15px;
        }

        .startup-description {
            color: #666;
            line-height: 1.6;
        }

        .rating-count {
            color: #666;
            margin-left: 10px;
        }

        /* Alert animations */
        .alert {
            animation: slideInDown 0.5s ease;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1051;
            min-width: 300px;
            text-align: center;
        }

        @keyframes slideInDown {
            from {
                transform: translate(-50%, -100%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>