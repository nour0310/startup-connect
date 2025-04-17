<?php
require_once '../../Controller/EvenementController.php';
require_once '../../Controller/ReservationController.php';

// Initialisation des contrôleurs
$evenementController = new EvenementController();
$reservationController = new ReservationController();

// Vérification de l'ID de l'événement
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ListEvenements.php');
    exit();
}

$id_event = $_GET['id'];
$evenement = $evenementController->getEvenementById($id_event);

// Vérifier si l'événement existe
if (!$evenement) {
    header('Location: ListEvenements.php');
    exit();
}

// Calculer le nombre de places disponibles
$placesReservees = $reservationController->getTotalPlacesReservees($id_event);
$placesMax = 100;
$placesDisponibles = $placesMax - $placesReservees;
$maxPlacesPerPerson = 8; // Maximum 8 places par personne

// Si aucune place n'est disponible, rediriger vers la page de détail
if ($placesDisponibles <= 0) {
    header('Location: DetailEvenement.php?id=' . $id_event . '&error=complet');
    exit();
}

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
    
    // Si aucune erreur, procéder à l'ajout
    if (empty($errors)) {
        // Création d'un tableau avec les données de réservation
        $reservationData = [
            'id_event' => $id_event,
            'nom_client' => $nom_client,
            'email' => $email,
            'nb_places' => $nbPlaces,
            'date_reservation' => date('Y-m-d') // Date actuelle
        ];
        
        // Appel à la méthode d'ajout
        $success = $reservationController->addReservation($reservationData);
        
        if ($success) {
            // Redirection après 2 secondes vers la liste des réservations
            header("Refresh: 2; URL=ListReservations.php");
        } else {
            $errors['general'] = "Une erreur est survenue lors de l'enregistrement de la réservation";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Réserver - <?= htmlspecialchars($evenement['nom_event']) ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="réservation, événement, <?= htmlspecialchars($evenement['nom_event']) ?>" name="keywords">
    <meta content="Réserver une place pour l'événement <?= htmlspecialchars($evenement['nom_event']) ?>" name="description">

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
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }
        
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
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
                <h1 class="display-4 text-white animated zoomIn">Réserver</h1>
                <a href="ListEvenements.php" class="h5 text-white">Événements</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="DetailEvenement.php?id=<?= $id_event ?>" class="h5 text-white">Détails</a>
                <i class="far fa-circle text-white px-2"></i>
                <span class="h5 text-white">Réserver</span>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Réservation Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <a href="DetailEvenement.php?id=<?= $id_event ?>" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Retour aux détails de l'événement
                    </a>
                </div>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="reservation-form-container wow fadeInUp" data-wow-delay="0.3s">
                        <div class="form-header">
                            <h2 class="mb-0">Réserver pour l'événement</h2>
                            <p class="mt-2 mb-0">Complétez le formulaire ci-dessous</p>
                            <div class="event-badge">Événement</div>
                        </div>
                        
                        <div class="form-body">
                            <?php if ($success): ?>
                            <div class="alert-success mb-4">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Réservation effectuée avec succès!</strong>
                                    <p class="mb-0">Votre réservation a été enregistrée. Vous serez redirigé vers vos réservations...</p>
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
                                            <input type="text" class="form-control <?= isset($errors['nom_client']) ? 'is-invalid' : '' ?>" id="nom_client" name="nom_client" placeholder="Votre nom" value="<?= isset($_POST['nom_client']) ? htmlspecialchars($_POST['nom_client']) : '' ?>">
                                            <?php if (isset($errors['nom_client'])): ?>
                                                <div class="error-text"><?= $errors['nom_client'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="text" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Votre adresse email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                            <?php if (isset($errors['email'])): ?>
                                                <div class="error-text"><?= $errors['email'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="nb_places" class="form-label">Nombre de places * <small class="text-muted">(maximum 8 places par personne)</small></label>
                                    <select class="form-control <?= isset($errors['nb_places']) ? 'is-invalid' : '' ?>" id="nb_places" name="nb_places">
                                        <?php 
                                        $selectedPlaces = isset($_POST['nb_places']) ? (int)$_POST['nb_places'] : 1;
                                        for ($i = 1; $i <= min($maxPlacesPerPerson, $placesDisponibles); $i++): 
                                        ?>
                                            <option value="<?= $i ?>" <?= $selectedPlaces === $i ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php if (isset($errors['nb_places'])): ?>
                                        <div class="error-text"><?= $errors['nb_places'] ?></div>
                                    <?php endif; ?>
                                    <div class="invalid-feedback" id="nbPlacesError">Le nombre de places doit être entre 1 et 8.</div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-submit" id="btnSubmit">
                                        <i class="fas fa-ticket-alt"></i>
                                        Confirmer la réservation
                                    </button>
                                </div>
                            </form>
                            
                            <!-- JavaScript pour validation -->
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Référence au formulaire et aux champs
                                const form = document.querySelector('form');
                                const nomInput = document.getElementById('nom_client');
                                const emailInput = document.getElementById('email');
                                const nbPlacesSelect = document.getElementById('nb_places');
                                
                                // Fonction pour afficher les erreurs
                                function showError(input, message) {
                                    input.classList.add('is-invalid');
                                    // Rechercher le div de message d'erreur
                                    const parent = input.parentElement;
                                    const errorDiv = parent.querySelector('.error-text') || parent.querySelector('.invalid-feedback');
                                    if (errorDiv) {
                                        errorDiv.textContent = message;
                                        errorDiv.style.display = 'block';
                                    }
                                }
                                
                                // Fonction pour cacher les erreurs
                                function hideError(input) {
                                    input.classList.remove('is-invalid');
                                    // Rechercher et cacher le div de message d'erreur
                                    const parent = input.parentElement;
                                    const errorDiv = parent.querySelector('.error-text') || parent.querySelector('.invalid-feedback');
                                    if (errorDiv) {
                                        errorDiv.style.display = 'none';
                                    }
                                }
                                
                                // Validation du nom (lettres, espaces, tirets et apostrophes seulement)
                                function validateName(name) {
                                    if (name.trim() === '') return false;
                                    if (name.trim().length > 100) return false;
                                    const nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-']+$/;
                                    return nameRegex.test(name);
                                }
                                
                                // Validation de l'email
                                function validateEmail(email) {
                                    if (email.trim() === '') return false;
                                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                                    return emailRegex.test(email);
                                }
                                
                                // Validation du nombre de places
                                function validateNbPlaces(nbPlaces) {
                                    const maxPlaces = <?= min($maxPlacesPerPerson, $placesDisponibles) ?>;
                                    const nb = parseInt(nbPlaces);
                                    return !isNaN(nb) && nb >= 1 && nb <= maxPlaces;
                                }
                                
                                // Événements de validation temps réel
                                nomInput.addEventListener('input', function() {
                                    if (this.value.trim() === '') {
                                        showError(this, 'Veuillez entrer votre nom');
                                    } else if (this.value.trim().length > 100) {
                                        showError(this, 'Le nom ne peut pas dépasser 100 caractères');
                                    } else if (!validateName(this.value)) {
                                        showError(this, 'Le nom contient des caractères non autorisés');
                                    } else {
                                        hideError(this);
                                    }
                                });
                                
                                emailInput.addEventListener('input', function() {
                                    if (this.value.trim() === '') {
                                        showError(this, 'Veuillez entrer votre adresse email');
                                    } else if (!validateEmail(this.value)) {
                                        showError(this, 'Veuillez entrer une adresse email valide');
                                    } else {
                                        hideError(this);
                                    }
                                });
                                
                                nbPlacesSelect.addEventListener('change', function() {
                                    if (!validateNbPlaces(this.value)) {
                                        const maxPlaces = <?= min($maxPlacesPerPerson, $placesDisponibles) ?>;
                                        showError(this, `Le nombre de places doit être entre 1 et ${maxPlaces}`);
                                    } else {
                                        hideError(this);
                                    }
                                });
                                
                                // Validation complète du formulaire
                                form.addEventListener('submit', function(event) {
                                    let isValid = true;
                                    
                                    // Valider le nom
                                    if (!validateName(nomInput.value)) {
                                        if (nomInput.value.trim() === '') {
                                            showError(nomInput, 'Veuillez entrer votre nom');
                                        } else if (nomInput.value.trim().length > 100) {
                                            showError(nomInput, 'Le nom ne peut pas dépasser 100 caractères');
                                        } else {
                                            showError(nomInput, 'Le nom contient des caractères non autorisés');
                                        }
                                        isValid = false;
                                    }
                                    
                                    // Valider l'email
                                    if (!validateEmail(emailInput.value)) {
                                        if (emailInput.value.trim() === '') {
                                            showError(emailInput, 'Veuillez entrer votre adresse email');
                                        } else {
                                            showError(emailInput, 'Veuillez entrer une adresse email valide');
                                        }
                                        isValid = false;
                                    }
                                    
                                    // Valider le nombre de places
                                    if (!validateNbPlaces(nbPlacesSelect.value)) {
                                        const maxPlaces = <?= min($maxPlacesPerPerson, $placesDisponibles) ?>;
                                        showError(nbPlacesSelect, `Le nombre de places doit être entre 1 et ${maxPlaces}`);
                                        isValid = false;
                                    }
                                    
                                    // Empêcher la soumission si le formulaire est invalide
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
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="reservation-form-container wow fadeInUp" data-wow-delay="0.5s">
                        <div class="form-header">
                            <h3 class="mb-0">Résumé de l'événement</h3>
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
                                
                                <div class="mb-3 mt-2">
                                    <div class="alert alert-info" role="alert">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pour des raisons d'organisation, les réservations sont limitées à 8 places par personne.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Informations importantes</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-info-circle text-primary me-2"></i> Veuillez vous présenter 15 minutes avant le début de l'événement.</li>
                                    <li><i class="fas fa-info-circle text-primary me-2"></i> Apportez une pièce d'identité pour confirmer votre réservation.</li>
                                    <li><i class="fas fa-info-circle text-primary me-2"></i> L'annulation est possible jusqu'à 48h avant l'événement.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Réservation End -->

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