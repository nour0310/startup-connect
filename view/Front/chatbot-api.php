<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$API_KEY = 'AIzaSyAFIiLtvT_k9vRGNLU7z0A-YIWs20rRWJE';
$API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $ch = curl_init($API_URL . '?key=' . $API_KEY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => 'API request failed: ' . curl_error($ch)]);
        exit;
    }
    
    curl_close($ch);
    
    http_response_code($httpCode);
    echo $response;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>