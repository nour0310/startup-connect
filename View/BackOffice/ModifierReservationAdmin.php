<?php
require_once '../../Controller/ReservationController.php';
require_once '../../Controller/EvenementController.php';

// Vérifier si l'ID de la réservation et de l'événement sont fournis
if (!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['event_id']) || empty($_GET['event_id'])) {
    header('Location: GestionEvenements.php');
    exit();
}

$reservation_id = (int)$_GET['id'];
$event_id = (int)$_GET['event_id'];

// Initialiser 
$reservationController = new ReservationController();
$evenementController = new EvenementController();

// Récupérer les détails de la réservation et de l'événement
$reservation = $reservationController->getReservationById($reservation_id);
$evenement = $evenementController->getEvenementById($event_id);

if (!$reservation || !$evenement) {
    header('Location: GestionEvenements.php');
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_client = trim($_POST['nom_client']);
    $email = trim($_POST['email']);
    $nb_places = (int)$_POST['nb_places'];

    if (!empty($nom_client) && !empty($email) && $nb_places > 0) {
        // Créer un objet Reservation avec les informations mises à jour
        include_once '../../Model/Reservation.php';
        $reservationObj = new Reservation(
            $event_id,
            $nom_client,
            $email,
            $reservation['date_reservation'],
            $nb_places,
            $reservation_id
        );
        
        $success = $reservationController->modifierReservation($reservationObj);
        $message = $success ? "Réservation modifiée avec succès." : "Erreur lors de la modification de la réservation.";
        $messageType = $success ? "success" : "danger";
        
        if ($success) {
            // Recharger les données
            $reservation = $reservationController->getReservationById($reservation_id);
            
            // Rediriger après un délai court pour afficher le message de succès
            header("refresh:2;url=VoirReservations.php?event_id={$event_id}&message=".urlencode($message)."&type={$messageType}");
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
        $messageType = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier une Réservation - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="gestion réservations, modification" name="keywords">
    <meta content="Modifier une réservation existante" name="description">

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
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
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
                            <a href="GestionEvenements.php" class="dropdown-item">Événements</a>
                            <a href="GestionReservations.php" class="dropdown-item active">Réservations</a>
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
                <h1 class="display-4 text-white animated zoomIn">Modifier une Réservation</h1>
                <a href="GestionEvenements.php" class="h5 text-white">Événements</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="h5 text-white">Réservations</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Modifier</span>
            </div>
        </div>
    </div>
    <!-- Header End -->
    
    <!-- Form Section Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste des réservations
                    </a>
                </div>
            </div>
            
            <div class="form-container">
                <h2 class="mb-4"><i class="fas fa-edit me-2"></i>Modifier la Réservation</h2>
                <h5 class="text-primary mb-4">Événement : <?= htmlspecialchars($evenement['nom_event']) ?></h5>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                        <i class="<?= $messageType == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle' ?> me-2"></i>
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="mt-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_client" class="form-label fw-bold">Nom du Client *</label>
                            <input type="text" class="form-control" id="nom_client" name="nom_client" value="<?= htmlspecialchars($reservation['nom_client']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($reservation['email']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_reservation" class="form-label fw-bold">Date de Réservation</label>
                            <input type="text" class="form-control" id="date_reservation" value="<?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nb_places" class="form-label fw-bold">Nombre de Places *</label>
                            <input type="number" class="form-control" id="nb_places" name="nb_places" value="<?= htmlspecialchars($reservation['nb_places']) ?>" min="1" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="btn btn-secondary px-4 py-2 ms-2">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Form Section End -->

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