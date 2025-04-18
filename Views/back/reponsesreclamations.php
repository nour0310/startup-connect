<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Startup Connect - Réponses aux Réclamations</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        /* Styles personnalisés */
        .dashboard-container {
            padding: 2rem 0;
            min-height: calc(100vh - 300px);
        }
        .stat-card {
            border-radius: 10px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }
        .status-badge {
            padding: 0.35rem 0.65rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending { background-color: #ffc107; color: #212529; }
        .status-sent { background-color: #17a2b8; color: white; }
        .status-read { background-color: #28a745; color: white; }
        .action-btn { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .filter-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th {
            white-space: nowrap;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }
        .response-card {
            transition: all 0.3s;
        }
        .response-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Bloc E, Esprit, Cite La Gazelle</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 90 044 054</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>Startup Connect@gmail.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="text-light"><i class="fa fa-user-shield me-2"></i>Espace Administrateur</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="admin-dashboard.html" class="nav-item nav-link">Tableau de bord</a>
                    <a href="admin-users.html" class="nav-item nav-link">Utilisateurs</a>
                    <a href="admin-projects.html" class="nav-item nav-link">Projets</a>
                    <a href="admin-formations.html" class="nav-item nav-link">Formations</a>
                    <a href="admin-events.html" class="nav-item nav-link">Événements</a>
                    <a href="admin-investments.html" class="nav-item nav-link">Investissements</a>
                    <a href="admin-reclamations.html" class="nav-item nav-link">Réclamations</a>
                    <a href="reponsesreclamations.html" class="nav-item nav-link active">Réponses</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-1"></i> Admin
                        </a>
                        <div class="dropdown-menu m-0">
                            <a href="admin-profile.html" class="dropdown-item">Profil</a>
                            <a href="admin-settings.html" class="dropdown-item">Paramètres</a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.html" class="dropdown-item">Déconnexion</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="dashboard-container">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="mb-0"><i class="fas fa-reply me-2"></i>Gestion des Réponses</h2>
                            <div>
                                <button id="exportBtn" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-file-export me-1"></i> Exporter
                                </button>
                                <button id="addBtn" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Nouvelle Réponse
                                </button>
                            </div>
                        </div>

                        <!-- Filtres Admin -->
                        <div class="filter-section mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">Tous</option>
                                        <option value="pending">En attente</option>
                                        <option value="sent">Envoyé</option>
                                        <option value="read">Lu</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Réclamation</label>
                                    <select class="form-select" id="filterReclamation">
                                        <option value="">Toutes</option>
                                        <!-- Rempli dynamiquement -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <select class="form-select" id="filterDate">
                                        <option value="">Toutes</option>
                                        <option value="today">Aujourd'hui</option>
                                        <option value="week">Cette semaine</option>
                                        <option value="month">Ce mois</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Cartes Statistiques -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stat-card bg-primary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="count" id="count-pending">0</div>
                                            <div class="title">En attente</div>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="count" id="count-sent">0</div>
                                            <div class="title">Envoyées</div>
                                        </div>
                                        <i class="fas fa-paper-plane fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="count" id="count-read">0</div>
                                            <div class="title">Lues</div>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des Réponses -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="responsesTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Réclamation</th>
                                                <th>Contenu</th>
                                                <th>Auteur</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="responsesBody">
                                            <!-- Rempli dynamiquement -->
                                        </tbody>
                                    </table>
                                </div>
                                <nav class="mt-3">
                                    <ul class="pagination justify-content-center" id="pagination">
                                        <!-- Pagination dynamique -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal CRUD -->
        <div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="modalTitle">Détails de la Réponse</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="responseForm">
                            <input type="hidden" id="editId">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">ID Réponse</label>
                                    <input type="text" class="form-control" id="displayId" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de création</label>
                                    <input type="text" class="form-control" id="displayDate" readonly>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Réclamation liée *</label>
                                    <select class="form-select" id="editReclamation" required>
                                        <!-- Rempli dynamiquement -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Auteur *</label>
                                    <input type="text" class="form-control" id="editAuthor" value="Admin" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Contenu *</label>
                                <textarea class="form-control" id="editContent" rows="5" required></textarea>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Statut *</label>
                                    <select class="form-select" id="editStatus" required>
                                        <option value="pending">En attente</option>
                                        <option value="sent">Envoyé</option>
                                        <option value="read">Lu</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pièces jointes</label>
                                <div id="attachmentsList" class="mb-2">
                                    <!-- Liste des pièces jointes -->
                                </div>
                                <input type="file" class="form-control" id="editAttachments" multiple>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-danger" id="deleteBtn">Supprimer</button>
                        <button type="button" class="btn btn-success" id="saveBtn">Enregistrer</button>
                    </div>
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
                                <h3 class="text-light mb-0">Contact</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Rue Tunis,Tunisie, TN</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">Startup Connect@gmail.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+216 90 044 054</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Startup Connect</a>. All Rights Reserved.</p>
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
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        // Configuration
        const ITEMS_PER_PAGE = 10;
        let currentPage = 1;
        let filteredResponses = [];
        
        // Données (simulées - remplacer par API réelle)
        let responses = JSON.parse(localStorage.getItem('responses')) || [];
        let reclamations = JSON.parse(localStorage.getItem('reclamations')) || [];
        
        // Initialiser avec des données de démo si vide
        if (responses.length === 0) {
            responses = [
                {
                    id: generateResponseId(),
                    reclamationId: reclamations.length > 0 ? reclamations[0].id : "REC-0001",
                    content: "Nous avons bien reçu votre réclamation et la traitons en priorité.",
                    author: "Admin Startup Connect",
                    date: new Date().toISOString(),
                    status: "sent",
                    attachments: []
                },
                {
                    id: generateResponseId(),
                    reclamationId: reclamations.length > 1 ? reclamations[1].id : "REC-0002",
                    content: "Votre demande de remboursement a été approuvée.",
                    author: "Admin Startup Connect",
                    date: new Date(Date.now() - 86400000).toISOString(), // Hier
                    status: "read",
                    attachments: []
                }
            ];
            localStorage.setItem('responses', JSON.stringify(responses));
        }
        
        // Fonction pour générer un ID unique
        function generateResponseId() {
            return 'RES-' + Math.random().toString(36).substr(2, 8).toUpperCase();
        }

        // Afficher les réponses avec pagination
        function renderResponses(page = 1) {
            currentPage = page;
            const tbody = document.getElementById('responsesBody');
            tbody.innerHTML = '';

            // Appliquer les filtres
            applyFilters();
            
            // Calculer les indices de pagination
            const startIdx = (page - 1) * ITEMS_PER_PAGE;
            const endIdx = startIdx + ITEMS_PER_PAGE;
            const paginatedResponses = filteredResponses.slice(startIdx, endIdx);

            // Remplir le tableau
            paginatedResponses.forEach((res, index) => {
                const rec = reclamations.find(r => r.id === res.reclamationId) || {};
                const tr = document.createElement('tr');
                tr.className = 'response-card';
                tr.innerHTML = `
                    <td>${res.id}</td>
                    <td>${rec.id || res.reclamationId} - ${rec.subject || 'Réclamation supprimée'}</td>
                    <td>${res.content.length > 50 ? res.content.substring(0, 50) + '...' : res.content}</td>
                    <td>${res.author}</td>
                    <td>${formatDate(res.date)}</td>
                    <td><span class="status-badge ${getStatusClass(res.status)}">${getStatusText(res.status)}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary action-btn view-btn" data-id="${findIndexById(res.id)}" title="Voir">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-info action-btn edit-btn" data-id="${findIndexById(res.id)}" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger action-btn delete-btn" data-id="${findIndexById(res.id)}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Mettre à jour la pagination
            renderPagination();
            
            // Mettre à jour les statistiques
            updateStats();
        }

        // Appliquer les filtres
        function applyFilters() {
            const statusFilter = document.getElementById('filterStatus').value;
            const reclamationFilter = document.getElementById('filterReclamation').value;
            const dateFilter = document.getElementById('filterDate').value;
            
            filteredResponses = responses.filter(res => {
                // Filtre par statut
                if (statusFilter && res.status !== statusFilter) return false;
                
                // Filtre par réclamation
                if (reclamationFilter && res.reclamationId !== reclamationFilter) return false;
                
                // Filtre par date
                if (dateFilter) {
                    const resDate = new Date(res.date);
                    const today = new Date();
                    
                    if (dateFilter === 'today') {
                        return resDate.toDateString() === today.toDateString();
                    } else if (dateFilter === 'week') {
                        const weekStart = new Date(today);
                        weekStart.setDate(today.getDate() - today.getDay());
                        return resDate >= weekStart;
                    } else if (dateFilter === 'month') {
                        return resDate.getMonth() === today.getMonth() && 
                               resDate.getFullYear() === today.getFullYear();
                    }
                }
                
                return true;
            });
            
            // Trier par date (les plus récentes en premier)
            filteredResponses.sort((a, b) => new Date(b.date) - new Date(a.date));
        }

        // Mettre à jour les statistiques
        function updateStats() {
            const stats = {
                pending: 0,
                sent: 0,
                read: 0
            };

            responses.forEach(res => {
                if (res.status === 'pending') stats.pending++;
                if (res.status === 'sent') stats.sent++;
                if (res.status === 'read') stats.read++;
            });

            document.getElementById('count-pending').textContent = stats.pending;
            document.getElementById('count-sent').textContent = stats.sent;
            document.getElementById('count-read').textContent = stats.read;
        }

        // Afficher la pagination
        function renderPagination() {
            const totalPages = Math.ceil(filteredResponses.length / ITEMS_PER_PAGE);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            
            if (totalPages <= 1) return;
            
            // Bouton Précédent
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
            prevLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) renderResponses(currentPage - 1);
            });
            pagination.appendChild(prevLi);
            
            // Pages
            for (let i = 1; i <= totalPages; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    renderResponses(i);
                });
                pagination.appendChild(pageLi);
            }
            
            // Bouton Suivant
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
            nextLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage < totalPages) renderResponses(currentPage + 1);
            });
            pagination.appendChild(nextLi);
        }

        // Gestion des statuts
        function getStatusClass(status) {
            return {
                'pending': 'status-pending',
                'sent': 'status-sent',
                'read': 'status-read'
            }[status] || '';
        }

        function getStatusText(status) {
            return {
                'pending': 'En attente',
                'sent': 'Envoyé',
                'read': 'Lu'
            }[status] || status;
        }
        
        function formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('fr-FR', options);
        }
        
        function findIndexById(id) {
            return responses.findIndex(res => res.id === id);
        }

        // Remplir le dropdown des réclamations
        function populateReclamationsDropdown() {
            const dropdown = document.getElementById('editReclamation');
            dropdown.innerHTML = reclamations.map(rec => 
                `<option value="${rec.id}">${rec.id} - ${rec.subject}</option>`
            ).join('');
        }

        // Remplir le filtre des réclamations
        function populateReclamationsFilter() {
            const filter = document.getElementById('filterReclamation');
            filter.innerHTML = '<option value="">Toutes</option>' + 
                reclamations.map(rec => 
                    `<option value="${rec.id}">${rec.id} - ${rec.subject}</option>`
                ).join('');
        }

        // Ouvrir le modal
        function openModal(id = null, action = 'view') {
            const form = document.getElementById('responseForm');
            const modal = new bootstrap.Modal('#crudModal');
            
            // Remplir le dropdown des réclamations
            populateReclamationsDropdown();
            
            if (id !== null) {
                const res = responses[id];
                document.getElementById('editId').value = id;
                document.getElementById('displayId').value = res.id;
                document.getElementById('displayDate').value = formatDate(res.date);
                document.getElementById('editReclamation').value = res.reclamationId;
                document.getElementById('editAuthor').value = res.author;
                document.getElementById('editContent').value = res.content;
                document.getElementById('editStatus').value = res.status;
                
                // Afficher les pièces jointes
                const attachmentsList = document.getElementById('attachmentsList');
                attachmentsList.innerHTML = res.attachments && res.attachments.length > 0 ? 
                    res.attachments.map(att => 
                        `<div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-paperclip me-2"></i>${att.name}</span>
                            <button class="btn btn-sm btn-outline-danger" data-attachment="${att.name}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`
                    ).join('') : 
                    '<div class="text-muted">Aucune pièce jointe</div>';
            } else {
                form.reset();
                document.getElementById('editId').value = '';
                document.getElementById('displayId').value = generateResponseId();
                document.getElementById('displayDate').value = formatDate(new Date());
                document.getElementById('editStatus').value = 'pending';
                document.getElementById('attachmentsList').innerHTML = '<div class="text-muted">Aucune pièce jointe</div>';
            }

            // Configurer le modal selon l'action
            document.getElementById('modalTitle').textContent = 
                action === 'view' ? 'Détails de la Réponse' : 
                action === 'edit' ? 'Modifier la Réponse' : 'Nouvelle Réponse';
            
            // Afficher/masquer les boutons selon l'action
            document.getElementById('deleteBtn').style.display = 
                (action === 'view' || action === 'edit') ? 'inline-block' : 'none';
            document.getElementById('saveBtn').style.display = 
                action === 'view' ? 'none' : 'inline-block';
            
            // Activer/désactiver les champs selon l'action
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.id !== 'editAttachments') {
                    input.disabled = action === 'view';
                }
            });
            
            modal.show();
        }

        // Enregistrer
        document.getElementById('saveBtn').addEventListener('click', () => {
            const id = document.getElementById('editId').value;
            const resData = {
                id: document.getElementById('displayId').value,
                reclamationId: document.getElementById('editReclamation').value,
                author: document.getElementById('editAuthor').value,
                content: document.getElementById('editContent').value,
                status: document.getElementById('editStatus').value,
                date: id === '' ? new Date().toISOString() : responses[id].date,
                attachments: id === '' ? [] : responses[id].attachments
            };

            if (id === '') {
                // Nouvelle réponse
                responses.push(resData);
            } else {
                // Mise à jour
                responses[id] = resData;
            }

            localStorage.setItem('responses', JSON.stringify(responses));
            renderResponses(currentPage);
            bootstrap.Modal.getInstance('#crudModal').hide();
        });

        // Supprimer
        document.getElementById('deleteBtn').addEventListener('click', () => {
            const id = document.getElementById('editId').value;
            if (confirm('Voulez-vous vraiment supprimer cette réponse ? Cette action est irréversible.')) {
                responses.splice(id, 1);
                localStorage.setItem('responses', JSON.stringify(responses));
                renderResponses(currentPage);
                bootstrap.Modal.getInstance('#crudModal').hide();
            }
        });

        // Exporter les données
        document.getElementById('exportBtn').addEventListener('click', () => {
            let csvContent = "ID,Réclamation,Contenu,Auteur,Statut,Date\n";
            
            filteredResponses.forEach(res => {
                const rec = reclamations.find(r => r.id === res.reclamationId) || {};
                csvContent += `"${res.id}","${rec.id || ''} - ${rec.subject || ''}","${res.content}",` +
                             `"${res.author}","${getStatusText(res.status)}","${formatDate(res.date)}"\n`;
            });
            
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.setAttribute('download', `reponses_${formatDate(new Date())}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            alert('Export des réponses effectué avec succès!');
        });

        // Gestion des filtres
        document.querySelectorAll('#filterStatus, #filterReclamation, #filterDate').forEach(filter => {
            filter.addEventListener('change', () => {
                renderResponses(1); // Retour à la première page
            });
        });

        // Événements
        document.getElementById('addBtn').addEventListener('click', () => openModal(null, 'create'));
        
        // Déléguation des événements pour les boutons dynamiques
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-btn') || e.target.parentElement.classList.contains('view-btn')) {
                const btn = e.target.classList.contains('view-btn') ? e.target : e.target.parentElement;
                openModal(btn.dataset.id, 'view');
            }
            else if (e.target.classList.contains('edit-btn') || e.target.parentElement.classList.contains('edit-btn')) {
                const btn = e.target.classList.contains('edit-btn') ? e.target : e.target.parentElement;
                openModal(btn.dataset.id, 'edit');
            }
            else if (e.target.classList.contains('delete-btn') || e.target.parentElement.classList.contains('delete-btn')) {
                const btn = e.target.classList.contains('delete-btn') ? e.target : e.target.parentElement;
                if (confirm('Supprimer cette réponse ? Cette action est irréversible.')) {
                    responses.splice(btn.dataset.id, 1);
                    localStorage.setItem('responses', JSON.stringify(responses));
                    renderResponses(currentPage);
                }
            }
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            populateReclamationsFilter();
            renderResponses();
            
            // Simuler un délai de chargement pour le spinner
            setTimeout(() => {
                document.getElementById('spinner').classList.remove('show');
            }, 500);
        });
    </script>
</body>
</html>