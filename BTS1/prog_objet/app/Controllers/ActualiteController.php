<?php
namespace App\Controllers;

use App\Models\Actualite;
use App\Models\Menu;

class ActualiteController {
    public function index(): void {
        $actualites = Actualite::getLatest(5);
        $menus = Menu::getAll();

        require __DIR__ . '/../Views/header.php';
        require __DIR__ . '/../Views/accueil.php';
        require __DIR__ . '/../Views/footer.php';
    }

    public function detail(int $id): void {
        // Simple detail placeholder — can be extended
        $actualites = Actualite::getLatest(5);
        $menus = Menu::getAll();

        require __DIR__ . '/../Views/header.php';
        echo '<main><h2>Détail de l\'actualité #' . htmlspecialchars((string)$id) . '</h2></main>';
        require __DIR__ . '/../Views/footer.php';
    }
}
