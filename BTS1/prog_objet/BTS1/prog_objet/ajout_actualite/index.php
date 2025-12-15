<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une actualité</title> 
    <link rel="stylesheet" href="../base.css">
    <link rel="stylesheet" href="ajout_actualite.css">
</head>
<body>
    <main>
        <?php
            include(dirname(__DIR__) . '/header.php');
            //include(dirname(__DIR__) . '/pdo.php');

            // Simulation de traitement du formulaire (sans base de données)
            $message = '';
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                $date_creation = $_POST['date_creation'] ?? date('Y-m-d');

                if (empty($titre) || empty($contenu)) {
                    $message = '<div class="message error">Veuillez remplir tous les champs obligatoires.</div>';
                } else {
                    $message = '<div class="message success">L\'actualité "' . htmlspecialchars($titre) . '" a été ajoutée avec succès ! (Simulation - pas de sauvegarde en base)</div>';
                }
            }
        ?>

        <div class="form-ajout">
            <h1>Ajouter une actualité</h1>

            <?php echo $message; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="titre">Titre de l'actualité *</label>
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="contenu">Contenu de l'actualité *</label>
                    <textarea id="contenu" name="contenu" rows="8" required><?php echo htmlspecialchars($_POST['contenu'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="date_creation">Date de création</label>
                    <input type="date" id="date_creation" name="date_creation" value="<?php echo htmlspecialchars($_POST['date_creation'] ?? date('Y-m-d')); ?>">
                </div>

                <button type="submit" class="btn-submit">Ajouter l'actualité</button>
            </form>
        </div>
    </main>