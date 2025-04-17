<?php
require_once '../../Controller/EvenementController.php';

// Initialiser les variables de message
$message = null;
$messageType = null;

// Vérifier si on doit supprimer un événement
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $evenementController = new EvenementController();
    if ($evenementController->supprimerEvenement($id)) {
        $message = "L'événement a été supprimé avec succès.";
        $messageType = "success";
    } else {
        $message = "Erreur lors de la suppression de l'événement.";
        $messageType = "danger";
    }
}

// Vérifier si un message a été passé dans l'URL
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = urldecode($_GET['message']);
    $messageType = $_GET['type'];
}

$evenementController = new EvenementController();
$evenements = $evenementController->afficherEvenements();

// Gestion de la recherche
$searchTerm = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    $evenements = $evenementController->rechercherEvenements($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Gestion des Événements - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="gestion événements, administration, startups" name="keywords">
    <meta content="Panel d'administration pour la gestion des événements" name="description">

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
        
        .dashboard-stats {
            margin-bottom: 30px;
        }
        
        .event-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .event-card-header {
            background-color: #06A3DA;
            color: white;
            padding: 15px 20px;
            position: relative;
        }
        
        .event-card-body {
            padding: 20px;
        }
        
        .event-title {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .event-info {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .event-info i {
            color: #06A3DA;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .event-actions {
            margin-top: 15px;
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
        
        .btn-add-event {
            background-color: #06A3DA;
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-add-event:hover {
            background-color: #0583AE;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(6, 163, 218, 0.3);
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
        
        .search-btn:hover {
            background-color: #0583AE;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
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
        
        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #06A3DA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .stats-icon i {
            font-size: 24px;
            color: white;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #091E3E;
            margin-bottom: 5px;
        }
        
        .stats-title {
            color: #6c757d;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .event-actions {
                flex-wrap: wrap;
            }
            
            .btn-action {
                width: 100%;
                margin-bottom: 10px;
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
                <h1 class="display-4 text-white animated zoomIn">Gestion des Événements</h1>
                <a href="dashboard.html" class="h5 text-white">Dashboard</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Événements</span>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Dashboard Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="mb-3 mb-lg-0"><i class="fas fa-calendar-alt me-3"></i>Administration des Événements</h2>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <a href="AjouterEvenement.php" class="btn btn-add-event">
                            <i class="fas fa-plus-circle"></i>
                            Ajouter un nouvel événement
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="dashboard-stats">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stats-number"><?= count($evenements) ?></div>
                            <div class="stats-title">Total des événements</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <?php 
                            $evenementsAVenir = $evenementController->getEvenementsAVenir(); 
                            $totalEvenementsAVenir = count($evenementsAVenir);
                            ?>
                            <div class="stats-number"><?= $totalEvenementsAVenir ?></div>
                            <div class="stats-title">Événements à venir</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <?php
                            require_once '../../Controller/ReservationController.php';
                            $reservationController = new ReservationController();
                            $totalReservations = $reservationController->getTotalReservations();
                            ?>
                            <div class="stats-number"><?= $totalReservations ?></div>
                            <div class="stats-title">Réservations totales</div>
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
            <div class="search-container">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control search-box" placeholder="Rechercher un événement..." name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn search-btn w-100">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Events List -->
            <div class="row">
                <?php if (empty($evenements)): ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h3>Aucun événement trouvé</h3>
                            <p>Il n'y a actuellement aucun événement dans le système<?= !empty($searchTerm) ? ' correspondant à votre recherche.' : '.' ?></p>
                            <?php if (!empty($searchTerm)): ?>
                                <a href="GestionEvenements.php" class="btn btn-primary mt-3">Voir tous les événements</a>
                            <?php else: ?>
                                <a href="AjouterEvenement.php" class="btn btn-primary mt-3">Créer votre premier événement</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Liste des événements</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Nom</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Lieu</th>
                                                <th scope="col">Organisateur</th>
                                                <th scope="col">Réservations</th>
                                                <th scope="col">Statut</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $today = date('Y-m-d');
                                            foreach ($evenements as $evenement): 
                                                $reservationsCount = count($reservationController->getReservationsParEvenement($evenement['id_event']));
                                                $isPassed = $evenement['date_event'] < $today;
                                            ?>
                                            <tr>
                                                <td><?= $evenement['id_event'] ?></td>
                                                <td><strong><?= htmlspecialchars($evenement['nom_event']) ?></strong></td>
                                                <td><?= date('d/m/Y', strtotime($evenement['date_event'])) ?></td>
                                                <td><?= htmlspecialchars($evenement['lieu']) ?></td>
                                                <td><?= htmlspecialchars($evenement['organisateur']) ?></td>
                                                <td><span class="badge bg-info"><?= $reservationsCount ?></span></td>
                                                <td>
                                                    <?php if ($isPassed): ?>
                                                        <span class="badge bg-secondary">Passé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">À venir</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="ModifierEvenement.php?id=<?= $evenement['id_event'] ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="VoirReservations.php?event_id=<?= $evenement['id_event'] ?>" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Voir les réservations">
                                                            <i class="fas fa-ticket-alt"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $evenement['id_event'] ?>" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-toggle="tooltip" title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Styles additionnels pour le tableau -->
            <style>
                .table-responsive {
                    overflow-x: auto;
                }
                
                .table th, .table td {
                    vertical-align: middle;
                }
                
                .table-hover tbody tr:hover {
                    background-color: rgba(6, 163, 218, 0.05);
                }
                
                .badge {
                    font-size: 0.85rem;
                    padding: 0.35em 0.65em;
                }
                
                .btn-group .btn {
                    margin: 0 2px;
                }
                
                .card {
                    border-radius: 10px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                
                .card-header {
                    padding: 15px 20px;
                }
            </style>
            
            <!-- Initialisation des tooltips -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialisation des tooltips Bootstrap
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                });
            </script>
        </div>
    </div>
    <!-- Dashboard End -->

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
                    <p>Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible et supprimera également toutes les réservations associées.</p>
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
                document.getElementById('deleteLink').href = `GestionEvenements.php?action=delete&id=${id}`;
            });
        });
        
        // Initialisation du compteur
        $('.counter-value').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    </script>
</body>

</html>