<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author:    Ashley Kitson                                                  //
// Copyright: (c) 2004, Ashley Kitson
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    SACC Simple Accounts                                           //
// ------------------------------------------------------------------------- //
/**
 * SACC Journal object handler
 *
 * @package SACC
 * @subpackage SACCJournal
 * @author Ashley Kitson http://xoobs.net
 * @copyright (c) 2004 Ashley Kitson, Great Britain
*/

if (!defined('XOOPS_ROOT_PATH')) { 
  exit('Call to include SACCAccount.php failed as XOOPS_ROOT_PATH not defined');
}
/**
* SACC base objects
*/
require_once SACC_PATH."/class/class.sacc.base.php";


/**
 * Object handler for SACCJournal
 *
 * @package SACC
 * @subpackage SACCJournal
 */
class Xbs_SaccSACCJournalHandler extends CDMBaseHandler {

  /**
   * Constructor
   * 
   * @param xoopsDatabase database handle
   */
	function Xbs_SaccSACCJournalHandler(&$db) {
    $this->CDMBaseHandler($db); //call ancestor constructor
    $this->classname = 'saccjournal';
    $this->ins_tagname='sacc_ins_journal';
  }

  /**
  * create a journal object
  *
  * @internal 
  */
  function &_create() {
    $obj = new SACCJournal();
    return $obj;
  }//end function _create

  /**
   * Create a journal object - extend ancestor create function
   *
   * @param date $jrn_dt date of journal
   * @param string $jrn_prps purpose of journal
   * @param int $org_id id of organisation
   * @return SACCJournal object
   */
  function create($jrn_dt,$jrn_prps,$org_id) {
    $obj = parent::create();
    $jrn_dt = (empty($jrn_dt) ? date('d/m/Y') : $jrn_dt);
    $obj->setVar('jrn_dt',$jrn_dt);
    $obj->setVar('jrn_prps',$jrn_prps);
    $obj->setVar('org_id',$org_id);
    return $obj;
  }

  /**
   * function loadEntries.  Load the account entries for the journal
   * returns journal with entries else FALSE on error
   */

  function loadEntries($journ) {
    $entries = array();
    $sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_ENTRY),$id);
    $sql .= (empty($row_flag) ? '' : sprintf(" and row_flag = %s",$this->db->quotestring($row_flag)));
    if ($result = $this->db->query($sql)) {
      while ($row = $this->db->fetchArray($result)) {
	$entry = new  SACCAcEntry();
	$entry->assignVars($row);
	$entries[] = $entry;
      } //end while
      $journ->setVar("acc_entry",$entries);
      return $journ;
    } else {
      $this->setError($this->db->errno(),$this->db->error());
    }//end if
    return false;
 }//end function loadEntries

 /**
  * function getall - overide ancestor because the call to create() is different
  *
  * @param int $id journal id
  * @param string $row_flag default = null. row status flag
  */
  function &getall($id,$row_flag=null) {
    $test = (is_int($id) ? ($id > 0 ? TRUE : FALSE) : !empty($id) ? TRUE : FALSE); 
    if ($test) {
      $journ =& $this->create(null,null,null);
      if ($journ) {
	$sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_JOURN),$id);
	$sql .= (empty($row_flag) ? '' : sprintf(" and row_flag = %s",$this->db->quotestring($row_flag)));
	if ($result = $this->db->query($sql)) {
	  if ($this->db->getRowsNum($result)==1) {
	    $journ->assignVars($this->db->fetchArray($result));
	    return $journ;
	  } else {
	    $this->setError(-1,sprintf(_MD_CDM_ERR_1,strval($id)));
	  }//end if
	} else {
	  $this->setError($this->db->errno(),$this->db->error());
	}//end if
      }//end if - error value set in call to create()
    } else {
      $this->setError(-1,sprintf(_MD_CDM_ERR_1,strval($id)));
    }//end if
    return false; //default return
  }//end function &getall


  /**
  * create sql string to insert object data
  *
  * @internal 
  */
  function _ins_insert($cleanVars) {
    foreach ($cleanVars as $k => $v) {
      ${$k} = $v;
    }
    $sql = sprintf("INSERT INTO %s (id,org_id,jrn_dt,jrn_prps,row_flag,row_uid,row_dt) VALUES (%u,%u,%s,%s,%s,%u,%s)",$this->db->prefix(SACC_TBL_JOURN),$id,$org_id,$this->db->quoteString($jrn_dt),$this->db->quoteString($jrn_prps),$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt)); 
    return $sql;
  }

  /**
  * create sql string to update object data
  *
  * @internal 
  */
  function _ins_update($cleanVars) {
    foreach ($cleanVars as $k => $v) {
      ${$k} = $v;
    }
    $sql = sprintf("UPDATE %s SET jrn_dt=%s,org_id=%u,jrn_prps=%s,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u",$this->db->prefix(SACC_TBL_JOURN),$this->db->quoteString($jrn_dt),$org_id,$this->db->quoteString($jrn_prps),$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt),$id);
    return $sql;
  }

  /**
  * insert object data to database - extend ancestor for post processing
  *
  * @param SACCJournal handle to journal object to insert
  */
  function insert(&$journal) {
   if (parent::insert($journal)) {
      // Journal header data saved, so now save any attached entries
      $entries = $journal->getVar('acc_entry');
      $id = $journal->getVar('id');
      $eHandler =& xoops_getmodulehandler("SACCEntry",SACC_DIR);
      foreach ($entries as $entry) {
	$entry->setVar('jrn_id',$id);
	$entry->setNew();
	if (!$eHandler->insert($entry)) {
	  return false;
	}
      }
      $journal->setVar('acc_entry',$entries);
      return true;
    } else {
      return false;
    }
  }//end function insert


} //end class SACCJournalHandler
?>