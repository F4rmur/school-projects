<?php

class Menu extends Database
{
    private $id_lien;
    private $nom_lien;
    private $lien;

    public function __construct($id_lien, $nom_lien, $lien) {
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