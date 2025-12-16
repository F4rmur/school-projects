<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'actualité</title> 
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/CSS/base.css">
    <link rel="stylesheet" href="/prog_objet/BTS1/prog_objet/CSS/detail_actualite.css">
</head>
<body>
    <main>
        <?php
            include(dirname(__DIR__) . '/header.php');
            include(dirname(__DIR__) . '/controllers/Actualite.php');

            $id = $_GET['id'] ?? null;
            $actu = null;
            if ($id) {
                try {
                    $db = getDB();
                    $stmt = $db->prepare("SELECT Id_actu, Titre, contenus, date FROM actualite WHERE Id_actu = ?");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch();
                    if ($row) {
                        $actu = new Actualite($row['Id_actu'], $row['Titre'], json_decode($row['contenus'], true), $row['date']);
                    }
                } catch (PDOException $e) {
                    // Handle error
                }
            }

            echo '<h1>Détail de l\'actualité</h1>';
            if ($actu) {
                echo '<div class="actualite">';
                echo '<h3>' . htmlspecialchars($actu->getTitre()) . '</h3>';
                echo '<p><strong>Date :</strong> ' . htmlspecialchars($actu->getDate()) . '</p>';
                $actu->afficheActu();
                echo '</div>';
            } else {
                echo '<p>Actualité non trouvée.</p>';
            }

            include(dirname(__DIR__) . '/footer.php');
        ?>
    </main>