<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title> 
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/CSS/base.css">
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/CSS/accueil.css">
</head>
<body>
    <main>
        <?php
            include('pdo.php');
            include('header.php');
            include('controllers/Actualite.php');

            echo'<h1> liste des actualités </h1>';
            $actualites = Actualite::getLatest(5);
            foreach ($actualites as $actu) {
                $preview = '';
                foreach ($actu->getContenus() as $contenu) {
                    if ($contenu['type'] == 'texte') {
                        $preview = htmlspecialchars($contenu['data']);
                        break;
                    }
                }
                echo '<a href="./detail_actualite?id=' . $actu->getId() . '"><div class="actualite">
                    <h3>' . htmlspecialchars($actu->getTitre()) . '</h3>
                    <p>' . $preview . '</p>
                </div></a>';
            }

            include('footer.php');
        ?>
    </main>