CREATE TABLE IF NOT EXISTS `mc_advmulti` (
  `id_advmulti` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `icon_advmulti` varchar(80) NOT NULL,
  `module_advmulti` varchar(25) NOT NULL DEFAULT 'home',
  `id_module` int(11) DEFAULT NULL,
  `order_advmulti` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_advmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_advmulti_content` (
  `id_advmulti_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_advmulti` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_advmulti` varchar(125) NOT NULL,
  `blank_advmulti` smallint(1) unsigned NOT NULL default 0,
  `title_advmulti` varchar(125) NOT NULL,
  `desc_advmulti` text,
  `published_advmulti` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_advmulti_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_advmulti` (`id_advmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_advmulti_content`
  ADD CONSTRAINT `mc_advmulti_content_ibfk_1` FOREIGN KEY (`id_advmulti`) REFERENCES `mc_advmulti` (`id_advmulti`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_advmulti_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;