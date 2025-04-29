<?php
require_once '../Model/AnalyticsModel.php';

class AnalyticsController {
    private $model;

    public function __construct() {
        $this->model = new AnalyticsModel();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            switch ($_GET['action'] ?? '') {
                case 'get_analytics':
                    $this->getAnalyticsData();
                    break;
                case 'record_view':
                    $this->recordView($_GET['startup_id'] ?? null);
                    break;
            }
        }
    }

    private function getAnalyticsData() {
        try {
            $data = $this->model->getTrendingStartups();
            
            if (!$data['success']) {
                throw new Exception($data['error']);
            }

            // Calculate metrics
            $response = [
                'success' => true,
                'totalViews' => $data['totalViews'],
                'averageRating' => 0,
                'trendingCount' => count($data['startups']),
                'topStartups' => $data['startups']
            ];

            // Calculate average rating
            $totalRating = 0;
            $ratedStartups = 0;
            foreach ($data['startups'] as $startup) {
                if ($startup['avg_rating']) {
                    $totalRating += $startup['avg_rating'];
                    $ratedStartups++;
                }
            }
            $response['averageRating'] = $ratedStartups ? round($totalRating / $ratedStartups, 1) : 0;

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function recordView($startupId) {
        if (!$startupId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Startup ID required']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'] ?? null;
            $success = $this->model->recordStartupView($startupId, $userId);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

$controller = new AnalyticsController();
$controller->handleRequest();
