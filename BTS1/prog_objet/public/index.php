<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ActualiteController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Very small router: adapt paths to your environment
if ($uri === '/' || strpos($uri, 'index.php') !== false || $uri === '/prog_objet/BTS1/prog_objet/') {
    (new ActualiteController())->index();
    exit;
}

if (strpos($uri, '/detail_actualite') !== false || strpos($uri, 'detail_actualite') !== false) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    (new ActualiteController())->detail($id);
    exit;
}

http_response_code(404);
echo 'Page non trouvée';
