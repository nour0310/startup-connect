<?php
require_once '../../Controller/ReservationController.php';
require_once '../../Controller/EvenementController.php';

// Initialiser les contrôleurs
$reservationController = new ReservationController();
$evenementController = new EvenementController();

// Gestion des recherches
$searchTerm = '';
$reservations = [];

// Action de suppression
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $success = $reservationController->supprimerReservation($id);
    $message = $success ? "Réservation supprimée avec succès." : "Erreur lors de la suppression de la réservation.";
    $messageType = $success ? "success" : "danger";
}

// Vérifie si un email est fourni pour la recherche
if (isset($_GET['email']) && !empty($_GET['email'])) {
    $email = trim($_GET['email']);
    $reservations = $reservationController->getReservationsParEmail($email);
    $searchTerm = $email;
} else if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    $reservations = $reservationController->rechercherReservations($searchTerm);
} else {
    // Charger toutes les réservations si aucune recherche
    $reservations = $reservationController->afficherReservations();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Mes Réservations - StartUp Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="réservations, événements, startups" name="keywords">
    <meta content="Gérez vos réservations aux événements" name="description">

    <!-- Favicon -->
    <link href="../../img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../css/style.css" rel="stylesheet">
    
    <style>
        .reservation-table {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        
        .reservation-table .table {
            margin-bottom: 0;
        }
        
        .reservation-table thead {
            background-color: #06A3DA;
            color: white;
        }
        
        .reservation-table th {
            padding: 18px 15px;
            font-weight: 700;
            border: none;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        
        .reservation-table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f5f5f5;
            font-size: 1rem;
        }
        
        .reservation-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .reservation-table tbody tr:hover {
            background-color: rgba(6, 163, 218, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .reservation-table .event-title {
            font-weight: 700;
            color: #091E3E;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .reservation-table .event-date {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .reservation-table .event-info {
            display: flex;
            flex-direction: column;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-sm {
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .btn-sm:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline-primary {
            border: 2px solid #06A3DA;
            color: #06A3DA;
        }
        
        .btn-outline-primary:hover {
            background-color: #06A3DA;
            color: white;
        }
        
        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .search-container {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }
        
        .search-input {
            border-radius: 50px;
            padding: 10px 20px;
            border: 1px solid #06A3DA;
        }
        
        .search-btn {
            border-radius: 50px;
            padding: 10px 25px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 0;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
        }
        
        .modal-confirm {
            color: #636363;
        }
        
        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
        }
        
        .modal-confirm .modal-header {
            border-bottom: none;
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            border-radius: 5px 5px 0 0;
            padding: 35px;
        }
        
        .modal-confirm h4 {
            text-align: center;
            font-size: 36px;
            margin: 10px 0;
        }
        
        .modal-confirm .form-control, .modal-confirm .btn {
            min-height: 40px;
            border-radius: 3px; 
        }
        
        .modal-confirm .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #fff;
            opacity: 1;
        }
        
        .modal-confirm .modal-footer {
            border: none;
            text-align: center;
            border-radius: 5px;
            font-size: 13px;
        }
        
        .modal-confirm .icon-box {
            color: #fff;
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: -70px;
            width: 95px;
            height: 95px;
            border-radius: 50%;
            z-index: 9;
            padding: 15px;
            text-align: center;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
        }
        
        .modal-confirm .icon-box i {
            font-size: 58px;
            position: relative;
            top: 3px;
        }
        
        .modal-confirm.modal-dialog {
            margin-top: 80px;
        }
        
        .modal-confirm .btn {
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border: none;
        }
        
        .modal-confirm-delete .icon-box {
            background: #ef513a;
        }
        
        .modal-confirm-delete .btn-danger {
            background: #ef513a;
        }
        
        .modal-confirm-delete .btn-danger:hover, .modal-confirm-delete .btn-danger:focus {
            background: #da2c12;
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->

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
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="background-color: #06A3DA;">
            <a href="../../index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>StartUp Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="../../index.html" class="nav-item nav-link">Accueil</a>
                    <a href="startupList.html" class="nav-item nav-link">Startups</a>
                    <a href="ListEvenements.php" class="nav-item nav-link">Événements</a>
                    <a href="ListReservations.php" class="nav-item nav-link active">Mes Réservations</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Administration</a>
                        <div class="dropdown-menu m-0">
                            <a href="../BackOffice/dashboard.html" class="dropdown-item">Dashboard</a>
                            <a href="../BackOffice/GestionEvenements.php" class="dropdown-item">Gestion événements</a>
                            <a href="../BackOffice/GestionReservations.php" class="dropdown-item">Gestion réservations</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Mes Réservations</h1>
                <a href="" class="h5 text-white">Gérez vos réservations aux événements</a>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Réservations Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Mes Réservations</h5>
                <h1 class="mb-0">Suivez et gérez vos participations aux événements</h1>
            </div>

            <!-- Alert pour les messages -->
            <?php if(isset($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <i class="<?= $messageType == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle' ?> me-2"></i>
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <!-- Search Bar -->
            <div class="search-container wow fadeInUp" data-wow-delay="0.2s">
                <form action="" method="POST" class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" name="search" placeholder="Rechercher une réservation par nom ou email..." value="<?= htmlspecialchars($searchTerm) ?>">
                            <button type="submit" class="btn btn-primary search-btn">
                                <i class="fa fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Réservations Table -->
            <div class="row">
                <div class="col-12">
                    <?php if (empty($reservations)): ?>
                        <div class="empty-state wow fadeInUp" data-wow-delay="0.3s">
                            <i class="fas fa-ticket-alt"></i>
                            <h4>Aucune réservation trouvée</h4>
                            <?php if (!empty($searchTerm)): ?>
                                <p>Aucun résultat pour "<?= htmlspecialchars($searchTerm) ?>". Essayez une autre recherche.</p>
                                <a href="ListReservations.php" class="btn btn-primary mt-3">Voir toutes mes réservations</a>
                            <?php else: ?>
                                <p>Vous n'avez pas encore de réservations. Découvrez nos événements et réservez votre place!</p>
                                <a href="ListEvenements.php" class="btn btn-primary mt-3">Voir les événements</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="reservation-table wow fadeInUp" data-wow-delay="0.3s">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Événement</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Nombre de places</th>
                                        <th>Date de réservation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td>
                                                <div class="event-info">
                                                    <span class="event-title"><?= htmlspecialchars($reservation['nom_event']) ?></span>
                                                    <span class="event-date">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        <?= date('d/m/Y', strtotime($reservation['date_event'])) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($reservation['nom_client']) ?></td>
                                            <td><?= htmlspecialchars($reservation['email']) ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= $reservation['nb_places'] ?> place<?= $reservation['nb_places'] > 1 ? 's' : '' ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?></td>
                                            <td class="action-buttons">
                                                <a href="ModifierReservation.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Éditer
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="<?= $reservation['id_reservation'] ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="fas fa-trash-alt"></i> Annuler
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Réservations End -->

    <!-- Delete Modal -->
    <div class="modal fade modal-confirm modal-confirm-delete" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box bg-danger">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h4 class="modal-title">Confirmation de suppression</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="deleteLink" class="btn btn-danger">Supprimer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Contactez-nous</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Rue Tunis, Tunisie, TN</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">startupconnect@gmail.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+216 29 999 999</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../lib/wow/wow.min.js"></script>
    <script src="../../lib/easing/easing.min.js"></script>
    <script src="../../lib/waypoints/waypoints.min.js"></script>
    <script src="../../lib/counterup/counterup.min.js"></script>
    <script src="../../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../../js/main.js"></script>
    
    <script>
        // Initialisation des animations WOW
        new WOW().init();
        
        // Gestion de la suppression
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('deleteLink').href = `ListReservations.php?action=delete&id=${id}`;
            });
        });
    </script>
</body>

</html> 