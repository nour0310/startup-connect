<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../../config.php';
require_once '../../Controller/startupC.php';
require_once '../../Model/category.php';

$controller = new StartupController();
$startups = $controller->getAllStartups();

$categoryModel = new CategoryModel();
$categories = $categoryModel->getAllCategories();

// Message handling
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear the messages
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Function to get category icon
function getCategoryIcon($categoryId) {
    $icons = [
        1 => 'fas fa-microchip',
        2 => 'fas fa-heartbeat',
        3 => 'fas fa-graduation-cap',
        4 => 'fas fa-chart-line',
        5 => 'fas fa-shopping-cart'
    ];
    return $icons[$categoryId] ?? 'fas fa-folder'; // Default icon if no match
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Startup - Gestion des Startups</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Gestion des startups" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Enhanced custom styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(6, 163, 218, 0.2);
        }

        .startup-image {
            height: 220px;
            transition: all 0.5s ease;
        }

        .card:hover .startup-image {
            transform: scale(1.05);
        }

        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        #searchInput {
            border-radius: 25px;
            padding-left: 20px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        #searchInput:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 0 0.2rem rgba(6, 163, 218, 0.25);
        }

        .filter-btn {
            border-radius: 25px;
            padding: 8px 25px;
            background: #06A3DA;
            border: none;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: #058bb8;
            transform: translateY(-2px);
        }

        .sidebar {
            background: linear-gradient(145deg, #ffffff, #f5f5f5);
            border-radius: 15px;
        }

        .sidebar ul li a {
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: rgba(6, 163, 218, 0.1);
            padding-left: 20px;
        }

        .sidebar ul li a.active {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
        }

        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }

        .btn-primary {
            border-radius: 25px;
            padding: 8px 20px;
            background: #06A3DA;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #058bb8;
            transform: translateY(-2px);
        }

        /* Modal enhancements */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 25px;
        }

        /* Alert animations */
        .alert {
            border-radius: 10px;
            animation: slideInDown 0.5s ease;
        }

        /* Category icons */
        .category-icon {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .rating-input .stars {
            font-size: 2em;
            cursor: pointer;
        }

        .rating-input .stars i {
            padding: 0.2em;
            color: #ffc107;
            transition: all 0.2s ease;
        }

        .rating-input .stars i:hover,
        .rating-input .stars i.active {
            transform: scale(1.2);
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }

        .rating-display .stars {
            color: #ffc107;
            font-size: 1.1em;
        }

        .rating-value {
            font-weight: bold;
            color: #6c757d;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .rating-success {
            animation: pulse 0.5s ease;
        }

        .form-select {
            padding: 0.75rem 1rem;
            border-radius: 25px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-select:focus {
            border-color: #06A3DA;
            box-shadow: 0 0 0 0.2rem rgba(6, 163, 218, 0.25);
        }

        #ratingFilter {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .category-filter {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .category-filter:hover {
            background: rgba(6, 163, 218, 0.1);
            color: #06A3DA;
            text-decoration: none;
            transform: translateX(5px);
        }

        .category-filter.active {
            background: linear-gradient(145deg, #06A3DA, #0590c0);
            color: white;
        }

        .category-icon {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>

<body>
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
                <div class="d-inline-flex align-items-center" style="height: 45px;"></div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="background-color: #06A3DA;">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.html" class="nav-item nav-link">Acceuil</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="dashboard.html" class="dropdown-item">Dashboard</a>
                            <a href="#" class="dropdown-item">Gestion utilisateurs</a>
                            <a href="#" class="dropdown-item">Gestion profiles</a>
                            <a href="gestion-startup.php" class="dropdown-item">Gestion Startup</a>
                            <a href="#" class="dropdown-item">Gestion evénements</a>
                            <a href="gestionInvestissement.html" class="dropdown-item">Gestion des investissements</a>
                            <a href="#" class="dropdown-item">Gestion documents</a>
                        </div>
                    </div>
                    <a href="login.html" class="nav-item nav-link">Connexion</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Main Content -->
    <div class="container py-5" style="margin-top: 100px !important;">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Catégories</h4>
                    <ul class="list-unstyled" id="categoryList">
                        <li>
                            <a href="#" class="category-filter active" data-category="0">
                                <i class="fas fa-th category-icon"></i>Toutes les catégories
                            </a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="#" class="category-filter" data-category="<?php echo htmlspecialchars($category['id']); ?>">
                                    <i class="<?php echo getCategoryIcon($category['id']); ?> category-icon"></i>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-12">
                        <h1>Liste des Startups</h1>
                    </div>
                </div>

                <!-- Search Bar and Filter -->
                <div class="row search-bar mt-3 animate__animated animate__fadeIn">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une startup...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="ratingFilter">
                            <option value="">Trier par étoiles</option>
                            <option value="5">5 étoiles</option>
                            <option value="4">4+ étoiles</option>
                            <option value="3">3+ étoiles</option>
                            <option value="2">2+ étoiles</option>
                            <option value="1">1+ étoile</option>
                            <option value="most">Les mieux notées</option>
                        </select>
                    </div>
                </div>

                <!-- Gallery of Startups -->
                <div class="row mt-4" id="startupGallery">
                    <?php if(empty($startups)): ?>
                        <div class="col-12 text-center">
                            <p>Aucune startup trouvée.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($startups as $startup): ?>
                            <div class="col-md-4 mb-4 startup-card" data-category="<?php echo $startup['category_id']; ?>">
                                <div class="card">
                                    <?php 
                                        // Handle image path
                                        $defaultImage = '/startupConnect-website/uploads/defaults/default-startup.png';
                                        if (!empty($startup['image_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $startup['image_path'])) {
                                            $imagePath = $startup['image_path'];
                                        } else {
                                            $imagePath = $defaultImage;
                                        }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         class="startup-image" 
                                         alt="<?php echo htmlspecialchars($startup['name']); ?>"
                                         onerror="this.onerror=null; this.src='<?php echo $defaultImage; ?>';">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($startup['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($startup['description'], 0, 100)) . '...'; ?></p>
                                        <p class="badge bg-info"><?php echo htmlspecialchars($startup['category_name']); ?></p>
                                        <div class="rating-display mb-2">
                                            <div class="stars">
                                                <?php
                                                    $rating = isset($startup['average_rating']) ? floatval($startup['average_rating']) : 0;
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $rating) {
                                                            echo '<i class="fas fa-star text-warning"></i>';
                                                        } elseif ($i - 0.5 <= $rating) {
                                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star text-warning"></i>';
                                                        }
                                                    }
                                                ?>
                                                <span class="rating-value ms-2"><?php echo number_format($rating, 1); ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-outline-warning btn-sm me-2" onclick="rateStartup(<?php echo $startup['id']; ?>)">
                                                <i class="fas fa-star me-1"></i>Noter
                                            </button>
                                            <a href="#" class="btn btn-primary btn-sm view-startup" data-id="<?php echo $startup['id']; ?>">Voir plus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

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

    <!-- Chatbot Container -->
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chat-header">
            <h5>StartupConnect Assistant</h5>
            <button class="minimize-btn" id="minimizeChatbot">−</button>
        </div>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
            <textarea id="userInput" placeholder="Posez votre question ici..." rows="1"></textarea>
            <button id="sendMessage">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <button class="chat-toggle" id="toggleChat">
        <i class="fas fa-comments"></i>
    </button>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatbotContainer');
        const toggleButton = document.getElementById('toggleChat');
        const minimizeButton = document.getElementById('minimizeChatbot');
        const messagesContainer = document.getElementById('chatMessages');
        const userInput = document.getElementById('userInput');
        const sendButton = document.getElementById('sendMessage');

        let isChatVisible = false;
        let isProcessing = false;

        // Welcome messages
        const welcomeMessages = [
            "Bonjour! Je suis l'assistant StartupConnect. Je peux vous aider avec :",
            "• Des informations sur les startups\n• La recherche de startups par catégorie\n• Des détails sur les services disponibles\n• Le processus d'évaluation des startups\n\nQue souhaitez-vous savoir ?"
        ];

        const fallbackResponses = {
            'startup': "Nous avons plusieurs startups intéressantes dans différentes catégories. Je peux vous donner plus d'informations sur une catégorie spécifique.",
            'categorie': "Les catégories disponibles sont : Technologie, Santé, Éducation, Finance, et E-commerce. Quelle catégorie vous intéresse ?",
            'technologie': "Dans la catégorie Technologie, nous avons des startups comme Figma, qui propose une plateforme de design collaboratif.",
            'sante': "La catégorie Santé comprend des startups innovantes comme 'hygiene' qui développe des solutions de suivi santé en temps réel.",
            'education': "En éducation, nous avons TakiAcademy qui propose des cours en ligne interactifs.",
            'finance': "Dans la finance, Qonto est un excellent exemple avec ses services bancaires pour professionnels.",
            'ecommerce': "Le secteur E-commerce comprend des solutions de paiement et des plateformes de vente en ligne.",
            'evaluation': "Les startups sont évaluées sur une échelle de 0 à 5 étoiles. Par exemple, Qonto a une note de 5.0, tandis que Figma a 2.7 étoiles.",
            'note': "Notre système de notation va de 0 à 5 étoiles. Les utilisateurs peuvent évaluer les startups en fonction de leur expérience.",
            'default': "Je suis uniquement dédié à fournir des informations sur les startups de notre plateforme. Je peux vous aider avec les catégories de startups, les évaluations, ou des informations spécifiques sur nos startups."
        };

        // Toggle chat visibility
        toggleButton.addEventListener('click', () => {
            isChatVisible = !isChatVisible;
            chatContainer.style.display = isChatVisible ? 'flex' : 'none';
            toggleButton.style.display = isChatVisible ? 'none' : 'flex';
            if (isChatVisible && messagesContainer.children.length === 0) {
                welcomeMessages.forEach(msg => addBotMessage(msg));
            }
        });

        // Minimize chat
        minimizeButton.addEventListener('click', () => {
            chatContainer.style.display = 'none';
            toggleButton.style.display = 'flex';
            isChatVisible = false;
        });

        // Handle textarea height
        userInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Handle Enter key
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Send button click
        sendButton.addEventListener('click', sendMessage);

        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
            
            // Convert URLs to clickable links
            const linkedText = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
            
            // Convert markdown-style bullet points to HTML
            const formattedText = linkedText.replace(/\n• /g, '<br>• ');
            
            messageDiv.innerHTML = formattedText;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function addBotMessage(text) {
            addMessage(text, false);
        }

        function addUserMessage(text) {
            addMessage(text, true);
        }

        function showTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'typing-indicator';
            indicator.innerHTML = `
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            `;
            indicator.id = 'typingIndicator';
            messagesContainer.appendChild(indicator);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function getFallbackResponse(message) {
            const lowerMessage = message.toLowerCase();
            
            // Check if the message is unrelated to the website
            const unrelatedKeywords = ['météo', 'temps', 'heure', 'date', 'jour', 'mois', 'année', 'politique', 'sport', 'cinéma', 'musique', 'restaurant', 'covid', 'vaccin'];
            if (unrelatedKeywords.some(keyword => lowerMessage.includes(keyword))) {
                return "Je suis désolé, je suis uniquement conçu pour répondre aux questions concernant les startups et services de notre plateforme StartupConnect. Pour d'autres types de questions, veuillez utiliser un moteur de recherche ou un assistant général.";
            }
            
            // Website-related responses
            for (const [key, response] of Object.entries(fallbackResponses)) {
                if (lowerMessage.includes(key.toLowerCase())) {
                    return response;
                }
            }

            // Check for greetings
            if (lowerMessage.match(/^(bonjour|salut|hello|hi|hey|bonsoir)/)) {
                return "Bonjour! Je suis l'assistant StartupConnect. Je peux vous aider à découvrir nos startups dans différentes catégories. Quelle information recherchez-vous ?";
            }

            // Check for thanks
            if (lowerMessage.match(/(merci|thanks|thank you|thx)/)) {
                return "Je vous en prie! N'hésitez pas si vous avez d'autres questions sur nos startups.";
            }

            return fallbackResponses.default;
        }

        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message || isProcessing) return;

            isProcessing = true;
            addUserMessage(message);
            userInput.value = '';
            userInput.style.height = 'auto';
            showTypingIndicator();

            try {
                const response = await fetch('chatbot-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                removeTypingIndicator();

                if (data.success && data.response) {
                    addBotMessage(data.response);
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Error details:', error);
                removeTypingIndicator();
                addBotMessage(getFallbackResponse(message));
            } finally {
                isProcessing = false;
            }
        }
    });
    </script>

    <style>
    .chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        display: none;
        flex-direction: column;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .chat-header {
        background: #06A3DA;
        color: white;
        padding: 15px;
        border-radius: 15px 15px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .minimize-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 0 10px;
        transition: all 0.3s ease;
    }

    .minimize-btn:hover {
        transform: scale(1.1);
    }

    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #f8f9fa;
    }

    .message {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 15px;
        margin: 5px 0;
        line-height: 1.4;
        font-size: 14px;
    }

    .message a {
        color: #06A3DA;
        text-decoration: none;
    }

    .message a:hover {
        text-decoration: underline;
    }

    .user-message {
        background: #06A3DA;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }

    .bot-message {
        background: white;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .chat-input {
        padding: 15px;
        border-top: 1px solid #dee2e6;
        display: flex;
        gap: 10px;
        align-items: flex-end;
        background: white;
        border-radius: 0 0 15px 15px;
    }

    .chat-input textarea {
        flex: 1;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 10px 15px;
        resize: none;
        max-height: 100px;
        min-height: 40px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .chat-input textarea:focus {
        outline: none;
        border-color: #06A3DA;
        box-shadow: 0 0 0 2px rgba(6, 163, 218, 0.1);
    }

    .chat-input button {
        background: #06A3DA;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chat-input button:hover {
        background: #0588b7;
        transform: scale(1.05);
    }

    .chat-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 30px;
        background: #06A3DA;
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .chat-toggle:hover {
        transform: scale(1.1);
        background: #0588b7;
    }

    .typing-indicator {
        display: flex;
        gap: 5px;
        padding: 10px 15px;
        background: white;
        border-radius: 15px;
        align-self: flex-start;
        margin: 5px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #06A3DA;
        border-radius: 50%;
        animation: typing 1s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: 0.2s; }
    .typing-dot:nth-child(2) { animation-delay: 0.3s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Custom scrollbar for chat messages */
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Add smooth fade-in animation for messages */
    .message {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
</body>
</html>