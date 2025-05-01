<?php
require_once '../../Controller/ReservationController.php';
require_once '../../Controller/EvenementController.php';

// Vérifier si l'ID de l'événement est fourni
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    header('Location: GestionEvenements.php');
    exit();
}

$event_id = (int)$_GET['event_id'];

// Initialiser 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_client = trim($_POST['nom_client']);
    $email = trim($_POST['email']);
    $nb_places = (int)$_POST['nb_places'];
    $date_reservation = date('Y-m-d');

    if (!empty($nom_client) && !empty($email) && $nb_places > 0) {
        // Créer un tableau de données pour la réservation
        $reservationData = [
            'id_event' => $event_id,
            'nom_client' => $nom_client,
            'email' => $email,
            'date_reservation' => $date_reservation,
            'nb_places' => $nb_places
        ];
        
        $success = $reservationController->addReservation($reservationData);
        $message = $success ? "Réservation ajoutée avec succès." : "Erreur lors de l'ajout de la réservation.";
        $messageType = $success ? "success" : "danger";
        
        if ($success) {
            // Rediriger vers la liste des réservations après un ajout réussi
            header("Location: VoirReservations.php?event_id={$event_id}&message=".urlencode($message)."&type={$messageType}");
            exit();
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
    <title>Ajouter une Réservation - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="gestion réservations, ajout" name="keywords">
    <meta content="Ajouter une nouvelle réservation" name="description">

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
                <h1 class="display-4 text-white animated zoomIn">Ajouter une Réservation</h1>
                <a href="GestionEvenements.php" class="h5 text-white">Événements</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="h5 text-white">Réservations</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Ajouter</span>
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
                <h2 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Ajouter une Réservation</h2>
                <h5 class="text-primary mb-4">Événement : <?= htmlspecialchars($evenement['nom_event']) ?></h5>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                        <i class="<?= $messageType == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle' ?> me-2"></i>
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="mt-4" id="reservationForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_client" class="form-label fw-bold">Nom du Client *</label>
                            <input type="text" class="form-control" id="nom_client" name="nom_client">
                            <div class="invalid-feedback">Veuillez entrer le nom du client.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email *</label>
                            <input type="text" class="form-control" id="email" name="email">
                            <div class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_reservation" class="form-label fw-bold">Date de Réservation</label>
                            <input type="text" class="form-control" id="date_reservation" value="<?= date('d/m/Y') ?>" disabled>
                            <small class="text-muted">La date actuelle sera utilisée.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nb_places" class="form-label fw-bold">Nombre de Places *</label>
                            <input type="number" class="form-control" id="nb_places" name="nb_places">
                            <div class="invalid-feedback">Veuillez entrer un nombre de places entre 1 et 8.</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2" id="btnSubmit">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter la réservation
                        </button>
                        <a href="VoirReservations.php?event_id=<?= $event_id ?>" class="btn btn-secondary px-4 py-2 ms-2">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
                
                <!-- JavaScript pour validation -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Référence au formulaire et aux champs
                    const form = document.getElementById('reservationForm');
                    const nomInput = document.getElementById('nom_client');
                    const emailInput = document.getElementById('email');
                    const nbPlacesInput = document.getElementById('nb_places');
                    
                    // Fonction pour afficher les erreurs
                    function showError(input, message) {
                        input.classList.add('is-invalid');
                        const errorDiv = input.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.textContent = message;
                        }
                    }
                    
                    // Fonction pour cacher les erreurs
                    function hideError(input) {
                        input.classList.remove('is-invalid');
                    }
                    
                    // Validation du nom (lettres, espaces, tirets et apostrophes seulement)
                    function validateName(name) {
                        const nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-']+$/;
                        return nameRegex.test(name);
                    }
                    
                    // Validation de l'email avec regex amélioré
                    function validateEmail(email) {
                        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        return emailRegex.test(email);
                    }
                    
                    // Validation du nombre de places
                    function validateNbPlaces(nbPlaces) {
                        const nb = parseInt(nbPlaces);
                        return !isNaN(nb) && nb >= 1 && nb <= 8;
                    }
                    
                    // Événements de validation temps réel
                    nomInput.addEventListener('input', function() {
                        if (this.value.trim() === '') {
                            showError(this, 'Le nom est obligatoire');
                        } else if (!validateName(this.value.trim())) {
                            showError(this, 'Le nom contient des caractères non autorisés');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    emailInput.addEventListener('input', function() {
                        if (this.value.trim() === '') {
                            showError(this, 'L\'email est obligatoire');
                        } else if (!validateEmail(this.value.trim())) {
                            showError(this, 'Format d\'email invalide');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    nbPlacesInput.addEventListener('input', function() {
                        if (this.value.trim() === '') {
                            showError(this, 'Veuillez spécifier le nombre de places');
                        } else if (!validateNbPlaces(this.value)) {
                            showError(this, 'Le nombre de places doit être entre 1 et 8');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    // Validation avant soumission du formulaire
                    form.addEventListener('submit', function(event) {
                        let isValid = true;
                        
                        // Validation du nom
                        if (nomInput.value.trim() === '') {
                            showError(nomInput, 'Le nom est obligatoire');
                            isValid = false;
                        } else if (!validateName(nomInput.value.trim())) {
                            showError(nomInput, 'Le nom contient des caractères non autorisés');
                            isValid = false;
                        }
                        
                        // Validation de l'email
                        if (emailInput.value.trim() === '') {
                            showError(emailInput, 'L\'email est obligatoire');
                            isValid = false;
                        } else if (!validateEmail(emailInput.value.trim())) {
                            showError(emailInput, 'Format d\'email invalide');
                            isValid = false;
                        }
                        
                        // Validation du nombre de places
                        if (nbPlacesInput.value.trim() === '') {
                            showError(nbPlacesInput, 'Veuillez spécifier le nombre de places');
                            isValid = false;
                        } else if (!validateNbPlaces(nbPlacesInput.value)) {
                            showError(nbPlacesInput, 'Le nombre de places doit être entre 1 et 8');
                            isValid = false;
                        }
                        
                        // Empêcher la soumission si le formulaire n'est pas valide
                        if (!isValid) {
                            event.preventDefault();
                        } else {
                            // Désactiver le bouton pour éviter les soumissions multiples
                            document.getElementById('btnSubmit').disabled = true;
                            document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
                        }
                    });
                });
                </script>
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