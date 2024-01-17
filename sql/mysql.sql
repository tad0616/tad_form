CREATE TABLE `tad_form_main` (
  `ofsn` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `start_date` datetime default NULL,
  `end_date` datetime default NULL,
  `content` text,
  `uid` mediumint(8) unsigned NOT NULL default '0',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tad_form_fill` (
  `ssn` int(10) unsigned NOT NULL auto_increment,
  `ofsn` smallint(5) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `man_name` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `fill_time` datetime NOT NULL,
  `result_col` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ssn`),
  UNIQUE KEY `ofsn_code` (`ofsn`, `code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tad_form_value` (
  `ssn` int(10) unsigned NOT NULL default '0',
  `csn` mediumint(8) unsigned NOT NULL default '0',
  `val` text,
  PRIMARY KEY  (`ssn`, `csn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tad_form_data_center` (
  `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '模組編號',
  `col_name` varchar(100) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `col_sn` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '欄位編號',
  `data_name` varchar(100) NOT NULL DEFAULT '' COMMENT '資料名稱',
  `data_value` text NOT NULL COMMENT '儲存值',
  `data_sort` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `col_id` varchar(100) NOT NULL COMMENT '辨識字串',
  `sort` mediumint(9) unsigned DEFAULT NULL COMMENT '排序',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  PRIMARY KEY (`mid`, `col_name`, `col_sn`, `data_name`, `data_sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tad_form_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL DEFAULT '' COMMENT '欄位名稱',
  `col_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `kind` enum('img','file') NOT NULL DEFAULT 'img' COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '下載人次',
  `original_filename` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案名稱',
  `hash_filename` varchar(255) NOT NULL DEFAULT '' COMMENT '加密檔案名稱',
  `sub_dir` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案子路徑',
  `upload_date` datetime NOT NULL COMMENT '上傳時間',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上傳者',
  `tag` varchar(255) NOT NULL DEFAULT '' COMMENT '註記',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;