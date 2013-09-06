CREATE TABLE `tad_form_main` (
  `ofsn` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `start_date` datetime default NULL,
  `end_date` datetime default NULL,
  `content` text,
  `uid` smallint(5) unsigned NOT NULL default '0',
  `post_date` datetime default NULL,
  `enable` enum('1','0') NOT NULL default '1',
  `sign_group` varchar(255) NOT NULL default '',
  `kind` varchar(255) NOT NULL default '',
  `adm_email` varchar(255) NOT NULL default '',
  `captcha` enum('1','0') NOT NULL default '1',
  `show_result` enum('0','1') NOT NULL default '0',
  `view_result_group` varchar(255) NOT NULL default '',
  `multi_sign` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ofsn`)
) ENGINE=MyISAM;

CREATE TABLE `tad_form_col` (
  `csn` mediumint(8) unsigned NOT NULL auto_increment,
  `ofsn` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `descript` text,
  `kind` varchar(20) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `val` varchar(255) default NULL,
  `chk` enum('1','0') default '1',
  `func` set('sum','avg','count') default NULL,
  `sort` tinyint(4) default NULL,
  `public` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`csn`),
  KEY `ofsn` (`ofsn`)
) ENGINE=MyISAM;

CREATE TABLE `tad_form_fill` (
  `ssn` int(10) unsigned NOT NULL auto_increment,
  `ofsn` smallint(5) unsigned NOT NULL default '0',
  `uid` smallint(5) unsigned NOT NULL default '0',
  `man_name` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `fill_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `result_col` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ssn`)
) ENGINE=MyISAM;


CREATE TABLE `tad_form_value` (
  `ssn` int(10) unsigned NOT NULL default '0',
  `csn` mediumint(8) unsigned NOT NULL default '0',
  `val` text,
  PRIMARY KEY  (`ssn`,`csn`)
) ENGINE=MyISAM;
