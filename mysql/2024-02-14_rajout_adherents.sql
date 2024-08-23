DROP TABLE IF EXISTS `adherent`;

CREATE TABLE `adherent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_naissance` text CHARACTER SET utf8 NOT NULL,
  `nom` text CHARACTER SET utf8 NOT NULL,
  `prenom` text CHARACTER SET utf8 NOT NULL,
  `localisation` text CHARACTER SET utf8 NOT NULL,
  `genre` text CHARACTER SET utf8 NOT NULL,
  `commentaire` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `adherent` WRITE;
/*!40000 ALTER TABLE `adherent` DISABLE KEYS */;
/*!40000 ALTER TABLE `adherent` ENABLE KEYS */;
UNLOCK TABLES;