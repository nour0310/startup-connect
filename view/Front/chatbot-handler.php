<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Receive the message
$data = json_decode(file_get_contents('php://input'), true);
$message = strtolower($data['message'] ?? '');

// Define responses for different startup categories
$responses = [
    'technologie' => [
        'Voici nos startups dans la catégorie Technologie:',
        '• Figma (2.7★) - Plateforme de design collaboratif en ligne',
        '• Autres startups technologiques à venir'
    ],
    'sante' => [
        'Dans la catégorie Santé, nous avons:',
        '• Hygiene (4.0★) - Solutions de suivi santé en temps réel',
        '• D\'autres startups innovantes en santé'
    ],
    'education' => [
        'Pour l\'éducation, découvrez:',
        '• TakiAcademy (1.5★) - Plateforme de cours en ligne interactifs',
        '• Plus de startups éducatives à venir'
    ],
    'finance' => [
        'Dans le domaine de la finance:',
        '• Qonto (5.0★) - Néobanque pour professionnels et PME',
        '• D\'autres solutions financières innovantes'
    ],
    'ecommerce' => [
        'Pour l\'e-commerce:',
        '• Solutions de paiement sécurisées',
        '• Plateformes de vente en ligne'
    ]
];

// Keywords for different types of queries
$keywords = [
    'evaluation' => ['note', 'etoile', 'evaluation', 'avis', 'rating'],
    'categories' => ['categorie', 'domaine', 'secteur', 'type'],
    'startups' => ['startup', 'entreprise', 'projet'],
    'help' => ['aide', 'help', 'aidez', 'comment']
];

// Check for unrelated topics
$unrelated = ['meteo', 'temps', 'heure', 'date', 'jour', 'mois', 'annee', 'politique', 'sport', 'cinema', 'musique', 'restaurant', 'covid', 'vaccin'];
foreach ($unrelated as $topic) {
    if (strpos($message, $topic) !== false) {
        echo json_encode([
            'success' => true,
            'response' => "Je suis désolé, je suis uniquement conçu pour répondre aux questions concernant les startups et services de notre plateforme StartupConnect. Pour d'autres types de questions, veuillez utiliser un moteur de recherche ou un assistant général."
        ]);
        exit;
    }
}

// Check for greetings
if (preg_match('/^(bonjour|salut|hello|hi|hey|bonsoir)/', $message)) {
    echo json_encode([
        'success' => true,
        'response' => "Bonjour! Je suis l'assistant StartupConnect. Je peux vous aider à découvrir nos startups dans différentes catégories. Quelle information recherchez-vous ?"
    ]);
    exit;
}

// Check for thanks
if (preg_match('/(merci|thanks|thank you|thx)/', $message)) {
    echo json_encode([
        'success' => true,
        'response' => "Je vous en prie! N'hésitez pas si vous avez d'autres questions sur nos startups."
    ]);
    exit;
}

// Process the message and generate response
$response = '';

// Check for category-specific queries
foreach ($responses as $category => $info) {
    if (strpos($message, $category) !== false) {
        echo json_encode([
            'success' => true,
            'response' => implode("\n", $info)
        ]);
        exit;
    }
}

// Check for evaluation-related queries
if (array_intersect(explode(' ', $message), $keywords['evaluation'])) {
    echo json_encode([
        'success' => true,
        'response' => "Notre système de notation va de 0 à 5 étoiles. Voici quelques exemples:\n• Qonto: 5.0★ (Finance)\n• Hygiene: 4.0★ (Santé)\n• Figma: 2.7★ (Technologie)\n• TakiAcademy: 1.5★ (Éducation)"
    ]);
    exit;
}

// Check for category-related queries
if (array_intersect(explode(' ', $message), $keywords['categories'])) {
    echo json_encode([
        'success' => true,
        'response' => "Nous avons plusieurs catégories de startups :\n• Technologie\n• Santé\n• Éducation\n• Finance\n• E-commerce\n\nQuelle catégorie vous intéresse ?"
    ]);
    exit;
}

// Default response for other queries
echo json_encode([
    'success' => true,
    'response' => "Je suis là pour vous aider avec les startups de notre plateforme. Je peux vous informer sur:\n• Les différentes catégories de startups\n• Les évaluations et avis\n• Les détails spécifiques des startups\n\nQue souhaitez-vous savoir ?"
]);
?>