

#
# Table structure for table 'syncit_bookmarks'
#

DROP TABLE IF EXISTS syncit_bookmarks;
CREATE TABLE syncit_bookmarks (
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
# Table structure for table 'syncit_buttugly_redir'
#

DROP TABLE IF EXISTS syncit_buttugly_redir;
CREATE TABLE syncit_buttugly_redir (
  person_id int(11) default NULL,
  publish_id int(11) default NULL,
  book_id int(11) default NULL,
  access datetime default NULL
) TYPE=MyISAM;


#
# Table structure for table 'syncit_category'
#

DROP TABLE IF EXISTS syncit_category;
CREATE TABLE syncit_category (
  name varchar(50) default NULL,
  categoryid int(11) NOT NULL auto_increment,
  description varchar(50) default NULL,
  PRIMARY KEY  (categoryid)
) TYPE=MyISAM;


#
# Dumping data for table 'syncit_category'
#

INSERT INTO syncit_category VALUES("Computer", "1", "Computer");
INSERT INTO syncit_category VALUES("Travel", "2", "Travel");
INSERT INTO syncit_category VALUES("Finance", "3", "Finance");
INSERT INTO syncit_category VALUES("International", "4", "International");
INSERT INTO syncit_category VALUES("Develop", "5", "Develop");


#
# Table structure for table 'syncit_charsets'
#

DROP TABLE IF EXISTS syncit_charsets;
CREATE TABLE syncit_charsets (
  charsetid int(10) unsigned NOT NULL auto_increment,
  charset varchar(16) default NULL,
  PRIMARY KEY  (charsetid)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_images'
#

DROP TABLE IF EXISTS syncit_images;
CREATE TABLE syncit_images (
  imgid int(10) unsigned NOT NULL auto_increment,
  src varchar(255) default NULL,
  width int(11) default NULL,
  height int(11) default NULL,
  PRIMARY KEY  (imgid)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_invitations'
#

DROP TABLE IF EXISTS syncit_invitations;
CREATE TABLE syncit_invitations (
  invitationid int(10) unsigned NOT NULL auto_increment,
  personid int(11) default NULL,
  email varchar(50) default NULL,
  publishid int(11) default NULL,
  invited datetime default NULL,
  PRIMARY KEY  (invitationid)
) TYPE=MyISAM;


#
# Table structure for table 'syncit_link'
#

DROP TABLE IF EXISTS syncit_link;
CREATE TABLE syncit_link (
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
# Table structure for table 'syncit_person'
#

DROP TABLE IF EXISTS syncit_person;
CREATE TABLE syncit_person (
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
# Table structure for table 'syncit_publish'
#

DROP TABLE IF EXISTS syncit_publish;
CREATE TABLE syncit_publish (
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
# Table structure for table 'syncit_removed'
#

DROP TABLE IF EXISTS syncit_removed;
CREATE TABLE syncit_removed (
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
# Table structure for table 'syncit_subscriptions'
#

DROP TABLE IF EXISTS syncit_subscriptions;
CREATE TABLE subscriptions (
  subscriptionid int(10) unsigned NOT NULL auto_increment,
  person_id int(11) default NULL,
  publish_id int(11) default NULL,
  created datetime default NULL,
  vote smallint(6) default NULL,
  PRIMARY KEY  (subscriptionid)
) TYPE=MyISAM;

