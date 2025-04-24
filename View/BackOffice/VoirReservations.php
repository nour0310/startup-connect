<?php
require_once '../../Controller/ReservationController.php';
require_once '../../Controller/EvenementController.php';

// Vérifier si l'ID de l'événement est fourni
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    header('Location: GestionEvenements.php');
    exit();
}

$event_id = (int)$_GET['event_id'];

// Initialiser les contrôleurs
$reservationController = new ReservationController();
$evenementController = new EvenementController();

// Récupérer les détails de l'événement
$evenement = $evenementController->getEvenementById($event_id);

if (!$evenement) {
    header('Location: GestionEvenements.php');
    exit();
}

$message = '';
$messageType = '';

// Vérifier si un message a été passé dans l'URL
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = urldecode($_GET['message']);
    $messageType = $_GET['type'];
}

// Action de suppression de réservation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $success = $reservationController->supprimerReservation($id);
    $message = $success ? "Réservation supprimée avec succès." : "Erreur lors de la suppression de la réservation.";
    $messageType = $success ? "success" : "danger";
}

// Récupérer les réservations pour cet événement
$reservations = $reservationController->getReservationsParEvenement($event_id);

// Gestion de la recherche
$searchTerm = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    // Filtrer les réservations par recherche
    $reservations = array_filter($reservations, function($reservation) use ($searchTerm) {
        return (
            stripos($reservation['nom_client'], $searchTerm) !== false ||
            stripos($reservation['email'], $searchTerm) !== false
        );
    });
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Réservations pour <?= htmlspecialchars($evenement['nom_event']) ?> - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="gestion réservations, administration, événements" name="keywords">
    <meta content="Panel d'administration pour gérer les réservations d'un événement" name="description">

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
        .dashboard-header {
            background-color: #091E3E;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .event-details-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .event-details-header {
            background-color: #06A3DA;
            color: white;
            padding: 20px;
        }
        
        .event-details-body {
            padding: 20px;
        }
        
        .event-info-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .event-info-item i {
            color: #06A3DA;
            font-size: 1.2rem;
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }
        
        .event-info-label {
            font-weight: 600;
            color: #091E3E;
            margin-right: 10px;
        }
        
        .stats-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 700;
            color: white;
            background-color: #06A3DA;
            margin-top: 10px;
        }
        
        .reservation-table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .reservation-table thead th {
            background-color: #091E3E;
            color: white;
            padding: 15px;
            font-weight: 600;
            border: none;
        }
        
        .reservation-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f5f5f5;
        }
        
        .reservation-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .reservation-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-action {
            border-radius: 50px;
            font-weight: 600;
            padding: 8px 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .btn-action i {
            margin-right: 5px;
        }
        
        .btn-add-reservation {
            background-color: #06A3DA;
            color: white;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 700;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-add-reservation:hover {
            background-color: #0583AE;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(6, 163, 218, 0.3);
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #06A3DA;
            text-decoration: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            color: #091E3E;
            transform: translateX(-5px);
        }
        
        .search-container {
            margin-bottom: 30px;
        }
        
        .search-box {
            border-radius: 50px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 10px rgba(6, 163, 218, 0.2);
        }
        
        .search-btn {
            border-radius: 50px;
            background-color: #06A3DA;
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .badge-custom {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .modal-confirm .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #f15e5e;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-confirm .icon-box i {
            font-size: 46px;
            color: white;
        }
        
        .modal-confirm h4 {
            font-size: 26px;
            margin: 30px 0 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-action {
                width: 100%;
                margin-bottom: 5px;
            }
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
                    <!-- Social media links can go here -->
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
                    <a href="dashboard.html" class="nav-item nav-link">Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Gestion</a>
                        <div class="dropdown-menu m-0">
                            <a href="GestionUtilisateurs.html" class="dropdown-item">Utilisateurs</a>
                            <a href="GestionStartups.html" class="dropdown-item">Startups</a>
                            <a href="GestionEvenements.php" class="dropdown-item active">Événements</a>
                            <a href="GestionReservations.php" class="dropdown-item">Réservations</a>
                            <a href="GestionInvestissements.html" class="dropdown-item">Investissements</a>
                        </div>
                    </div>
                    <a href="../FrontOffice/login.html" class="nav-item nav-link">Déconnexion</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Gestion des Réservations</h1>
                <a href="GestionEvenements.php" class="h5 text-white">Événements</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Réservations</span>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Réservations Section Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <a href="GestionEvenements.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste des événements
                    </a>
                </div>
            </div>
            
            <div class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="mb-3 mb-lg-0"><i class="fas fa-ticket-alt me-3"></i>Réservations pour l'événement</h2>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <a href="AjouterReservationAdmin.php?event_id=<?= $event_id ?>" class="btn btn-add-reservation">
                            <i class="fas fa-plus-circle"></i>
                            Ajouter une réservation
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Event Details Card -->
            <div class="event-details-card wow fadeInUp" data-wow-delay="0.1s">
                <div class="event-details-header">
                    <h3 class="mb-0"><?= htmlspecialchars($evenement['nom_event']) ?></h3>
                </div>
                <div class="event-details-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="event-info-item">
                                <i class="far fa-calendar-alt"></i>
                                <span class="event-info-label">Date:</span>
                                <span><?= date('d/m/Y', strtotime($evenement['date_event'])) ?></span>
                            </div>
                            
                            <?php if (!empty($evenement['lieu'])): ?>
                            <div class="event-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="event-info-label">Lieu:</span>
                                <span><?= htmlspecialchars($evenement['lieu']) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($evenement['organisateur'])): ?>
                            <div class="event-info-item">
                                <i class="fas fa-user-tie"></i>
                                <span class="event-info-label">Organisateur:</span>
                                <span><?= htmlspecialchars($evenement['organisateur']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            // Calculer les statistiques de l'événement
                            $totalReservations = count($reservations);
                            $totalPlaces = 0;
                            
                            foreach ($reservations as $reservation) {
                                $totalPlaces += $reservation['nb_places'];
                            }
                            
                            // Supposons un maximum de 100 places
                            $placesMax = 100;
                            $placesRestantes = $placesMax - $totalPlaces;
                            $pourcentageRemplissage = ($totalPlaces / $placesMax) * 100;
                            ?>
                            
                            <div class="stats-box">
                                <div class="event-info-item">
                                    <i class="fas fa-users"></i>
                                    <span class="event-info-label">Total des réservations:</span>
                                    <span><?= $totalReservations ?></span>
                                </div>
                                
                                <div class="event-info-item">
                                    <i class="fas fa-chair"></i>
                                    <span class="event-info-label">Places réservées:</span>
                                    <span><?= $totalPlaces ?> sur <?= $placesMax ?></span>
                                </div>
                                
                                <div class="event-info-item">
                                    <i class="fas fa-chart-pie"></i>
                                    <span class="event-info-label">Taux de remplissage:</span>
                                    <span><?= round($pourcentageRemplissage) ?>%</span>
                                </div>
                                
                                <div class="stats-badge">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    <?= $placesRestantes ?> places encore disponibles
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert for messages -->
            <?php if(isset($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <i class="<?= $messageType == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle' ?> me-2"></i>
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Search Bar -->
            <div class="search-container wow fadeInUp" data-wow-delay="0.2s">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control search-box" placeholder="Rechercher par nom ou email..." name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn search-btn w-100">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Reservations Table -->
            <?php if (empty($reservations)): ?>
                <div class="empty-state wow fadeInUp" data-wow-delay="0.3s">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>Aucune réservation trouvée</h3>
                    <p>Cet événement n'a pas encore de réservations<?= !empty($searchTerm) ? ' correspondant à votre recherche.' : '.' ?></p>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="btn btn-primary mt-3">Voir toutes les réservations</a>
                    <?php else: ?>
                        <a href="AjouterReservationAdmin.php?event_id=<?= $event_id ?>" class="btn btn-primary mt-3">Ajouter une réservation</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="reservation-table wow fadeInUp" data-wow-delay="0.3s">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date de réservation</th>
                                <th>Places</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= $reservation['id_reservation'] ?></td>
                                    <td><?= htmlspecialchars($reservation['nom_client']) ?></td>
                                    <td><?= htmlspecialchars($reservation['email']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?></td>
                                    <td>
                                        <span class="badge badge-custom bg-primary">
                                            <?= $reservation['nb_places'] ?> place<?= $reservation['nb_places'] > 1 ? 's' : '' ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="ModifierReservationAdmin.php?id=<?= $reservation['id_reservation'] ?>&event_id=<?= $event_id ?>" class="btn btn-outline-primary btn-action">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-action delete-btn" data-id="<?= $reservation['id_reservation'] ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash-alt"></i> Supprimer
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
    <!-- Réservations Section End -->

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h4 class="modal-title">Confirmation de suppression</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="deleteLink" class="btn btn-danger px-4">Supprimer</a>
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
        
        // Fonction pour la confirmation de suppression
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('deleteLink').href = `VoirReservations.php?event_id=<?= $event_id ?>&action=delete&id=${id}`;
            });
        });
    </script>
</body>

</html>