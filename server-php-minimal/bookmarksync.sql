# MySQL-Front Dump 2.5
#
# Host: localhost   Database: minimal
# --------------------------------------------------------
# Server version 4.0.14-nt


#
# Table structure for table 'bookmarks'
#

DROP TABLE IF EXISTS bookmarks;
CREATE TABLE bookmarks (
  bookid int(10) unsigned NOT NULL auto_increment,
  surl varchar(255) default NULL,
  lastchecked datetime default NULL,
  title varchar(255) default NULL,
  status smallint(6) default NULL,
  url text,
  PRIMARY KEY  (bookid),
  UNIQUE KEY urlidx (surl)
) TYPE=MyISAM;



#
# Dumping data for table 'bookmarks'
#



#
# Table structure for table 'buttugly_redir'
#

DROP TABLE IF EXISTS buttugly_redir;
CREATE TABLE buttugly_redir (
  person_id int(11) default NULL,
  publish_id int(11) default NULL,
  book_id int(11) default NULL,
  access datetime default NULL
) TYPE=MyISAM;



#
# Dumping data for table 'buttugly_redir'
#



#
# Table structure for table 'category'
#

DROP TABLE IF EXISTS category;
CREATE TABLE category (
  name varchar(50) default NULL,
  categoryid int(11) NOT NULL auto_increment,
  description varchar(50) default NULL,
  PRIMARY KEY  (categoryid)
) TYPE=MyISAM;



#
# Dumping data for table 'category'
#

INSERT INTO category VALUES("Computer", "1", "Computer");
INSERT INTO category VALUES("Travel", "2", "Travel");
INSERT INTO category VALUES("Finance", "3", "Finance");
INSERT INTO category VALUES("International", "4", "International");
INSERT INTO category VALUES("Develop", "5", "Develop");


#
# Table structure for table 'charsets'
#

DROP TABLE IF EXISTS charsets;
CREATE TABLE charsets (
  charsetid int(10) unsigned NOT NULL auto_increment,
  charset varchar(16) default NULL,
  PRIMARY KEY  (charsetid)
) TYPE=MyISAM;



#
# Dumping data for table 'charsets'
#



#
# Table structure for table 'images'
#

DROP TABLE IF EXISTS images;
CREATE TABLE images (
  imgid int(10) unsigned NOT NULL auto_increment,
  src varchar(255) default NULL,
  width int(11) default NULL,
  height int(11) default NULL,
  PRIMARY KEY  (imgid)
) TYPE=MyISAM;



#
# Dumping data for table 'images'
#



#
# Table structure for table 'invitations'
#

DROP TABLE IF EXISTS invitations;
CREATE TABLE invitations (
  invitationid int(10) unsigned NOT NULL auto_increment,
  personid int(11) default NULL,
  email varchar(50) default NULL,
  publishid int(11) default NULL,
  invited datetime default NULL,
  PRIMARY KEY  (invitationid)
) TYPE=MyISAM;



#
# Dumping data for table 'invitations'
#



#
# Table structure for table 'link'
#

DROP TABLE IF EXISTS link;
CREATE TABLE link (
  link_id int(10) unsigned NOT NULL auto_increment,
  person_id int(11) NOT NULL default '0',
  book_id int(11) default NULL,
  access varchar(24) default NULL,
  path varchar(255) NOT NULL default '\\',
  publish_id int(11) default NULL,
  expiration datetime default NULL,
  openimg_id int(11) default NULL,
  closeimg_id int(11) default NULL,
  PRIMARY KEY  (person_id,path),
  UNIQUE KEY linkdx (link_id),
  FULLTEXT KEY idxpath (path)
) TYPE=MyISAM;



#
# Dumping data for table 'link'
#



#
# Table structure for table 'person'
#

DROP TABLE IF EXISTS person;
CREATE TABLE person (
  personid int(10) unsigned NOT NULL auto_increment,
  name varchar(50) default NULL,
  address1 varchar(50) default NULL,
  address2 varchar(50) default NULL,
  city varchar(50) default NULL,
  state varchar(20) default NULL,
  zip varchar(10) default NULL,
  phone varchar(50) default NULL,
  fax varchar(50) default NULL,
  email varchar(50) default NULL,
  security int(11) default NULL,
  description text,
  wherefrom text,
  pass varchar(50) default NULL,
  registered datetime default NULL,
  lastchanged datetime default NULL,
  lastread datetime default NULL,
  country varchar(30) default NULL,
  age int(11) default NULL,
  gender char(1) default NULL,
  token int(11) default '0',
  upsize_ts varchar(255) default NULL,
  optin smallint(6) default NULL,
  lastverified datetime default NULL,
  refercnt int(11) default NULL,
  PRIMARY KEY  (personid),
  UNIQUE KEY idxemail (email)
) TYPE=MyISAM;



#
# Dumping data for table 'person'
#



#
# Table structure for table 'publish'
#

DROP TABLE IF EXISTS publish;
CREATE TABLE publish (
  publishid int(10) unsigned NOT NULL auto_increment,
  user_id int(11) default NULL,
  path varchar(255) default NULL,
  category_id int(11) default NULL,
  created datetime default NULL,
  title varchar(50) default NULL,
  description text,
  token int(11) default NULL,
  anonymous enum('True','False') default NULL,
  PRIMARY KEY  (publishid)
) TYPE=MyISAM;



#
# Dumping data for table 'publish'
#



#
# Table structure for table 'removed'
#

DROP TABLE IF EXISTS removed;
CREATE TABLE removed (
  name varchar(50) default NULL,
  email varchar(50) default NULL,
  bookmarks int(11) default NULL,
  reason text,
  registered datetime default NULL,
  removed datetime default NULL,
  lastchanged datetime default NULL,
  token int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;



#
# Dumping data for table 'removed'
#



#
# Table structure for table 'subscriptions'
#

DROP TABLE IF EXISTS subscriptions;
CREATE TABLE subscriptions (
  subscriptionid int(10) unsigned NOT NULL auto_increment,
  person_id int(11) default NULL,
  publish_id int(11) default NULL,
  created datetime default NULL,
  vote smallint(6) default NULL,
  PRIMARY KEY  (subscriptionid)
) TYPE=MyISAM;



#
# Dumping data for table 'subscriptions'
#

