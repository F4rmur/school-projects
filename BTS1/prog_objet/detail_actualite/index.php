<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title> 
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="detail_actualite.css">
</head>
<body>
    <main>
        <?php
            include('../header.php');

            echo'<h1> actualité du date </h1>';
            echo
                '<div class="actualite">
                    <h3>actualité spécifique</h3>
                    <p>contenu détaillé de l\'actualité spécifique</p>
                </div>';

            include('../footer.php');
        ?>
    </main>