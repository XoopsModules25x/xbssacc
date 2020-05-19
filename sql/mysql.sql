## phpMyAdmin SQL Dump
## version 2.6.0
## http://www.phpmyadmin.net
##
## Host: localhost
## Generation Time: Nov 17, 2004 at 04:10 AM
## Server version: 4.0.21
## PHP Version: 4.3.9
##
## Table creation for Simple Accounts System
## (c) 2004 A Kitson
##
##
## Database: `sacc`
##
##
## Table structure for table `sacc_ac`
##

CREATE TABLE sacc_ac (
  id bigint(20) NOT NULL auto_increment,
  ac_prnt_id bigint(20) NOT NULL default '0',
  org_id bigint(20) NOT NULL default '0',
  ac_curr char(3) NOT NULL default '',
  ac_tp varchar(6) NOT NULL default '',
  ac_nm varchar(20) NOT NULL default '',
  ac_prps text,
  ac_note varchar(255) default NULL,
  ac_dr bigint(20) unsigned default NULL,
  ac_cr bigint(20) unsigned default NULL,
  ac_level tinyint(1) NOT NULL default '0',
  has_kids tinyint(1) NOT NULL default '0',
  disp_order tinyint(4) NOT NULL default '0',
  row_flag enum('Active','Defunct','Suspended') NOT NULL default 'Active',
  row_uid int(10) unsigned NOT NULL default '0',
  row_dt timestamp,
  PRIMARY KEY  (id),
  KEY Account_FKIndex1 (ac_tp),
  KEY Account_FKIndex2 (ac_curr),
  KEY Account_FKIndex3 (org_id),
  KEY Account_FKIndex4 (ac_prnt_id)
) ENGINE=MyISAM COMMENT='An Account';

##
## Table structure for table `sacc_ctrl`
##

CREATE TABLE sacc_ctrl (
 org_id int(11) NOT NULL default '0',
 ctrl_cd char(4) NOT NULL default '0',
 ac_id int(11) NOT NULL default '0',
 row_flag enum('Active','Defunct','Suspended') NOT NULL default 'Active',
 row_uid int(10) NOT NULL default '0',
 row_dt timestamp,
 PRIMARY KEY  (org_id,ctrl_cd)
) ENGINE=MyISAM COMMENT='Control Accounts';

##
## Table structure for table `sacc_entry`
##

CREATE TABLE sacc_entry (
  id bigint(20) NOT NULL auto_increment,
  jrn_id bigint(20) NOT NULL default '0',
  ac_id bigint(20) NOT NULL default '0',
  txn_ref varchar(20) default NULL,
  txn_dr bigint(20) unsigned default NULL,
  txn_cr bigint(20) unsigned default NULL,
  row_flag enum('Active','Defunct','Suspended') NOT NULL default 'Active',
  row_uid int(10) NOT NULL default '0',
  row_dt timestamp,
  PRIMARY KEY  (id),
  KEY AccountEntry_FKIndex1 (jrn_id),
  KEY AccountEntry_FKIndex2 (ac_id)
) ENGINE=MyISAM COMMENT='Entry into an Account/Journal';


##
## Table structure for table `sacc_journ`
##

CREATE TABLE sacc_journ (
  id bigint(20) NOT NULL auto_increment,
  org_id smallint(6) NOT NULL default '0',
  jrn_dt datetime NOT NULL default '2020-05-16 00:00:00',
  jrn_prps varchar(30) default NULL,
  row_flag enum('Active','Defunct','Suspended') NOT NULL default 'Active',
  row_uid int(10) unsigned NOT NULL default '0',
  row_dt timestamp,
  PRIMARY KEY  (id),
  KEY org_id (org_id)
) ENGINE=MyISAM COMMENT='A journal header';

##
## Table structure for table `sacc_org`
##

CREATE TABLE sacc_org (
  id bigint(20) NOT NULL auto_increment,
  base_crcy char(3) NOT NULL default '',
  org_name varchar(30) default NULL,
  row_flag enum('Active','Defunct','Suspended') NOT NULL default 'Active',
  row_uid int(10) unsigned NOT NULL default '0',
  row_dt timestamp,
  PRIMARY KEY  (id),
  KEY Org_FKIndex1 (base_crcy)
) ENGINE=MyISAM COMMENT='An organisation for whom accounts are kept';



## Put code data into CDM system for SACC
INSERT INTO cdm_meta (cd_set, cd_type, cd_len, val_type, val_len, cd_desc) VALUES ('SACCACTP', 'VARCHAR', 6, 'VARCHAR', 20, 'SAC Account Types. Codes must be as defined in include/defines.php.  Do not extend the code set unless you know what you are doing!');
INSERT INTO cdm_meta (cd_set, cd_type, cd_len, val_type, val_len, cd_desc) VALUES ('SACCCNTL', 'CHAR', 4, 'VARCHAR', 20, 'Actual name for a control account.  Code must be same as defined in include/define.php as must be known to application.  DO NOT extend this codeset unless you are sure you know what you are doing.');
## Account types
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'INCOME', '', 'Income Account', 'An account showing sources of income.  Income is shown as CR, Refund as DR',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'EXPENS', '', 'Expense Account', 'An account showing destination of expenses.  Expense is shown as DR, refund of expense as CR.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'ASSET', '', 'Asset Account', 'An account showing assets. Value coming in is DR, going out is CR.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'LIABIL', '', 'Liability account', 'An account recording liabilities (money owing to third parties.) Liability recorded as CR.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'EQUITY', '', 'Equity Account', 'An account recording the capital or equity of an organisation.  Positive value is shown as CR, negative as DR.  Essentially a form of Liability as it is owed to the shareholders or owners.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'BANK', 'ASSET', 'Bank Account', 'An account at a bank.  Derived from 3rd Party Account, it is a special form of Asset Account',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, row_flag, cd_param) VALUES ('SACCACTP', 'EN', '3RDPTY', '', '3rd Party Account', '3rd party Accounts are distinguished by being associated with a person or company not connected with the account''s organisation.','Defunct',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'SUPPLY', 'LIABIL', 'Supplier Account', 'An account recording details of purchases from Suppliers.  Derived from 3rd Party Account, it is a kind of Liability Account.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCACTP', 'EN', 'CUSTOM', 'ASSET', 'Customer Account', 'An account recording sales to a customer.  Derived from 3rd Party Account, it is a kind of Asset Account',null);
## Control account types
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'BANK', '', 'Current Account', 'The default current bank account',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'OPEN', '', 'Opening Balances', 'Usually a sub account of the Equity ledger.  Used to  balance set-up amounts for accounts.',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'ASST', '', 'Master Asset Account', 'Used for Balance sheet overview',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'LIAB', '', 'Master Liability Account', 'Used for Balance sheet overview',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'EQUI', '', 'Master Equity Account', 'Used for Balance sheet overview',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'INCO', '', 'Master Income Account', 'Used for P/L Overview',null);
INSERT INTO cdm_code (cd_set, cd_lang, cd, cd_prnt, cd_value, cd_desc, cd_param) VALUES ('SACCCNTL', 'EN', 'EXPE', '', 'Master Expense Account', 'Used for P/L Overview',null);


