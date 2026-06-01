<?php
/**
 * Accueil view expects $actualites (array of App\Models\Actualite)
 */
?>
<main>
    <h1>liste des actualités</h1>
    <?php if (!empty($actualites)): ?>
        <?php foreach ($actualites as $actu): ?>
            <?php
                $preview = '';
                foreach ($actu->getContenus() as $contenu) {
                    if (!empty($contenu) && ($contenu['type'] ?? '') === 'texte') {
                        $preview = htmlspecialchars($contenu['data']);
                        break;
                    }
                }
            ?>
            <a href="/prog_objet/BTS1/prog_objet/detail_actualite?id=<?php echo $actu->getId(); ?>">
                <div class="actualite">
                    <h3><?php echo htmlspecialchars($actu->getTitre()); ?></h3>
                    <p><?php echo $preview; ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune actualité trouvée.</p>
    <?php endif; ?>
</main>
