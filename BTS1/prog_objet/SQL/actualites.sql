-- dans la base de données actualites

CREATE TABLE IF NOT EXISTS `actualite` (
  `Id_actu` int NOT NULL AUTO_INCREMENT,
  `Titre` text NOT NULL,
  `contenus` json NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`Id_actu`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `menu` (
  `id_lien` int NOT NULL AUTO_INCREMENT,
  `nom_lien` text NOT NULL,
  `lien` text NOT NULL,
  PRIMARY KEY (`id_lien`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;