-- dans la base de données actualites

CREATE TABLE IF NOT EXISTS `menu` (
  `id_lien` int NOT NULL AUTO_INCREMENT,
  `nom_lien` text NOT NULL,
  `lien` text NOT NULL,
  PRIMARY KEY (`id_lien`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;