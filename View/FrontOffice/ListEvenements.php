<?php
require_once '../../Controller/EvenementController.php';

// Initialiser le contrôleur
$evenementController = new EvenementController();

// Récupérer tous les événements (sans filtrage par date)
$evenements = $evenementController->afficherEvenements();

// Gestion de la recherche
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $evenements = $evenementController->rechercherEvenements($searchTerm);
}

// Gestion du tri
$critere = isset($_GET['critere']) ? $_GET['critere'] : 'date_event';
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : 'ASC';

if (isset($_GET['tri'])) {
    $evenements = $evenementController->trierEvenements($critere, $ordre);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Événements - StartUp Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="événements, startups, networking" name="keywords">
    <meta content="Découvrez les événements à venir pour les startups" name="description">

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
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            background-color: #fff;
            margin-bottom: 30px;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(6, 163, 218, 0.15);
        }
        
        .event-image {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .event-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }
        
        .event-card:hover .event-img {
            transform: scale(1.1);
        }
        
        .event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(9, 30, 62, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .event-card:hover .event-overlay {
            opacity: 1;
        }
        
        .btn-view {
            width: 50px;
            height: 50px;
            background: #06A3DA;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .event-card:hover .btn-view {
            transform: translateY(0);
            opacity: 1;
        }
        
        .btn-view:hover {
            background: white;
            color: #06A3DA;
        }
        
        .event-date-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: rgba(6, 163, 218, 0.9);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 700;
            z-index: 2;
            font-size: 0.9rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(3px);
        }
        
        .event-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .event-title {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #091E3E;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 2.8em;
        }
        
        .event-info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .event-info {
            color: #6c757d;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        
        .event-info i {
            color: #06A3DA;
            width: 22px;
            text-align: center;
            margin-right: 10px;
            font-size: 1rem;
        }
        
        .event-action {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }
        
        .btn-outline-primary, .btn-primary {
            border-radius: 50px;
            padding: 10px 15px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex: 1;
        }
        
        .btn-outline-primary {
            border: 2px solid #06A3DA;
            color: #06A3DA;
        }
        
        .btn-outline-primary:hover {
            background-color: #06A3DA;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(6, 163, 218, 0.25);
        }
        
        .btn-primary {
            background-color: #06A3DA;
            border: 2px solid #06A3DA;
        }
        
        .btn-primary:hover {
            background-color: #0583ae;
            border-color: #0583ae;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(6, 163, 218, 0.3);
        }
        
        .search-container {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .search-input {
            border-radius: 50px;
            padding: 15px 25px;
            border: 2px solid #06A3DA;
            font-size: 1rem;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(6, 163, 218, 0.25);
        }
        
        .search-btn {
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 1rem;
            background-color: #06A3DA;
            box-shadow: 0 5px 15px rgba(6, 163, 218, 0.2);
        }
        
        .search-btn:hover {
            background-color: #0583ae;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 163, 218, 0.3);
        }
        
        .form-select {
            border-radius: 50px;
            padding: 15px 25px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            height: auto;
            background-position: right 15px center;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 0 0.25rem rgba(6, 163, 218, 0.25);
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 0;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }
        
        .empty-state i {
            font-size: 6rem;
            color: #06A3DA;
            margin-bottom: 30px;
            opacity: 0.6;
        }
        
        .empty-state h4 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #091E3E;
            margin-bottom: 20px;
        }
        
        .empty-state p {
            color: #6c757d;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        
        .empty-state .btn {
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(6, 163, 218, 0.2);
            transition: all 0.3s ease;
        }
        
        .empty-state .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(6, 163, 218, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .event-card {
                margin-bottom: 30px;
            }
            
            .event-image {
                height: 180px;
            }
            
            .event-content {
                padding: 20px;
            }
            
            .event-title {
                font-size: 1.2rem;
            }
            
            .event-action {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-outline-primary, .btn-primary {
                width: 100%;
                justify-content: center;
                padding: 10px 15px;
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
                    <a href="ListEvenements.php" class="nav-item nav-link active">Événements</a>
                    <a href="ListReservations.php" class="nav-item nav-link">Mes Réservations</a>
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
                <h1 class="display-4 text-white animated zoomIn">Événements</h1>
                <a href="" class="h5 text-white">Découvrez nos événements pour les startups</a>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Événements Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Nos événements</h5>
                <h1 class="mb-0">Rencontrez des startups, investisseurs et experts</h1>
            </div>

            <!-- Search Bar -->
            <div class="search-container wow fadeInUp" data-wow-delay="0.2s">
                <form action="" method="GET" class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" name="search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($searchTerm) ?>">
                            <button type="submit" class="btn btn-primary search-btn">
                                <i class="fa fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="critere" class="form-select">
                            <option value="date_event" <?= $critere == 'date_event' ? 'selected' : '' ?>>Date</option>
                            <option value="nom_event" <?= $critere == 'nom_event' ? 'selected' : '' ?>>Nom</option>
                            <option value="lieu" <?= $critere == 'lieu' ? 'selected' : '' ?>>Lieu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex">
                            <select name="ordre" class="form-select">
                                <option value="ASC" <?= $ordre == 'ASC' ? 'selected' : '' ?>>Croissant</option>
                                <option value="DESC" <?= $ordre == 'DESC' ? 'selected' : '' ?>>Décroissant</option>
                            </select>
                            <button type="submit" name="tri" value="1" class="btn btn-primary ms-2">
                                <i class="fa fa-sort"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Événements Cards -->
            <div class="row g-4">
                <?php if (empty($evenements)): ?>
                    <div class="col-12 empty-state wow fadeInUp" data-wow-delay="0.3s">
                        <i class="fas fa-calendar-times"></i>
                        <h4>Aucun événement trouvé</h4>
                        <?php if (!empty($searchTerm)): ?>
                            <p>Aucun résultat pour "<?= htmlspecialchars($searchTerm) ?>". Essayez une autre recherche.</p>
                            <a href="ListEvenements.php" class="btn btn-primary mt-3">Voir tous les événements</a>
                        <?php else: ?>
                            <p>Il n'y a pas d'événements à venir pour le moment. Revenez bientôt!</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($evenements as $index => $evenement): ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= 0.1 + ($index % 3) * 0.2 ?>s">
                            <div class="event-card">
                                <div class="event-image">
                                    <div class="event-date-badge">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?= date('d/m/Y', strtotime($evenement['date_event'])) ?>
                                    </div>
                                    <?php
                                    // Tableau d'images d'événements par défaut pour diversifier
                                    $defaultImages = [
                                        '../../img/blog-1.jpg',
                                        '../../img/blog-2.jpg',
                                        '../../img/blog-3.jpg',
                                      
                                    ];
                                    // Utiliser l'ID de l'événement pour sélectionner une image (cycle entre les 5 images)
                                    $imageIndex = $evenement['id_event'] % count($defaultImages);
                                    $eventImage = $defaultImages[$imageIndex];
                                    ?>
                                    <img src="<?= $eventImage ?>" alt="<?= htmlspecialchars($evenement['nom_event']) ?>" class="event-img">
                                    <div class="event-overlay">
                                        <a href="DetailEvenement.php?id=<?= $evenement['id_event'] ?>" class="btn-view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="event-content">
                                    <h3 class="event-title"><?= htmlspecialchars($evenement['nom_event']) ?></h3>
                                    <div class="event-info-list">
                                        <div class="event-info">
                                            <i class="far fa-calendar-alt"></i> 
                                            <span><?= date('d/m/Y', strtotime($evenement['date_event'])) ?></span>
                                        </div>
                                        <div class="event-info">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <span><?= isset($evenement['lieu']) && !empty($evenement['lieu']) ? htmlspecialchars($evenement['lieu']) : 'Lieu à confirmer' ?></span>
                                        </div>
                                        <div class="event-info">
                                            <i class="fas fa-user-tie"></i> 
                                            <span><?= isset($evenement['organisateur']) && !empty($evenement['organisateur']) ? htmlspecialchars($evenement['organisateur']) : 'Organisateur non spécifié' ?></span>
                                        </div>
                                    </div>
                                    <div class="event-action mt-3">
                                        <a href="DetailEvenement.php?id=<?= $evenement['id_event'] ?>" class="btn btn-outline-primary">
                                            <i class="far fa-eye"></i> Détails
                                        </a>
                                        <a href="AjouterReservation.php?id=<?= $evenement['id_event'] ?>" class="btn btn-primary">
                                            <i class="fas fa-ticket-alt"></i> Réserver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Événements End -->

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
    </script>
</body>

</html> 