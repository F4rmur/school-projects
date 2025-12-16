<header>
    <?php
        include('pdo.php');
        include('controllers/Menu.php');
        $menus = Menu::getAll();
    ?>
    <a href='/prog_objet/BTS1/prog_objet/index.php'><img src="/prog_objet/BTS1/prog_objet/ressources/home_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="accueil" name='accueil_icon'></a>
    <a href='/prog_objet/BTS1/prog_objet/ajout_actualite/index.php'><img src="/prog_objet/BTS1/prog_objet/ressources/add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="ajout actualité" name='ajout_icon'></a>
    <details class="menu-dropdown">
        <summary>Menu</summary>
        <ul>
            <?php foreach ($menus as $menu): ?>
                <li><a href="<?php echo htmlspecialchars($menu->getLien()); ?>"><?php echo htmlspecialchars($menu->getNomLien()); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </details>
</header>