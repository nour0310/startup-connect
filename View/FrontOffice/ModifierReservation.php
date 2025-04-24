<?php
require_once '../../Controller/ReservationController.php';
require_once '../../Controller/EvenementController.php';

// Initialiser les contrôleurs
$reservationController = new ReservationController();
$evenementController = new EvenementController();

// Vérification que l'ID est bien passé et que la réservation existe
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ListReservations.php');
    exit();
}

$id_reservation = (int)$_GET['id'];
$reservation = $reservationController->getReservationById($id_reservation);

if (!$reservation) {
    header('Location: ListReservations.php?error=notfound');
    exit();
}

// Récupération des détails de l'événement
$evenement = $evenementController->getEvenementById($reservation['id_event']);

// Calculer le nombre de places disponibles
$placesReservees = $reservationController->getTotalPlacesReservees($reservation['id_event']);
$placesMax = 100;
$placesDisponibles = $placesMax - $placesReservees + $reservation['nb_places']; // Ajouter les places déjà réservées par cette réservation

// Traitement du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $nom_client = isset($_POST['nom_client']) ? trim($_POST['nom_client']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nbPlaces = isset($_POST['nb_places']) ? (int)$_POST['nb_places'] : 1;
    
    // Validation nom
    if (empty($nom_client)) {
        $errors['nom_client'] = "Le nom est obligatoire";
    } elseif (strlen($nom_client) > 100) {
        $errors['nom_client'] = "Le nom ne peut pas dépasser 100 caractères";
    }
    
    // Validation email
    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide";
    }
    
    // Validation nombre de places
    if ($nbPlaces <= 0) {
        $errors['nb_places'] = "Le nombre de places doit être supérieur à 0";
    } elseif ($nbPlaces > $placesDisponibles) {
        $errors['nb_places'] = "Il ne reste que $placesDisponibles places disponibles";
    }
    
    // Si aucune erreur, procéder à la mise à jour
    if (empty($errors)) {
        // Création d'un objet Reservation avec les nouvelles données
        $reservationObj = new Reservation(
            $reservation['id_event'],
            $nom_client,
            $email,
            $reservation['date_reservation'],
            $nbPlaces,
            $id_reservation
        );
        
        // Appel à la méthode de mise à jour
        $success = $reservationController->modifierReservation($reservationObj);
        
        if ($success) {
            // Redirection après 2 secondes vers la liste des réservations
            header("Refresh: 2; URL=ListReservations.php");
        } else {
            $errors['general'] = "Une erreur est survenue lors de la mise à jour de la réservation";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier la réservation - StartUp Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="modification, réservation, événement" name="keywords">
    <meta content="Modifier les détails de votre réservation" name="description">

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
        .reservation-form-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .form-header {
            background-color: #091E3E;
            color: #fff;
            padding: 25px;
            position: relative;
        }
        
        .event-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #06A3DA;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #091E3E;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 10px rgba(6, 163, 218, 0.1);
        }
        
        .btn-submit {
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
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background-color: #0689DA;
            box-shadow: 0 10px 20px rgba(6, 163, 218, 0.3);
            transform: translateY(-3px);
        }
        
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border-color: #badbcc;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success i {
            font-size: 1.5rem;
        }
        
        .event-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .event-summary h4 {
            color: #091E3E;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .event-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .event-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .event-info-item i {
            color: #06A3DA;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .places-info {
            display: inline-block;
            background-color: #06A3DA;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: 600;
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
        
        .error-text {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
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
                <h1 class="display-4 text-white animated zoomIn">Modifier une Réservation</h1>
                <a href="ListReservations.php" class="h5 text-white">Réservations</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Modifier</span>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Modifier Réservation Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <a href="ListReservations.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Retour à la liste des réservations
                    </a>
                </div>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="reservation-form-container wow fadeInUp" data-wow-delay="0.3s">
                        <div class="form-header">
                            <h2 class="mb-0">Modifier la réservation</h2>
                            <p class="mt-2 mb-0">Mettez à jour les informations de votre réservation</p>
                            <div class="event-badge">Modification</div>
                        </div>
                        
                        <div class="form-body">
                            <?php if ($success): ?>
                            <div class="alert-success mb-4">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Réservation modifiée avec succès!</strong>
                                    <p class="mb-0">Votre réservation a été mise à jour. Vous serez redirigé vers vos réservations...</p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $errors['general'] ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!$success): ?>
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nom_client" class="form-label">Nom *</label>
                                            <input type="text" class="form-control <?= isset($errors['nom_client']) ? 'is-invalid' : '' ?>" id="nom_client" name="nom_client" value="<?= isset($_POST['nom_client']) ? htmlspecialchars($_POST['nom_client']) : htmlspecialchars($reservation['nom_client']) ?>">
                                            <?php if (isset($errors['nom_client'])): ?>
                                                <div class="error-text"><?= $errors['nom_client'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($reservation['email']) ?>">
                                            <?php if (isset($errors['email'])): ?>
                                                <div class="error-text"><?= $errors['email'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="nb_places" class="form-label">Nombre de places *</label>
                                    <select class="form-control <?= isset($errors['nb_places']) ? 'is-invalid' : '' ?>" id="nb_places" name="nb_places">
                                        <?php 
                                        $selectedPlaces = isset($_POST['nb_places']) ? (int)$_POST['nb_places'] : (int)$reservation['nb_places'];
                                        for ($i = 1; $i <= min(5, $placesDisponibles); $i++): 
                                        ?>
                                            <option value="<?= $i ?>" <?= $selectedPlaces === $i ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php if (isset($errors['nb_places'])): ?>
                                        <div class="error-text"><?= $errors['nb_places'] ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-submit">
                                        <i class="fas fa-save"></i>
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="reservation-form-container wow fadeInUp" data-wow-delay="0.5s">
                        <div class="form-header">
                            <h3 class="mb-0">Détails de l'événement</h3>
                        </div>
                        
                        <div class="form-body">
                            <div class="event-summary">
                                <h4><?= htmlspecialchars($evenement['nom_event']) ?></h4>
                                
                                <div class="event-info">
                                    <?php
                                    // Formatage de la date
                                    $date = new DateTime($evenement['date_event']);
                                    $dateFormatee = $date->format('d F Y');
                                    ?>
                                    
                                    <div class="event-info-item">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>Date: <?= $dateFormatee ?></span>
                                    </div>
                                    
                                    <?php if (isset($evenement['lieu']) && !empty($evenement['lieu'])): ?>
                                    <div class="event-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Lieu: <?= htmlspecialchars($evenement['lieu']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($evenement['organisateur']) && !empty($evenement['organisateur'])): ?>
                                    <div class="event-info-item">
                                        <i class="fas fa-user-tie"></i>
                                        <span>Organisateur: <?= htmlspecialchars($evenement['organisateur']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="places-info">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    <?= $placesDisponibles ?> places disponibles
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Détails de la réservation</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-info-circle text-primary me-2"></i> Réservation effectuée le: <?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?></li>
                                    <li><i class="fas fa-info-circle text-primary me-2"></i> Numéro de réservation: #<?= $reservation['id_reservation'] ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modifier Réservation End -->

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