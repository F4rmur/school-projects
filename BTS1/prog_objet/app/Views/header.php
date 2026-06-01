<?php
/**
 * Header view expects $menus (array of App\\Models\\Menu)
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/public/CSS/base.css">
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/public/CSS/accueil.css">
</head>
<body>
<header>
    <?php if (isset($menus) && is_array($menus)): ?>
        <a href='/prog_objet/BTS1/prog_objet/public/index.php'><img src="/prog_objet/BTS1/prog_objet/public/ressources/home_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="accueil"></a>
        <a href='/prog_objet/BTS1/prog_objet/public/index.php?route=ajout_actualite'><img src="/prog_objet/BTS1/prog_objet/public/ressources/add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="ajout"></a>
        <details class="menu-dropdown">
            <summary>Menu</summary>
            <ul>
                <?php foreach ($menus as $menu): ?>
                    <li><a href="<?php echo htmlspecialchars($menu->getLien()); ?>"><?php echo htmlspecialchars($menu->getNomLien()); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </details>
    <?php endif; ?>
</header>

