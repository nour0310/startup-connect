<?php
require_once '../../Controller/EvenementController.php';
require_once '../../Controller/ReservationController.php';

// Initialiser les contrôleurs
$evenementController = new EvenementController();
$reservationController = new ReservationController();


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ListEvenements.php');
    exit();
}

$id_event = $_GET['id'];


$evenement = $evenementController->getEvenementById($id_event);
    
    // si existe
    if (!$evenement) {
    header('Location: ListEvenements.php');
    exit();
}


$placesReservees = $reservationController->getTotalPlacesReservees($id_event);


$placesMax = 100;
$placesDisponibles = $placesMax - $placesReservees;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($evenement['nom_event']) ?> - StartUp Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="événements, startups, networking, <?= htmlspecialchars($evenement['nom_event']) ?>" name="keywords">
    <meta content="Détails de l'événement <?= htmlspecialchars($evenement['nom_event']) ?>" name="description">

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
        .event-detail-container {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }
        
        .event-image {
            position: relative;
            height: 400px;
            overflow: hidden;
        }
        
        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .event-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.6));
        }
        
        .event-date-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #06A3DA;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 2;
        }
        
        .event-date-badge .day {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .event-date-badge .month-year {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .event-content {
            padding: 40px;
        }
        
        .event-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #091E3E;
            margin-bottom: 20px;
        }
        
        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        
        .event-meta-item {
            display: flex;
            align-items: center;
            color: #6c757d;
        }
        
        .event-meta-item i {
            color: #06A3DA;
            font-size: 1.25rem;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .event-description {
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .reservation-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
            position: relative;
            border: 1px solid #e9ecef;
        }
        
        .reservation-box h3 {
            color: #091E3E;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .places-info {
            background-color: #06A3DA;
            color: white;
            border-radius: 5px;
            padding: 10px 15px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .btn-reserve {
            background-color: #06A3DA;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-reserve:hover {
            background-color: #0689DA;
            box-shadow: 0 10px 20px rgba(6, 163, 218, 0.3);
            transform: translateY(-3px);
        }
        
        .btn-reserve i {
            font-size: 1.2rem;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #06A3DA;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            color: #091E3E;
            text-decoration: none;
            transform: translateX(-5px);
        }
        
        .back-btn i {
            font-size: 0.9rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .event-image {
                height: 250px;
            }
            
            .event-title {
                font-size: 2rem;
            }
            
            .event-content {
                padding: 25px;
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
                <h1 class="display-4 text-white animated zoomIn">Détails de l'événement</h1>
                <a href="ListEvenements.php" class="h5 text-white">Retour aux événements</a>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Détails de l'événement Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <a href="ListEvenements.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Retour à la liste des événements
                    </a>
                </div>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="event-detail-container wow fadeInUp" data-wow-delay="0.3s">
                        <div class="event-image">
                            <?php
                            // Tableau d'images d'événements par défaut pour diversifier
                            $defaultImages = [
                                '../../img/blog-1.jpg',
                                '../../img/blog-2.jpg',
                                '../../img/blog-3.jpg'
                            ];
                            // Utiliser l'ID de l'événement pour sélectionner une image (cycle entre les 3 images)
                            $imageIndex = $evenement['id_event'] % count($defaultImages);
                            $eventImage = $defaultImages[$imageIndex];
                            
                            // Formater la date pour l'affichage badge
                            $dateObj = new DateTime($evenement['date_event']);
                            $day = $dateObj->format('d');
                            $monthYear = $dateObj->format('M Y');
                            ?>
                            <div class="event-date-badge">
                                <div class="day"><?= $day ?></div>
                                <div class="month-year"><?= $monthYear ?></div>
                            </div>
                            <img src="<?= $eventImage ?>" alt="<?= htmlspecialchars($evenement['nom_event']) ?>">
                            <div class="event-image-overlay"></div>
                        </div>
                        
                        <div class="event-content">
                            <h1 class="event-title"><?= htmlspecialchars($evenement['nom_event']) ?></h1>
                            
                            <div class="event-meta">
                                <div class="event-meta-item">
                                    <i class="far fa-calendar-alt"></i>
                                    <span><?= date('d F Y', strtotime($evenement['date_event'])) ?></span>
                                </div>
                                
                                <?php if (isset($evenement['lieu']) && !empty($evenement['lieu'])): ?>
                                <div class="event-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($evenement['lieu']) ?></span>
                                </div>
                                <?php endif; ?>
                    
                                <?php if (isset($evenement['organisateur']) && !empty($evenement['organisateur'])): ?>
                                <div class="event-meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Organisé par <?= htmlspecialchars($evenement['organisateur']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="event-description">
                                <p>Rejoignez-nous pour cet événement exceptionnel organisé par <?= htmlspecialchars($evenement['organisateur']) ?> à <?= htmlspecialchars($evenement['lieu']) ?>.</p>
                                <p>Cet événement vous permettra de rencontrer d'autres entrepreneurs, investisseurs et experts du domaine des startups.</p>
                                <p>Ne manquez pas cette opportunité unique de développer votre réseau professionnel et de découvrir de nouvelles opportunités!</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="reservation-box wow fadeInUp" data-wow-delay="0.5s">
                        <h3>Réserver votre place</h3>
                        
                        <div class="places-info">
                            <i class="fas fa-ticket-alt me-2"></i>
                            <?= $placesDisponibles ?> places disponibles
                        </div>
                        
                        <p>Ne manquez pas cette opportunité unique de participer à cet événement exceptionnel! Réservez dès maintenant pour garantir votre place.</p>
                        <p><small><i class="fas fa-info-circle text-primary"></i> Maximum 8 places par réservation.</small></p>
                        
                        <?php if ($placesDisponibles > 0): ?>
                            <a href="AjouterReservation.php?id=<?= $evenement['id_event'] ?>" class="btn btn-reserve">
                                <i class="fas fa-ticket-alt"></i>
                                Réserver maintenant
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-times-circle"></i>
                                Complet
                            </button>
                            <p class="mt-2 text-danger">Toutes les places ont été réservées pour cet événement.</p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Détails de l'événement End -->

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