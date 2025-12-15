<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title> 
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="accueil.css">
</head>
<body>
    <main>
        <?php
            use controllers\actualite as actualite;
            include('header.php');
            //$actu1 = actualite::creeActu1();

            echo'<h1> liste des actualités </h1>';
            echo
                '<a href="./detail_actualite"><div class="actualite">
                    <h3>actualité 1</h3>
                    <p>contenu de l\'actualité 1</p>
                </div></a>';
            echo
                '<div class="actualite">
                    <h3>actualité 2</h3>
                    <p>contenu de l\'actualité 2</p>
                </div>';
            echo
                '<div class="actualite">
                    <h3>actualité 3</h3>
                    <p>contenu de l\'actualité 3</p>
                </div>';
            echo
                '<div class="actualite">
                    <h3>actualité 4</h3>
                    <p>contenu de l\'actualité 4</p>
                </div>';
            echo
                '<div class="actualite">
                    <h3>actualité 5</h3>
                    <p>contenu de l\'actualité 5</p>
                </div>';

            include('footer.php');
        ?>
    </main>