<?php
require_once '../../Controller/EvenementController.php';

// Initialiser le contrôleur
$evenementController = new EvenementController();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $date = trim($_POST['date']);
    $lieu = trim($_POST['lieu']);
    $organisateur = trim($_POST['organisateur']);

    if (!empty($nom) && !empty($date)) {
        $success = $evenementController->ajouterEvenement($nom, $date, $lieu, $organisateur);
        $message = $success ? "Événement ajouté avec succès." : "Erreur lors de l'ajout de l'événement.";
        $messageType = $success ? "success" : "danger";
        
        if ($success) {
            // Rediriger vers la liste des événements après un ajout réussi
            header("Location: GestionEvenements.php?message=".urlencode($message)."&type={$messageType}");
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
    <title>Ajouter un Événement - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="gestion événements, ajout" name="keywords">
    <meta content="Ajouter un nouvel événement" name="description">

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
                <h1 class="display-4 text-white animated zoomIn">Ajouter un Événement</h1>
                <a href="GestionEvenements.php" class="h5 text-white">Événements</a>
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
                    <a href="GestionEvenements.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste des événements
                    </a>
                </div>
            </div>
            
            <div class="form-container">
                <h2 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Ajouter un Nouvel Événement</h2>
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                        <i class="<?= $messageType == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle' ?> me-2"></i>
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="mt-4" id="eventForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-bold">Nom de l'Événement *</label>
                            <input type="text" class="form-control" id="nom" name="nom">
                            <div class="invalid-feedback">Veuillez entrer un nom d'événement valide.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label fw-bold">Date *</label>
                            <input type="date" class="form-control" id="date" name="date">
                            <div class="invalid-feedback" id="dateError">La date ne peut pas être dans le passé.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lieu" class="form-label fw-bold">Lieu *</label>
                            <select class="form-select" id="lieu" name="lieu">
                                <option value="">Sélectionner un lieu</option>
                                <option value="Tunis">Tunis</option>
                                <option value="Sfax">Sfax</option>
                                <option value="Sousse">Sousse</option>
                                <option value="Kairouan">Kairouan</option>
                                <option value="Bizerte">Bizerte</option>
                                <option value="Gabès">Gabès</option>
                                <option value="Ariana">Ariana</option>
                                <option value="Gafsa">Gafsa</option>
                                <option value="Monastir">Monastir</option>
                                <option value="Ben Arous">Ben Arous</option>
                                <option value="Kasserine">Kasserine</option>
                                <option value="Médenine">Médenine</option>
                                <option value="Nabeul">Nabeul</option>
                                <option value="Tataouine">Tataouine</option>
                                <option value="Béja">Béja</option>
                                <option value="Kef">Kef</option>
                                <option value="Mahdia">Mahdia</option>
                                <option value="Sidi Bouzid">Sidi Bouzid</option>
                                <option value="Jendouba">Jendouba</option>
                                <option value="Tozeur">Tozeur</option>
                                <option value="Siliana">Siliana</option>
                                <option value="Zaghouan">Zaghouan</option>
                                <option value="Kébili">Kébili</option>
                                <option value="Manouba">Manouba</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un lieu.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="organisateur" class="form-label fw-bold">Organisateur *</label>
                            <input type="text" class="form-control" id="organisateur" name="organisateur">
                            <div class="invalid-feedback">Veuillez spécifier un organisateur.</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter l'événement
                        </button>
                        <a href="GestionEvenements.php" class="btn btn-secondary px-4 py-2 ms-2">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
                
                <!-- JavaScript pour la validation du formulaire -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Référence au formulaire et aux champs
                    const form = document.getElementById('eventForm');
                    const nomInput = document.getElementById('nom');
                    const dateInput = document.getElementById('date');
                    const lieuSelect = document.getElementById('lieu');
                    const organisateurInput = document.getElementById('organisateur');
                    
                    // Fonction pour afficher les erreurs
                    function showError(input, message) {
                        input.classList.add('is-invalid');
                        // Trouver le div de feedback correspondant
                        const errorDiv = input.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.textContent = message;
                            errorDiv.style.display = 'block';
                        }
                    }
                    
                    // Fonction pour masquer les erreurs
                    function hideError(input) {
                        input.classList.remove('is-invalid');
                        const errorDiv = input.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.style.display = 'none';
                        }
                    }
                    
                    // Validation du nom d'événement (ne doit pas être vide)
                    function validateEventName(name) {
                        return name.trim() !== '';
                    }
                    
                    // Vérifier si une date est dans le passé
                    function isDateInPast(dateString) {
                        const selectedDate = new Date(dateString);
                        selectedDate.setHours(0, 0, 0, 0);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        return selectedDate < today;
                    }
                    
                    // Validation de la date (doit être renseignée et ne pas être dans le passé)
                    function validateDate(date) {
                        if (date === '') return false;
                        if (isDateInPast(date)) return false;
                        return true;
                    }
                    
                    // Validation du lieu (doit être sélectionné)
                    function validateLocation(location) {
                        return location.trim() !== '';
                    }
                    
                    // Validation de l'organisateur (ne doit pas être vide)
                    function validateOrganizer(organizer) {
                        return organizer.trim() !== '';
                    }
                    
                    // Validation en temps réel
                    nomInput.addEventListener('input', function() {
                        if (!validateEventName(this.value)) {
                            showError(this, 'Veuillez entrer un nom d\'événement valide');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    dateInput.addEventListener('change', function() {
                        if (this.value === '') {
                            showError(this, 'Veuillez sélectionner une date');
                        } else if (isDateInPast(this.value)) {
                            showError(this, 'La date ne peut pas être dans le passé');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    lieuSelect.addEventListener('change', function() {
                        if (!validateLocation(this.value)) {
                            showError(this, 'Veuillez sélectionner un lieu');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    organisateurInput.addEventListener('input', function() {
                        if (!validateOrganizer(this.value)) {
                            showError(this, 'Veuillez spécifier un organisateur');
                        } else {
                            hideError(this);
                        }
                    });
                    
                    // Validation complète du formulaire lors de la soumission
                    form.addEventListener('submit', function(event) {
                        let isValid = true;
                        
                        // Validation du nom
                        if (!validateEventName(nomInput.value)) {
                            showError(nomInput, 'Veuillez entrer un nom d\'événement valide');
                            isValid = false;
                        }
                        
                        // Validation de la date
                        if (!validateDate(dateInput.value)) {
                            if (dateInput.value === '') {
                                showError(dateInput, 'Veuillez sélectionner une date');
                            } else {
                                showError(dateInput, 'La date ne peut pas être dans le passé');
                            }
                            isValid = false;
                        }
                        
                        // Validation du lieu
                        if (!validateLocation(lieuSelect.value)) {
                            showError(lieuSelect, 'Veuillez sélectionner un lieu');
                            isValid = false;
                        }
                        
                        // Validation de l'organisateur
                        if (!validateOrganizer(organisateurInput.value)) {
                            showError(organisateurInput, 'Veuillez spécifier un organisateur');
                            isValid = false;
                        }
                        
                        // Empêcher la soumission si le formulaire est invalide
                        if (!isValid) {
                            event.preventDefault();
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