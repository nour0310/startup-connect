// Validation du formulaire d'événement
function validerFormEvenement() {
    let isValid = true;
    let erreurs = {};
    
    // Récupérer les éléments du formulaire
    const nomEvent = document.getElementById('nom_event');
    const dateEvent = document.getElementById('date_event');
    const lieu = document.getElementById('lieu');
    const organisateur = document.getElementById('organisateur');
    
    // Réinitialiser les styles et messages d'erreur
    resetFormStyles([nomEvent, dateEvent, lieu, organisateur]);
    
    // Valider le nom de l'événement
    if (!nomEvent.value.trim()) {
        setErrorFor(nomEvent, "Le nom de l'événement est requis");
        erreurs.nom_event = "Le nom de l'événement est requis";
        isValid = false;
    }
    
    // Valider la date de l'événement
    if (!dateEvent.value) {
        setErrorFor(dateEvent, "La date de l'événement est requise");
        erreurs.date_event = "La date de l'événement est requise";
        isValid = false;
    } else {
        // Vérifier que la date est dans le futur
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(dateEvent.value);
        
        if (selectedDate < today) {
            setErrorFor(dateEvent, "La date de l'événement doit être dans le futur");
            erreurs.date_event = "La date de l'événement doit être dans le futur";
            isValid = false;
        }
    }
    
    // Valider le lieu
    if (!lieu.value.trim()) {
        setErrorFor(lieu, "Le lieu de l'événement est requis");
        erreurs.lieu = "Le lieu de l'événement est requis";
        isValid = false;
    }
    
    // Valider l'organisateur
    if (!organisateur.value.trim()) {
        setErrorFor(organisateur, "L'organisateur est requis");
        erreurs.organisateur = "L'organisateur est requis";
        isValid = false;
    }
    
    return { isValid, erreurs };
}

// Fonction pour la recherche d'événements en temps réel
function rechercherEvenements() {
    const keyword = document.getElementById('search_evenement').value;
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const evenements = JSON.parse(this.responseText);
            afficherEvenements(evenements);
        }
    };
    
    xhr.open('GET', '../../Controller/EvenementController.php?action=search_evenements&keyword=' + encodeURIComponent(keyword), true);
    xhr.send();
}

// Fonction pour afficher les événements dans le tableau
function afficherEvenements(evenements) {
    const tbody = document.querySelector('#table_evenements tbody');
    
    // Vider le tableau
    tbody.innerHTML = '';
    
    if (evenements.length === 0) {
        // Aucun événement trouvé
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="6" class="text-center">Aucun événement trouvé</td>';
        tbody.appendChild(tr);
        return;
    }
    
    // Ajouter les événements au tableau
    evenements.forEach(evenement => {
        const tr = document.createElement('tr');
        tr.className = 'inner-box';
        
        tr.innerHTML = `
            <td>
                <div class="event-img">
                    <p>${evenement.id_event}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${evenement.nom_event}</p>
                </div>
            </td>
            <td>
                <div class="event-date">              
                    <p>${evenement.date_event}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${evenement.lieu}</p>
                </div>
            </td>
            <td>
                <div class="event-img">
                    <p>${evenement.organisateur}</p>
                </div>
            </td>
            <td>
                <div class="primary-btn">
                    <a href="ModifierEvenement.php?id=${evenement.id_event}" class="btn btn-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="../../Controller/EvenementController.php?supprimer_evenement=${evenement.id_event}" 
                       class="btn btn-danger" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement?');">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="ListReservationFront.php?evenement=${evenement.id_event}" class="btn btn-info">
                        <i class="fa fa-eye"></i> Voir réservations
                    </a>
                </div>
            </td>
        `;
        
        tbody.appendChild(tr);
    });
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

// Attacher les événements aux éléments une fois le DOM chargé
document.addEventListener('DOMContentLoaded', function() {
    // Formulaire d'ajout/modification d'événement
    const form = document.getElementById('form_evenement');
    if (form) {
        form.addEventListener('submit', function(e) {
            const validation = validerFormEvenement();
            if (!validation.isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Champ de recherche d'événements
    const searchInput = document.getElementById('search_evenement');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            rechercherEvenements();
        });
        
        // Charger les événements au chargement de la page
        rechercherEvenements();
    }
}); 