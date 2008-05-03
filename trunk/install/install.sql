CREATE TABLE `applangs` (
  `applangID` int(11) NOT NULL auto_increment,
  `lang_name` char(100) default NULL,
  `lang_code` char(2) default NULL,
  `appfile` char(20) default NULL,
  PRIMARY KEY  (`applangID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `applangs` VALUES (1,'English','en','english.php'),(2,'Spanish','es','spanish.php'),(3,'Catalan','ca','catala.php'),(4,'Euskera','ek','euskera.php'),(5,'Greek','gk','greek.php'),(6,'Romanian','ro','romanian.php');

CREATE TABLE `comments` (
  `commentid` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `userID` int(10) unsigned NOT NULL default '0',
  `username` varchar(50) character set latin1 NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment` varchar(255) character set latin1 NOT NULL default '',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  USING BTREE (`commentid`,`subID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `downloads` (
  `dID` bigint(20) unsigned NOT NULL auto_increment,
  `userID` int(10) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `cuando` datetime default NULL,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` smallint(6) NOT NULL default '0',
  `lang` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`dID`),
  KEY `busq1` (`subID`,`fversion`,`lang`),
  KEY `busq2` (`subID`,`fversion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `files` (
  `subID` int(10) unsigned NOT NULL auto_increment,
  `author` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `is_episode` tinyint(1) NOT NULL default '0',
  `showID` mediumint(8) unsigned NOT NULL default '0',
  `season` smallint(5) unsigned NOT NULL default '0',
  `season_number` smallint(5) unsigned NOT NULL default '0',
  `finished` tinyint(1) NOT NULL default '0',
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `duration` time NOT NULL default '00:00:00',
  `comment` text collate utf8_unicode_ci NOT NULL,
  `temp` tinyint(1) NOT NULL default '0',
  `downloads` int(11) default '0',
  PRIMARY KEY  (`subID`),
  KEY `principal` (`subID`,`author`,`title`,`is_episode`,`finished`,`end_date`),
  KEY `serie` (`showID`,`season`,`season_number`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `flangs` (
  `entryID` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  `lang_id` tinyint(3) unsigned NOT NULL default '0',
  `state` float(5,2) NOT NULL default '100.00',
  `testing` tinyint(1) NOT NULL default '0',
  `original` tinyint(1) NOT NULL default '0',
  `totalseq` int(10) unsigned NOT NULL default '0',
  `totalVersion0` int(10) unsigned NOT NULL default '0',
  `cyrillic` tinyint(1) default '0',
  `merged` tinyint(1) default '0',
  PRIMARY KEY  (`entryID`,`subID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `fversions` (
  `entryID` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  `author` int(10) unsigned NOT NULL default '0',
  `versionDesc` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `size` float(7,2) default NULL,
  `comment` text collate utf8_unicode_ci,
  `indate` datetime default NULL,
  PRIMARY KEY  (`entryID`,`subID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `languages` (
  `langID` int(10) unsigned NOT NULL auto_increment,
  `lang_name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `sub_lang_id` int(11) NOT NULL default '0',
  `utf` tinyint(1) default '0',
  PRIMARY KEY  (`langID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `languages` VALUES (1,'English',0,0),(2,'English (UK)',0,0),(3,'English (US)',0,0),(4,'Español',0,0),(5,'Español (España)',0,0),(6,'Español (Latinoamérica)',0,0),(7,'Italian',0,0),(8,'French',0,0),(9,'Portuguese',0,0),(10,'Brazilian',0,0),(11,'German',0,0),(12,'Català',0,0),(13,'Euskera',0,0),(14,'Czech',0,0),(15,'Galego',0,0),(16,'Turkish',0,1),(17,'Nederlandse',0,0),(18,'Swedish',0,0),(19,'Russian',0,1),(20,'Hungarian',0,1),(21,'Polish',0,1),(22,'Slovenian',0,1),(23,'Hebrew',0,1),(24,'Chinese',0,0),(25,'Slovak',0,0);

CREATE TABLE `lasttranslated` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  `lang_id` int(10) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `links` (
  `linkID` int(11) NOT NULL auto_increment,
  `subID` int(11) NOT NULL default '0',
  `fversion` smallint(6) default NULL,
  `author` int(11) default NULL,
  `downloads` int(11) default NULL,
  `enabled` tinyint(1) default NULL,
  `versionDESC` varchar(20) default NULL,
  `versionSize` float(7,2) default NULL,
  PRIMARY KEY  (`linkID`),
  KEY `showsub` (`subID`,`fversion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `links_data` (
  `entryID` int(11) unsigned NOT NULL auto_increment,
  `linkID` int(11) unsigned NOT NULL default '0',
  `sequence` mediumint(9) default NULL,
  `start_time` time default NULL,
  `start_time_fraction` smallint(3) unsigned zerofill default NULL,
  `end_time` time default NULL,
  `end_time_fraction` smallint(3) unsigned zerofill default NULL,
  PRIMARY KEY  (`entryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `log` (
  `entryID` int(10) unsigned NOT NULL auto_increment,
  `action` smallint(6) default NULL,
  `text` varchar(300) default NULL,
  `date` datetime default NULL,
  `userID` int(10) unsigned default NULL,
  `moderator` tinyint(1) default '0',
  `subID` int(10) unsigned default NULL,
  PRIMARY KEY  (`entryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `log_actions` (
  `actionID` smallint(5) unsigned NOT NULL auto_increment,
  `actionDESC` varchar(100) default NULL,
  PRIMARY KEY  (`actionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `moderations` (
  `entry` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned default NULL,
  `fversion` smallint(5) unsigned default '0',
  `lang` smallint(5) unsigned default NULL,
  `active` tinyint(1) default '1',
  `counter` int(10) unsigned default '1',
  `moderator` int(10) unsigned default NULL,
  `comment` text,
  PRIMARY KEY  (`entry`),
  KEY `busqueda` (`subID`,`fversion`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `msgs` (
  `msgID` int(10) unsigned NOT NULL auto_increment,
  `from` int(10) unsigned NOT NULL default '0',
  `to` int(10) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `opened` tinyint(1) NOT NULL default '0',
  `fromDelete` tinyint(1) NOT NULL default '0',
  `toDelete` tinyint(1) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `noticed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`msgID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text character set latin1 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

CREATE TABLE `shows` (
  `showID` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`showID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `subs` (
  `entryID` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `sequence` int(10) unsigned NOT NULL default '0',
  `authorID` int(10) unsigned NOT NULL default '0',
  `version` smallint(5) unsigned NOT NULL default '0',
  `original` tinyint(1) NOT NULL default '1',
  `locked` tinyint(1) NOT NULL default '0',
  `in_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `start_time` time NOT NULL default '00:00:00',
  `start_time_fraction` smallint(3) unsigned zerofill NOT NULL default '000',
  `end_time` time NOT NULL default '00:00:00',
  `end_time_fraction` smallint(3) unsigned zerofill NOT NULL default '000',
  `text` text character set utf8 NOT NULL,
  `lang_id` int(10) unsigned NOT NULL default '0',
  `edited_seq` int(10) unsigned NOT NULL default '0',
  `last` tinyint(1) unsigned NOT NULL default '1',
  `estart_time` time NOT NULL default '00:00:00',
  `estart_time_fraction` smallint(3) unsigned zerofill NOT NULL default '000',
  `eend_time` time NOT NULL default '00:00:00',
  `eend_time_fraction` smallint(3) unsigned zerofill NOT NULL default '000',
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  `tested` smallint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  USING BTREE (`entryID`,`subID`,`edited_seq`,`sequence`,`lang_id`),
  KEY `list` USING BTREE (`subID`,`fversion`,`lang_id`),
  KEY `listuser` (`authorID`,`version`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `testing` (
  `entryID` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` int(10) unsigned NOT NULL default '0',
  `lang_id` int(10) unsigned NOT NULL default '0',
  `total` int(10) unsigned NOT NULL default '0',
  `user` int(10) unsigned NOT NULL default '1',
  `current` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`entryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `transcomments` (
  `commentID` int(10) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `lang_id` int(10) unsigned NOT NULL default '0',
  `author` int(10) unsigned NOT NULL default '0',
  `text` text character set latin1 NOT NULL,
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`commentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `translating` (
  `entryID` bigint(20) unsigned NOT NULL auto_increment,
  `subID` int(10) unsigned NOT NULL default '0',
  `fversion` tinyint(3) unsigned NOT NULL default '0',
  `lang_id` int(10) unsigned NOT NULL default '0',
  `tokened` tinyint(1) NOT NULL default '0',
  `userID` int(10) unsigned NOT NULL default '0',
  `tokentime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sequence` int(10) unsigned NOT NULL default '0',
  `seqcount` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`entryID`),
  KEY `main` (`subID`,`fversion`,`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `userID` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(50) character set latin1 NOT NULL default '',
  `password` varchar(32) character set latin1 NOT NULL default '',
  `mail` varchar(120) character set latin1 NOT NULL default '',
  `website` varchar(255) character set latin1 NOT NULL default '',
  `joined` datetime NOT NULL default '0000-00-00 00:00:00',
  `uploads` int(11) NOT NULL default '0',
  `range` tinyint(3) unsigned NOT NULL default '0',
  `ip` varchar(32) default NULL,
  `last` datetime default NULL,
  `navegate` text,
  `hide` tinyint(1) default '0',
  `applang` char(2) default NULL,
  PRIMARY KEY  (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into users (username,password,joined,range) values('admin', '7bcc68ab6784645e6532730bb7689697',NOW(),2);












