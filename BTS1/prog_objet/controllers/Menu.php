<?php
namespace Controllers\Database;

class Menu extends Database
{
    private int $id_lien;
    private string $nom_lien;
    private string $lien;

    public function __construct(int $id_lien, string $nom_lien, string $lien) {
        $this->id_lien = $id_lien;
        $this->nom_lien = $nom_lien;
        $this->lien = $lien;
    }

    public function getIdLien() {
        return $this->id_lien;
    }

    public function getNomLien() {
        return $this->nom_lien;
    }

    public function getLien() {
        return $this->lien;
    }

    public static function getAll() {
        $db = self::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id_lien, nom_lien, lien FROM menu ORDER BY id_lien");
        $stmt->execute();
        $menus = [];
        while ($row = $stmt->fetch()) {
            $menus[] = new Menu($row['id_lien'], $row['nom_lien'], $row['lien']);
        }
        return $menus;
    }
}

?>