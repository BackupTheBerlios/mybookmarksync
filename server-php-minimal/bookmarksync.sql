

#
# Table structure for table 'syncit_bookmarks'
#

DROP TABLE IF EXISTS syncit_bookmarks;
CREATE TABLE syncit_bookmarks (
  bookid int(10) unsigned NOT NULL auto_increment,
  surl varchar(255) default NULL,
  url text,
  PRIMARY KEY  (bookid),
  UNIQUE KEY urlidx (surl)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_link'
#

DROP TABLE IF EXISTS syncit_link;
CREATE TABLE syncit_link (
  link_id int(10) unsigned NOT NULL auto_increment,  # not used
  person_id int(11) NOT NULL default '0',
  book_id int(11) default NULL,
  access varchar(24) default NULL,
  path varchar(255) NOT NULL default '\\',
  expiration datetime default NULL,
  PRIMARY KEY  (person_id,path),
  UNIQUE KEY linkdx (link_id),
  FULLTEXT KEY idxpath (path)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_person'
#

DROP TABLE IF EXISTS syncit_person;
CREATE TABLE syncit_person (
  personid int(10) unsigned NOT NULL auto_increment,
  name varchar(50) default NULL,
  email varchar(50) default NULL,
  pass varchar(50) default NULL,
  registered datetime default NULL,
  lastchanged datetime default NULL,
  token int(11) default '0',
  PRIMARY KEY  (personid),
  UNIQUE KEY idxemail (email)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_config'
#

DROP TABLE IF EXISTS syncit_config;
CREATE TABLE syncit_config (
  param varchar(50) NOT NULL,
  value varchar(50) default NULL,
  PRIMARY KEY (param)
) TYPE=MyISAM;
