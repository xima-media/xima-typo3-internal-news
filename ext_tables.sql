CREATE TABLE tx_ximatypo3internalnews_domain_model_news
(
	uid           int(11) NOT NULL auto_increment,
	pid           int(11) DEFAULT '0' NOT NULL,
	tstamp        int(11) DEFAULT '0' NOT NULL,
	crdate        int(11) DEFAULT '0' NOT NULL,
	deleted       tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden        tinyint(4) unsigned DEFAULT '0' NOT NULL,

	title       varchar(255)  DEFAULT '' NOT NULL,
	description varchar(2000) DEFAULT '' NOT NULL,
	top         tinyint(4) unsigned DEFAULT 0 NOT NULL,
	image       int (11) unsigned DEFAULT '0' NOT NULL,
	dates       int(11) unsigned DEFAULT '0' NOT NULL,
	be_group    varchar(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid)
);

CREATE TABLE tx_ximatypo3internalnews_domain_model_date
(
	uid            int(11) NOT NULL auto_increment,
	title          varchar(255) DEFAULT '' NOT NULL,
	type           varchar(32)  DEFAULT '' NOT NULL,
	recurrence     varchar(255) DEFAULT '' NOT NULL,
	notify         tinyint(4) unsigned DEFAULT 0 NOT NULL,
	notify_type    varchar(32)  DEFAULT '' NOT NULL,
	notify_message varchar(255)  DEFAULT '' NOT NULL,
	news           int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);
