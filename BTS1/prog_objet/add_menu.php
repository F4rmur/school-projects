<?php

// Script pour ajouter un lien menu en console
// Usage: php BTS1\prog_objet\add_menu.php "nom_du_lien" "url_du_lien"
require_once 'controllers/pdo.php';

if ($argc < 3) {
    echo "Usage: php BTS1\prog_objet\add_menu.php \"nom_du_lien\" \"url_du_lien\"\n";
    exit(1);
}

$nom_lien = trim($argv[1]);
$lien = trim($argv[2]);

if (empty($nom_lien) || empty($lien)) {
    echo "Erreur: Les champs nom_du_lien et url_du_lien sont obligatoires.\n";
    exit(1);
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO menu (nom_lien, lien) VALUES (?, ?)");
    $stmt->execute([$nom_lien, $lien]);
    echo "Le lien menu \"$nom_lien\" a été ajouté avec succès !\n";
} catch (PDOException $e) {
    echo "Erreur lors de l'ajout: " . $e->getMessage() . "\n";
    exit(1);
}

?>