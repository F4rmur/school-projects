<?php
class actualite
{
    private $contenus = [];
    private $titre = '';
    private $date = '';

    public function __construct($titre, $contenus, $date) {
        $this->titre = $titre;
        $this->contenus = $contenus;
        $this->date = $date;
    }

    public function afficheActu() {
        foreach ($this->contenus as $contenu)
            if ($contenu != null)
                if ($contenu['type'] == 'texte') {
                    echo '<p>' . htmlspecialchars($contenu['data']) . '</p>';
                } elseif ($contenu['type'] == 'image') {
                    echo '<img src="' . htmlspecialchars($contenu['data']) . '" alt="Image">';
                } elseif ($contenu['type'] == 'video') {
                    echo '<video controls><source src="' . htmlspecialchars($contenu['data']) . '" type="video/mp4">La vidéo n\'est pas supportée</video>';
                }
    }

    public function creeActu1() {
        $actu = new actualite(
            "Nouvelle mise à jour disponible",
            [
                ['type' => 'texte', 'data' => 'Nous sommes ravis d\'annoncer la sortie de la nouvelle mise à jour de notre application.'],
                ['type' => 'image', 'data' => '/prog_objet/ressources/update_image.png'],
                ['type' => 'texte', 'data' => 'Cette mise à jour inclut plusieurs améliorations de performance et de nouvelles fonctionnalités.'],
                ['type' => 'video', 'data' => '/prog_objet/ressources/update_video.mp4']
            ],
            '2024-06-15'
        );
        return $actu;
    }

    public function creeActu2() {
        $actu = new actualite(
            "Nouvelle mise à jour disponible",
            [
                ['type' => 'texte', 'data' => 'lorem    sdqlsdklqskd ravis d\'annoncer la sortie de la nouvelle mise à jour de notre application.'],
                ['type' => 'image', 'data' => '/prog_objet/ressources/update_image.png'],
                ['type' => 'texte', 'data' => 'Cette mise à jour inclut plusieurs améliorations de performance et de nouvelles fonctionnalités.'],
                ['type' => 'video', 'data' => '/prog_objet/ressources/update_video.mp4']
            ],
            '2024-09-15'
        );
        return $actu;
    }
}


?>