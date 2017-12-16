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
// Module:    Code Data Management (CDM)                                     //
// ------------------------------------------------------------------------- //
/**
 * Organisation object handler
 *
 * @package SACC
 * @subpackage SACCOrg
 * @author Ashley Kitson <akitson@bbcb.co.uk>
 * @copyright (c) 2004 Ashley Kitson, Great Britain
*/

if (!defined('XOOPS_ROOT_PATH')) { 
  exit('Call to include SACCOrg.php failed as XOOPS_ROOT_PATH not defined');
}

/**
* SACC base object definitions
*/
require_once SACC_PATH."/class/class.sacc.base.php";

/**
* SACC Organisation object handler 
*
* XoopsModule handler
*
* @author Ashley Kitson http://xoobs.net
* @copyright 2005 Ashley Kitson, UK
* @package SACC
* @subpackage SACCOrg
* @version 1 
*/
class Xbs_SaccSACCOrgHandler extends CDMBaseHandler {

	/**
	* Function: constructor 
	*
	* Organisation handler constructor
	*
	* @version 1
	* @param XoopsDatabase $db handle to current database
	*/
  function Xbs_SaccSACCOrgHandler(&$db) {
    $this->CDMBaseHandler($db); //call ancestor constructor
    $this->classname = 'saccorg';
    $this->ins_tagname='sacc_ins_org';
  }

/**
* Function: _create Create Organisation object 
*
* @version 1
* @access private
*/
  function &_create() {
    $obj = new SACCOrg();
    return $obj;
  }//end function _create

/**
* Function: _get create sql string to get data for object
*
* @version 1
* @access private
*/
  function &_get($key,$row_flag=null,$lang=null) {
    $sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_ORG),$key);
    $sql .= (empty($row_flag)?"":" and row_flag = ".$this->db->quoteString($row_flag));
    return $sql;
  }//end function _get

/**
* Function: _reload reload an object from database
*
* @version 1
* @access private
*/
  function &_reload($key=null) {
    $sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_ORG),$key);
    return $sql;
  }

  /**
  * Load up the accounts belonging to this organisation
  *
  * @version 1
  * @param SACCOrg organisation object handle
  */
  function loadAccounts(&$obj) {
    $id = intval($obj->getVar('id'));
    $sql=sprintf("select id from %s where org_id = %u order by disp_order",$this->db->prefix(SACC_TBL_ACC),$id);

    if ($result=$this->db->query($sql)) {
      $ret = array();
      $accHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
      while ($arr=$this->db->fetchArray($result)) {
	$ac = $accHandler->getall($arr['id']);
	$ac->setBalance();
	$ret[] = $ac;
      }
      $obj->setVar('accounts',$ret);
    }
  }

  /**
  * Load up the journal entries belonging to this organisation
  *
  * @version 1
  * @param SACCOrg organisation object handle
  */
  function loadJournal(&$obj) {
    $id = intval($obj->getVar('id'));
    $sql=sprintf("select id from %s where org_id = %u",$this->db->prefix(SACC_TBL_JOURN),$id);

    if ($result=$this->db->query($sql)) {
      $ret = array();
      $jHandler =& xoops_getmodulehandler("SACCJournal",SACC_DIR);
      while ($arr=$this->db->fetchArray($result)) {
        $ret[] = $jHandler->get($arr['id']);
      }
      $obj->setVar('journal',$ret);
    }
  }
  /** 
   * function createControl.  Create a control account
   *
   * @version 1
   * @param int organisation id
   * @param int account id
   * @param string control account name (tag)
   */
  function createControl($org_id, $ac_id, $ctrlName) {
    //set the control account details 
    $ctrlHandler =& xoops_getmodulehandler("SACCControl",SACC_DIR); 
    $ctrlAc = $ctrlHandler->create();
    $ctrlAc->setVar("org_id",$org_id);
    $ctrlAc->setVar("ctrl_cd",$ctrlName);
    $ctrlAc->setVar("ac_id",$ac_id);
    $ctrlHandler->insert($ctrlAc);
  }

  /**
   * function createAccounts - Create new base accounts for the organisation.
   *
   * @version 1
   * @param SACCOrg organisation object handle
   */

   function createAccounts(&$org) {
    $org_id = $org->getVar("id");
    $base_crcy = $org->getVar("base_crcy");
    // Set up an array of new Base account information
    $nac = array("asset"=>array("ac_tp"=>SACC_ACTP_ASSET,"ac_nm"=>_MD_SACC_NAC_ASSET),
		 "liability"=>array("ac_tp"=>SACC_ACTP_LIABILITY,"ac_nm"=>_MD_SACC_NAC_LIABILITY),
		 "income"=>array("ac_tp"=>SACC_ACTP_INCOME,"ac_nm"=>_MD_SACC_NAC_INCOME),
		 "expense"=>array("ac_tp"=>SACC_ACTP_EXPENSE,"ac_nm"=>_MD_SACC_NAC_EXPENSE),
		 "equity"=>array("ac_tp"=>SACC_ACTP_EQUITY,"ac_nm"=>_MD_SACC_NAC_EQUITY),
		 "bank"=>array("ac_tp"=>SACC_ACTP_BANK,"ac_nm"=>_MD_SACC_NAC_BANK),
		 "open"=>array("ac_tp"=>SACC_ACTP_EQUITY,"ac_nm"=>_MD_SACC_NAC_OPEN));
    $accHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
    $ctrlHandler =& xoops_getmodulehandler("SACCControl",SACC_DIR); 
    $account = array();
    //loop through the array and create the new accounts
    foreach ($nac as $ac) {
      $accs = $accHandler->create();
      $accs->setVar("ac_prnt_id",_MD_SACC_NOPARENT);
      $accs->setVar("org_id",$org_id);
      $accs->setVar("ac_curr",$base_crcy);
      $accs->setVar("id",0); 
      $accs->setVar("ac_nm",$ac["ac_nm"]);
      $accs->setVar("ac_tp",$ac["ac_tp"]);
      $accs->setVar("ac_dr",0);
      $accs->setVar("ac_cr",0);
      $accHandler->insert($accs); //create the account
      $account[] = $accs;  //Save details
    } //for loop end

    //get the account id's for each of the accounts
    $bank_key = array_search("bank",array_keys($nac));
    $asset_key = array_search("asset",array_keys($nac));
    $open_key = array_search("open",array_keys($nac));
    $equity_key = array_search("equity",array_keys($nac));
	$liability_key = array_search("liability",array_keys($nac));
	$income_key = array_search("income",array_keys($nac));
	$expense_key = array_search("expense",array_keys($nac));
	
    //reset the parent account for the bank account
    $account[$bank_key]->setVar("ac_prnt_id",$account[$asset_key]->getVar("id"));
    $accHandler->insert($account[$bank_key]); //update the account

    //reset the parent account for the Opening Balances account
    $account[$open_key]->setVar("ac_prnt_id",$account[$equity_key]->getVar("id"));
    $accHandler->insert($account[$open_key]); //update the account

    //add the control account information
    $this->createControl($org_id, $account[$bank_key]->getVar("id"), SACC_CNTL_BANK);
    $this->createControl($org_id, $account[$open_key]->getVar("id"), SACC_CNTL_OPEN);
    $this->createControl($org_id, $account[$asset_key]->getVar("id"), SACC_CNTL_ASST);
    $this->createControl($org_id, $account[$liability_key]->getVar("id"), SACC_CNTL_LIAB);
    $this->createControl($org_id, $account[$equity_key]->getVar("id"), SACC_CNTL_EQUI);
    $this->createControl($org_id, $account[$income_key]->getVar("id"), SACC_CNTL_INCO);
    $this->createControl($org_id, $account[$expense_key]->getVar("id"), SACC_CNTL_EXPE);

    return TRUE;
    //$this->loadAccounts($org);  //load the accounts
  }

 /**
   * return an array of All id, orgname pairs for use in an admin user form select box
   * 
   * @return array
   */
  function getSelectListAll() {
    $sql = sprintf("select id, org_name, row_flag from %s",$this->db->prefix(SACC_TBL_ORG));
    $result = $this->db->query($sql);
    $ret = array();
    while ($res = $this->db->fetchArray($result)) {
    	switch ($res['row_flag']) {
    		case SACC_RSTAT_DEF:
    			$ret[$res['id']] = $res['org_name'] . ' (' . SACC_RSTAT_DEF . ')';
    			break;
    		case SACC_RSTAT_SUS:
    			$ret[$res['id']] = $res['org_name'] . ' (' . SACC_RSTAT_SUS . ')';
    			break;
    		default:
    			$ret[$res['id']] = $res['org_name'];
    			break;
    	}
    }
    return $ret;
  }
 
  /**
   * return an array of Active id, orgname pairs for use in a end user form select box
   *
   * @return array
   */
  function getSelectList() {
    $sql = sprintf("select id, org_name from %s where row_flag= %s",$this->db->prefix(SACC_TBL_ORG),$this->db->quoteString(SACC_RSTAT_ACT));
    $result = $this->db->query($sql);
    $ret = array();
    while ($res = $this->db->fetchArray($result)) {
      $ret[$res['id']]=$res['org_name'];
    }
    return $ret;
  }
 
  /**
  * create sql string to insert object data
  *
  * @access private
  */
  function _ins_insert($cleanVars) {
    foreach ($cleanVars as $k => $v) {
      ${$k} = $v;
    }
    $sql = sprintf("INSERT INTO %s (id, base_crcy, org_name,row_flag,row_uid,row_dt) VALUES (%u,%s,%s,%s,%u,%s)",$this->db->prefix(SACC_TBL_ORG),$id,$this->db->quoteString($base_crcy),$this->db->quoteString($org_name),$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt));
    return $sql;
  }
 
  /**
  * create sql string to update object data
  *
  * @access private
  */
  function _ins_update($cleanVars) {
    foreach ($cleanVars as $k => $v) {
      ${$k} = $v;
    }
    $sql = sprintf("UPDATE %s SET base_crcy = %s,org_name = %s,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u",$this->db->prefix(SACC_TBL_ORG),$this->db->quoteString($base_crcy),$this->db->quoteString($org_name),$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt),$id);
    return $sql;
  }

  /**
  * Insert data into database - extend ancestor
  *
  * @param SACCOrg Handle to organisation object
  */
  function insert(&$code) {
   $base_crcy = $code->getVar('base_crcy');
   $base_crcy = (empty($base_crcy) ? SACC_DEF_CRCY : $base_crcy);
   $code->setVar('base_crcy',$base_crcy); //default currency if none given
   return parent::insert($code);
  }//end function insert
  
  /**
  * Function: countOrgs 
  *
  * Count the number of organisations
  *
  * @version 1
  * @return int number of organisations 
  */
	function countOrgs() {
		$sql = sprintf("SELECT count(*) from %s",$this->db->prefix(SACC_TBL_ORG));
		$result = $this->db->queryF($sql);
		$ret = $this->db->fetchRow($result);
		$ret = $ret[0];
		return $ret;
	}//end function countOrgs
	
} //end class SACCOrgHandler
?>