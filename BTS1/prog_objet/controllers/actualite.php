<?php

class actualite
{
    private $id;
    private $contenus = [];
    private $titre = '';
    private $date = '';

    public function __construct($id, $titre, $contenus, $date) {
        $this->id = $id;
        $this->titre = $titre;
        $this->contenus = $contenus;
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getContenus() {
        return $this->contenus;
    }

    public function getDate() {
        return $this->date;
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

    public static function getLatest($limit = 5) {
        $db = getDB();
        $stmt = $db->prepare("SELECT Id_actu, Titre, contenus, date FROM actualite ORDER BY date DESC LIMIT ?");
        $stmt->execute([$limit]);
        $actualites = [];
        while ($row = $stmt->fetch()) {
            $actualites[] = new actualite($row['Id_actu'], $row['Titre'], json_decode($row['contenus'], true), $row['date']);
        }
        return $actualites;
    }
}


?>