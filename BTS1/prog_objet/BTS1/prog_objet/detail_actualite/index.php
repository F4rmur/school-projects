<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'actualité</title> 
    <link rel="stylesheet" href="../base.css">
    <link rel="stylesheet" href="detail_actualite.css">
</head>
<body>
    <main>
        <?php
            include(dirname(__DIR__) . '/header.php');
            // include(dirname(__DIR__) . '/pdo.php');
            // $db = getDB();

            echo'<h1> Détail de l\'actualité </h1>';
            echo
                '<div class="actualite">
                    <h3>actualité spécifique</h3>
                    <p>contenu détaillé de l\'actualité spécifique</p>
                </div>';

            include(dirname(__DIR__) . '/footer.php');
        ?>
    </main>