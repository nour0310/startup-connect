// Validation du formulaire de réservation
function validerFormReservation() {
    let isValid = true;
    let erreurs = {};
    
    // Récupérer les éléments du formulaire
    const idEvent = document.getElementById('id_event');
    const nomClient = document.getElementById('nom_client');
    const email = document.getElementById('email');
    const dateReservation = document.getElementById('date_reservation');
    const nbPlaces = document.getElementById('nb_places');
    
    // Réinitialiser les styles et messages d'erreur
    resetFormStyles([idEvent, nomClient, email, dateReservation, nbPlaces]);
    
    // Valider l'événement
    if (!idEvent.value || idEvent.value === "0") {
        setErrorFor(idEvent, "Veuillez sélectionner un événement");
        erreurs.id_event = "Veuillez sélectionner un événement";
        isValid = false;
    }
    
    // Valider le nom du client
    if (!nomClient.value.trim()) {
        setErrorFor(nomClient, "Le nom du client est requis");
        erreurs.nom_client = "Le nom du client est requis";
        isValid = false;
    }
    
    // Valider l'email
    if (!email.value.trim()) {
        setErrorFor(email, "L'email est requis");
        erreurs.email = "L'email est requis";
        isValid = false;
    } else if (!isValidEmail(email.value.trim())) {
        setErrorFor(email, "L'email n'est pas valide");
        erreurs.email = "L'email n'est pas valide";
        isValid = false;
    }
    
    // Valider la date de réservation
    if (!dateReservation.value) {
        setErrorFor(dateReservation, "La date de réservation est requise");
        erreurs.date_reservation = "La date de réservation est requise";
        isValid = false;
    } else {
        // Vérifier que la date n'est pas antérieure à aujourd'hui
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(dateReservation.value);
        
        if (selectedDate < today) {
            setErrorFor(dateReservation, "La date de réservation ne peut pas être dans le passé");
            erreurs.date_reservation = "La date de réservation ne peut pas être dans le passé";
            isValid = false;
        }
    }
    
    // Valider le nombre de places
    if (!nbPlaces.value) {
        setErrorFor(nbPlaces, "Le nombre de places est requis");
        erreurs.nb_places = "Le nombre de places est requis";
        isValid = false;
    } else if (isNaN(nbPlaces.value) || parseInt(nbPlaces.value) <= 0) {
        setErrorFor(nbPlaces, "Le nombre de places doit être un nombre positif");
        erreurs.nb_places = "Le nombre de places doit être un nombre positif";
        isValid = false;
    }
    
    return { isValid, erreurs };
}

// Fonction pour la recherche de réservations en temps réel
function rechercherReservations() {
    const keyword = document.getElementById('search_reservation').value;
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const reservations = JSON.parse(this.responseText);
            afficherReservations(reservations);
        }
    };
    
    xhr.open('GET', '../../Controller/ReservationController.php?action=search_reservations&keyword=' + encodeURIComponent(keyword), true);
    xhr.send();
}

// Fonction pour afficher les réservations dans le tableau
function afficherReservations(reservations) {
    const tbody = document.querySelector('#table_reservations tbody');
    
    // Vider le tableau
    tbody.innerHTML = '';
    
    if (reservations.length === 0) {
        // Aucune réservation trouvée
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="7" class="text-center">Aucune réservation trouvée</td>';
        tbody.appendChild(tr);
        return;
    }
    
    // Ajouter les réservations au tableau
    reservations.forEach(reservation => {
        const tr = document.createElement('tr');
        tr.className = 'inner-box';
        
        tr.innerHTML = `
            <td>
                <div class="event-img">
                    <p>${reservation.id_reservation}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${reservation.nom_event}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${reservation.nom_client}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${reservation.email}</p>
                </div>
            </td>
            <td>
                <div class="event-date">              
                    <p>${reservation.date_reservation}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${reservation.nb_places}</p>
                </div>
            </td>
            <td>
                <div class="primary-btn">
                    <a href="ModifierReservation.php?id=${reservation.id_reservation}" class="btn btn-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="../../Controller/ReservationController.php?supprimer_reservation=${reservation.id_reservation}" 
                       class="btn btn-danger" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </td>
        `;
        
        tbody.appendChild(tr);
    });
}

// Fonction pour charger les réservations d'un événement spécifique
function chargerReservationsParEvenement(idEvent) {
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const reservations = JSON.parse(this.responseText);
            afficherReservations(reservations);
        }
    };
    
    xhr.open('GET', '../../Controller/ReservationController.php?evenement=' + idEvent, true);
    xhr.send();
}

// Fonctions utilitaires
function resetFormStyles(elements) {
    elements.forEach(element => {
        element.classList.remove('is-invalid');
        const errorElement = element.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = '';
        }
    });
}

function setErrorFor(input, message) {
    input.classList.add('is-invalid');
    
    // Créer ou récupérer l'élément d'erreur
    let errorElement = input.parentElement.querySelector('.error-message');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message text-danger';
        input.parentElement.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

// Attacher les événements aux éléments une fois le DOM chargé
document.addEventListener('DOMContentLoaded', function() {
    // Formulaire d'ajout/modification de réservation
    const form = document.getElementById('form_reservation');
    if (form) {
        form.addEventListener('submit', function(e) {
            const validation = validerFormReservation();
            if (!validation.isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Champ de recherche de réservations
    const searchInput = document.getElementById('search_reservation');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            rechercherReservations();
        });
        
        // Charger les réservations au chargement de la page
        rechercherReservations();
    }
    
    // Si on est sur la page de liste des réservations d'un événement spécifique
    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('evenement');
    if (eventId) {
        chargerReservationsParEvenement(eventId);
    }
}); 