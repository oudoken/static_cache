CREATE TABLE static_cache_cpages (
	`scache_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`scache_key` varchar(255) NOT NULL default '',
	`scache_url` varchar(255) NOT NULL default '',
	`scache_path` varchar(255) NOT NULL default '',
	`scache_lastmod` varchar(15) NOT NULL default '',
	PRIMARY KEY (scache_id)
) ENGINE=MyISAM;