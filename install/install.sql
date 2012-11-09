--
-- Tabellenstruktur für Tabelle `plugins_personalnews`
--

CREATE TABLE IF NOT EXISTS `plugins_personalnews` (
  `newsid` varchar(32) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  PRIMARY KEY (`newsid`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;