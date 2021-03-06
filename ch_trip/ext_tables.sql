#
# Table structure for table 'tt_content'
#
ALTER TABLE tt_content ADD `tx_chtrip_treeField` INT( 11 ) DEFAULT '0' NOT NULL ;
ALTER TABLE tt_content ADD `tx_chtrip_treeAltField` INT( 11 ) DEFAULT '0' NOT NULL ;

#
# Table structure for table 'tx_chtrip_hotel'
#
CREATE TABLE tx_chtrip_hotel (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	location tinytext NOT NULL,
	id_code tinytext NOT NULL,
	postcode tinytext NOT NULL,
	weathercode tinytext NOT NULL,
	room text NOT NULL,
	teaser text NOT NULL,
	description text NOT NULL,
	pictures blob NOT NULL,
	captions text NOT NULL,
	wheremap blob NOT NULL,
	video blob NOT NULL,
	hoteltype int(11) DEFAULT '0' NOT NULL,
	location_f04338f846 int(11) DEFAULT '0' NOT NULL,
	hotelproperties int(11) DEFAULT '0' NOT NULL,
    metakeywords text NOT NULL,
	tx_perfectlightbox_activate tinyint(3) DEFAULT '0' NOT NULL,
	tx_perfectlightbox_imageset tinyint(3) DEFAULT '0' NOT NULL,
	tx_perfectlightbox_presentation tinyint(3) DEFAULT '0' NOT NULL,
	tx_perfectlightbox_slideshow tinyint(3) DEFAULT '0' NOT NULL,
	season int(11) DEFAULT '0' NOT NULL,
	treeField int(11) DEFAULT '0' NOT NULL,
	category text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
	KEY crdate (crdate)
);

#
# Table structure for table 'tx_chtrip_hotel_properties_mm'
# 
#
CREATE TABLE tx_chtrip_hotel_properties_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_hotel_type_mm'
# 
#
CREATE TABLE tx_chtrip_hotel_type_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_hotel_season_mm'
# 
#
CREATE TABLE tx_chtrip_location_season_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_hierarchy'
#
CREATE TABLE tx_chtrip_hierarchy (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	parent_uid int(11) DEFAULT '0' NOT NULL,
	icon blob NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
	KEY parent_uid (parent_uid)
);

#
# Table structure for table 'tx_chtrip_region'
#
CREATE TABLE tx_chtrip_region (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	parent_uid int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
	KEY parent_uid (parent_uid)
);

#
# Table structure for table 'tx_chtrip_season'
#
CREATE TABLE tx_chtrip_season (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	parent_uid int(11) DEFAULT '0' NOT NULL,
	from_a1 int(11) DEFAULT '0' NOT NULL,
	till_a1 int(11) DEFAULT '0' NOT NULL,
	icon blob NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
	KEY parent_uid (parent_uid)
);

#
# Table structure for table 'tx_chtrip_properties'
#
CREATE TABLE tx_chtrip_properties (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	parent_uid int(11) DEFAULT '0' NOT NULL,
	icon blob NOT NULL,
	hierarchy int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
	KEY parent_uid (parent_uid)
);

#
# Table structure for table 'tx_chtrip_hotel_season_mm'
# 
#
CREATE TABLE tx_chtrip_hotel_season_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_chtrip_properties_mm'
# 
#
CREATE TABLE tx_chtrip_properties_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_price'
#
CREATE TABLE tx_chtrip_price (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	a_baseprice tinytext NOT NULL,
	a_halfboard tinytext NOT NULL,
	season int(11) DEFAULT '0' NOT NULL,
	specialoffer tinyint(3) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted)
);

#
# Table structure for table 'tx_chtrip_price_season_mm'
#
CREATE TABLE tx_chtrip_price_season_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_room'
#
CREATE TABLE tx_chtrip_room (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	description text NOT NULL,
	pictures blob NOT NULL,
	captions text NOT NULL,
	special int(11) DEFAULT '0' NOT NULL,
	miscellaneous text NOT NULL,
	arrivalanddeparture text NOT NULL,
	roomproperties int(11) DEFAULT '0' NOT NULL,
	treeField int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted),
);

#
# Table structure for table 'tx_chtrip_room_special'
#
CREATE TABLE tx_chtrip_room_special (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	description tinytext NOT NULL,

	PRIMARY KEY (uid),
	KEY hidden (hidden),
	KEY deleted (deleted)
);


#
# Table structure for table 'tx_chtrip_room_mm'
# 
#
CREATE TABLE tx_chtrip_room_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_room_special_mm'
# 
#
CREATE TABLE tx_chtrip_room_special_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_price_mm'
# 
#
CREATE TABLE tx_chtrip_price_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_category_mm'
# 
#
CREATE TABLE tx_chtrip_category_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_chtrip_category'
#
CREATE TABLE tx_chtrip_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	bookingperiod int(11) DEFAULT '0' NOT NULL,
	allseason tinyint(3) DEFAULT '0' NOT NULL,
	price int(11) DEFAULT '0' NOT NULL,
	property int(11) DEFAULT '0' NOT NULL,
	requestperunit tinyint(3) DEFAULT '0' NOT NULL,
	requestperperson tinyint(3) DEFAULT '0' NOT NULL,
	halfboard tinyint(3) DEFAULT '0' NOT NULL,
	seasonprice text NOT NULL,
	treeField int(11) DEFAULT '0' NOT NULL,
	specialoffer int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
    	KEY hidden (hidden),
    	KEY deleted (deleted),
);

#
# Table structure for table 'tx_chtrip_request'
#
CREATE TABLE tx_chtrip_request (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
    datefrom int(11) DEFAULT '0' NOT NULL,
    datetill int(11) DEFAULT '0' NOT NULL,
    salutation int(11) DEFAULT '0' NOT NULL,
	region tinytext NOT NULL,
	objtype tinytext NOT NULL,    
    objname tinytext NOT NULL,
    objaccommodation tinytext NOT NULL,
	category tinytext NOT NULL,
	idcode tinytext NOT NULL,
    name tinytext NOT NULL,
    forename tinytext NOT NULL,
	street tinytext NOT NULL,
	where1 tinytext NOT NULL,
	phone tinytext NOT NULL,
	fax tinytext NOT NULL,
	email tinytext NOT NULL,
	name2 tinytext NOT NULL,
	message tinytext NOT NULL,
	billingaddress int(11) DEFAULT '0' NOT NULL,
	nl int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY hidden (hidden),
	KEY deleted (deleted)
);

#
# Table structure for table 'tx_chtrip_realurl'
#
CREATE TABLE tx_chtrip_realurl (
    uid int(11) NOT NULL auto_increment,
    title tinytext NOT NULL,
    getvar tinytext NOT NULL,

    PRIMARY KEY (uid)
);