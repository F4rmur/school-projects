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
            include(dirname(__DIR__) . '/pdo.php');


            // Traitement du formulaire
            $message = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                $date_creation = $_POST['date_creation'] ?? date('Y-m-d');

                if (empty($titre) || empty($contenu)) {
                    $message = '<div class="message error">Veuillez remplir tous les champs obligatoires.</div>';
                } else {
                    try {
                        $db = getDB();
                        $contenus_json = json_encode([['type' => 'texte', 'data' => $contenu]]);
                        $stmt = $db->prepare("INSERT INTO actualite (Titre, contenus, date) VALUES (?, ?, ?)");
                        $stmt->execute([$titre, $contenus_json, $date_creation]);
                        $message = '<div class="message success">L\'actualité "' . htmlspecialchars($titre) . '" a été ajoutée avec succès !</div>';
                    } catch (PDOException $e) {
                        $message = '<div class="message error">Erreur lors de l\'ajout : ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
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