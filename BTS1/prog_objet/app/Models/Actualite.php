<?php
namespace App\Models;

class Actualite extends Database
{
    private int $id;
    private array $contenus = [];
    private string $titre = '';
    private string $date = '';

    public function __construct(int $id, string $titre, array $contenus, string $date) {
        $this->id = $id;
        $this->titre = $titre;
        $this->contenus = $contenus;
        $this->date = $date;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getContenus(): array {
        return $this->contenus;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function afficheActu(): void {
        foreach ($this->contenus as $contenu)
            if ($contenu != null)
                if ($contenu['type'] == 'texte') {
                    echo '<p>' . htmlspecialchars($contenu['data']) . '</p>';
                } elseif ($contenu['type'] == 'image') {
                    echo '<img src="' . htmlspecialchars($contenu['data']) . '" alt="Image">';
                } elseif ($contenu['type'] == 'video') {
                    echo '<video controls><source src="' . htmlspecialchars($contenu['data']) . '" type="video/mp4">La vidéo n\'t est pas supportée</video>';
                }
    }

    public static function getLatest(int $limit = 5): array {
        $db = self::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT Id_actu, Titre, contenus, date FROM actualite ORDER BY date DESC LIMIT ?");
        $stmt->execute([$limit]);
        $actualites = [];
        while ($row = $stmt->fetch()) {
            $actualites[] = new Actualite((int)$row['Id_actu'], $row['Titre'], json_decode($row['contenus'], true), $row['date']);
        }
        return $actualites;
    }
}
