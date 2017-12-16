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
 * Account Object Handler
 * 
 * @package SACC
 * @subpackage SACCAccount
 * @author Ashley Kitson http://xoobs.net
 * @copyright (c) 2004 Ashley Kitson, UK
*/

if (!defined('XOOPS_ROOT_PATH')) { 
  exit('Call to include SACCAccount.php failed as XOOPS_ROOT_PATH not defined');
}
/**
* SACC base objects
*/
require_once SACC_PATH."/class/class.sacc.base.php";
/**
* SACC functions
*/
require_once CDM_PATH."/include/functions.php";


/**
 * Object handler for SACCAccount
 *
 * @package SACC
 * @subpackage SACCAccount
 * @author Ashley Kitson http://xoobs.net
 * @copyright (c) 2004 Ashley Kitson, UK
 */
class Xbs_SaccSACCAccountHandler extends CDMBaseHandler {

/**
  * Function: Constructor 
  *
  * @version 1
  * @param xoopsDatabase Handle to database object
  */  

	function Xbs_SaccSACCAccountHandler(&$db) {
    	$this->CDMBaseHandler($db); //call ancestor constructor
    	$this->classname = 'saccaccount';
    	$this->ins_tagname='sacc_ins_account';
  	}

  /**
   * Create account object -  overide ancestor because we need to know what type of account it is
   *
   * @param string $accType default null. Type of account to create, SACC_ACTP_INCOME, SACC_ACTP_EXPENSE, SACC_ACTP_ASSET,
   *										SACC_ACTP_LIABILITY, SACC_ACTP_BANK, SACC_ACTP_SUPPLIER, SACC_ACTP_CUSTOMER, 
   *										SACC_ACTP_EQUITY
   * @param boolean $isNew default TRUE. This is a new account we are creating
   * @return SACCAccount object else FALSE on failure
   */
  function &create($accType=null,$isNew = true) {
    switch ($accType) {
    case SACC_ACTP_INCOME:
      $obj = new SACCIncomeAc();
      break;
    case SACC_ACTP_EXPENSE:
      $obj = new SACCExpenseAc();
      break;
    case SACC_ACTP_ASSET:
      $obj = new SACCAssetAc();
      break;
    case SACC_ACTP_LIABILITY:
      $obj = new SACCLiabilityAc();
      break;
    case SACC_ACTP_BANK:
      $obj = new SACCBankAc();
      break;
    case SACC_ACTP_SUPPLIER:
      $obj = new SACCSupplierAc();
      break;
    case SACC_ACTP_CUSTOMER:
      $obj = new SACCCustomerAc();
      break;
    case SACC_ACTP_EQUITY:
      $obj = new SACCEquityAc();
      break;
    default:
      //we will always get an object even if it the base account type
      $obj = new SACCAccount();
    }//end switch
    if ($isNew && $obj) { //if it is new and the object was created
      $obj->setNew();
      $obj->unsetDirty();
    } else {
      if ($obj) {         //it is not new (forced by caller, usually &getall()) but obj was created
	$obj->unsetNew();
	$obj->unsetDirty();
      } else {
	$this->setError(-1,sprintf(_MD_CDM_ERR_2,$classname));
	return FALSE;      //obj was not created so return False to caller.
      }
    }
    return $obj;
  }//end function create

  /**
  * create sql string for getting object data
  *
  * @internal 
  */
  function &_get($key,$row_flag=null) {
    $sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_ACC),$key);
    $sql .= (empty($row_flag)?"":" and row_flag = ".$this->db->quoteString($row_flag));
    return $sql;
  }//end function _get

  /**
   * function getall - overide ancestor because we need to know account type
   *
   * @param int $id id of account to get
   * @param string $row_flag default null.  Row status flag
   * @return SACCAccount object on success else FALSE on failure
   */
  function &getall($id,$row_flag=null) {
    $test = (is_int($id) ? ($id > 0 ? TRUE : FALSE) : !empty($id) ? TRUE : FALSE); 
    if ($test) {
      $sql = "select ac_tp from ".$this->db->prefix(SACC_TBL_ACC)." where id = ".$id;
      if ($result = $this->db->query($sql)) {
		$ret = $this->db->fetchRow($result);
		$actype = $ret[0];
      } else {//no result
	$this->setError(-1,sprintf(_MD_SACC_ERR_6,strval($id)));
      }
      $account =& $this->create($actype,FALSE);
      if ($account) {
		$sql = $this->_get($id,$row_flag);

	if ($result = $this->db->query($sql)) {
	  if ($this->db->getRowsNum($result)==1) {
	    $account->assignVars($this->db->fetchArray($result));
	    $account->setBalance();
	    return $account;
	  } else {
	    $this->setError(-1,sprintf(_MD_SACC_ERR_7,strval($id)));
	  }
	} else {
	  $this->setError($this->db->errno(),$this->db->error());
	}//end if
      }//end if - error value set in call to create()
    } else {
      $this->setError(-1,sprintf(_MD_SACC_ERR_8,strval($id)));
    }//end if
    return false; //default return
  }//end function &getall

	/**
	* create sql to reload object data
	*
	* @internal 
	*/
  function &_reload($key=null) {
    $sql = sprintf("select * from %s where id = %u",$this->db->prefix(SACC_TBL_ACC),$key);
    return $sql;
  }

  
  /**
   * function reload - extend ancestor to add account information
   *
   * @param SACCAccount $obj handle to account object
   * @param int $key Accoint id
   * @return SACCAccount
   */
  function reload(&$obj,$key=null) {
    $ret = parent::reload($obj,$key);
    $this->aggregate($ret);
    return $ret;
  }

  /**
  * Update the account balance for a given account
  *
  * Recursive function that will update balances for the parent account
  *
  * @param int $ac_id account id
  * @param int $ac_dr debit account balance
  * @param int $ac_cr credit account balance
  */
  function updateBalances($ac_id,$ac_dr = 0,$ac_cr = 0) {
    if($ac_id == 0) return true;  //all done
    //else update the balance for this account
    $accountHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
    $accountData =& $accountHandler->getall($ac_id);
    $new_dr = $accountData->getVar('ac_dr') + $ac_dr;
    $new_cr = $accountData->getVar('ac_cr') + $ac_cr;
    $accountData->setVar('ac_dr',$new_dr);
    $accountData->setVar('ac_cr',$new_cr);
    $accountData->setBalance();
    $accountHandler->insert($accountData);
    $this->updateBalances($accountData->getVar('ac_prnt_id'),$ac_dr,$ac_cr);
    return true;
  }

  /**
   * function setOrder - sets the display order for accounts
   *
   * Recursive function, will set order for child accounts
   *
   * @param int $ac_id account id of account to set order for
   * @param int $order starting order number
   */
  function setOrder($ac_id,$order) {
    $sql= sprintf("UPDATE %s SET disp_order = %u WHERE id = %u",$this->db->prefix(SACC_TBL_ACC),$order,$ac_id);
    $result = $this->db->query($sql);
    $order ++;
    $sql = sprintf("SELECT id from %s WHERE ac_prnt_id = %u",$this->db->prefix(SACC_TBL_ACC),$ac_id);
    if ( $result = $this->db->query($sql)) {
      while ($arr=$this->db->fetchArray($result)) {
		$order = $this->setOrder($arr["id"],$order);
      }
    }
    return $order;
  }//end function setOrder

  /**
  * create sql string for object insert
  *
  * @internal 
  */
  function _ins_insert($cleanVars) {
    extract($cleanVars);
    $sql = sprintf("INSERT INTO %s (id,ac_prnt_id,org_id,ac_curr,ac_tp,ac_nm,ac_prps,ac_note,ac_dr,ac_cr,ac_level,has_kids,row_flag,row_uid,row_dt)",$this->db->prefix(SACC_TBL_ACC));
    $sql .= sprintf(" VALUES (%u,%u,%u,%s,%s,%s,%s,%s,%u,%u,%u,%u,%s,%u,%s)",$id,$ac_prnt_id,$org_id,$this->db->quoteString($ac_curr),$this->db->quoteString($ac_tp),$this->db->quoteString($ac_nm),$this->db->quoteString($ac_prps),$this->db->quoteString($ac_note),$ac_dr,$ac_cr,$ac_level,$has_kids,$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt));
    return $sql;
  }

  /**
  * create sql string for object update
  *
  * @internal 
  */
  function _ins_update($cleanVars) {
    extract($cleanVars);
    $sql = sprintf("UPDATE %s SET ac_prnt_id=%u,org_id=%u,ac_curr=%s,ac_tp=%s,ac_nm=%s,ac_prps=%s,ac_note=%s,ac_dr=%u,ac_cr=%u,ac_level=%u,has_kids=%u,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u",$this->db->prefix(SACC_TBL_ACC),$ac_prnt_id,$org_id,$this->db->quoteString($ac_curr),$this->db->quoteString($ac_tp),$this->db->quoteString($ac_nm),$this->db->quoteString($ac_prps),$this->db->quoteString($ac_note),$ac_dr,$ac_cr,$ac_level,$has_kids,$this->db->quoteString($row_flag),$row_uid,$this->db->quoteString($row_dt),$id);
    return $sql;
  }

  /**
   * Defunct any children of an account that has been defuncted itself
   *
   * @param int $account SACCAccount object to process
   */
  function defunctChildAccounts($account) {
    if ($account->getVar("has_kids")==1) {
      $sql = sprintf("select id from %s where ac_prnt_id = %u",$this->db->prefix(SACC_TBL_ACC),$account->getVar("id"));
      if ($result = $this->db->query($sql)) {
	$accHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
	while ($arr=$this->db->fetchArray($result)) {
	  $acc = $accHandler->get($arr["id"]);
	  $acc->setDefunct();
	  $accHandler->insert($acc);
	  $this->defunctChildAccounts($acc);
	}//end while
      }//end if
    }//end if
  }//end function defunctChildAccounts

  /**
  * Insert data into database - extend ancestor for pre processing
  *
  * @param SACCAccount Handle to account object
  */
  function insert(&$account) {
    if (!$account->isDirty()) { return true; }    // if data is untouched then don't save
    //Check if we are about to defunct the account and if so if the balance is zero
    if ($account->getVar("row_flag")==SACC_RSTAT_DEF) {
      $account->setBalance();
      if ($account->getVar("ac_net_bal")!=0) {
	$this->setError(-1,sprintf(_MD_SACC_ERR_9,$account->getVar("ac_nm")));
	return false;
      } else {
	//defunct any children of this account as well
	$this->defunctChildAccounts($account);
      }
    }//end if
    //  get organisation base currency
   $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
   $org = $orgHandler->get($account->getVar('org_id'));
   //  set default currency
   $crcy = $account->getVar('ac_curr');
   $crcy = (empty($crcy) ? $org->getVar('base_crcy') : $crcy);
   $account->setVar('ac_curr',$crcy); 

   //work out level information for later account display
   $ac_prnt_id = $account->getVar("ac_prnt_id");
   if ($ac_prnt_id > 0) { //has a parent so process
     $sql = sprintf("SELECT ac_level from %s where id = %u",$this->db->prefix(SACC_TBL_ACC),$ac_prnt_id);

     if($result = $this->db->query($sql)) {
       $arr=$this->db->fetchArray($result);
       $account->setVar("ac_level",$arr["ac_level"] + 1);
       //tell parent it has kids
       $sql = sprintf("UPDATE %s SET has_kids = 1 WHERE id = %u",$this->db->prefix(SACC_TBL_ACC),$ac_prnt_id);
       $result = $this->db->query($sql);
     }//end if
   } else {
     $account->setVar("ac_level",0);
   }//end if
   $account->setVar("has_kids",0); //this account doesn't have kids yet!
   //run ancestor
   $newac = parent::insert($account);
   //set up the display levels for the accounts
   $sql = sprintf("SELECT id FROM %s WHERE ac_prnt_id = 0",$this->db->prefix(SACC_TBL_ACC));
   $order = 0;
   $result = $this->db->query($sql);
   while ($arr=$this->db->fetchArray($result)) {
     $order = $this->setOrder($arr["id"],$order);
   }//end while
   return $newac;
  }//end function insert

  /**
   * Function: return array of code, value pairs for use in drop down select box
   *
   * @version 1.1
   * @param int $org_id Id of organisation to get list for
   * @param boolean $ignore_prnt_setting Ignore the Use parent config setting.  This ensures all accounts are included
   * @return array array([id]->account name)
   */
  function getSelectList($org_id = 1, $ignore_prnt_setting = FALSE) {
    $sql = sprintf("select id, ac_nm, ac_level from %s where org_id = %u and row_flag= %s ",$this->db->prefix(SACC_TBL_ACC),$org_id,$this->db->quoteString(SACC_RSTAT_ACT));
    $sql .= ((SACC_CFG_USEPRNT==0 AND !$ignore_prnt_setting) ? "and has_kids = 0 " : "");
    $sql .= "order by disp_order";
    $result = $this->db->query($sql);
    $ret = array();
    while ($res = $this->db->fetchArray($result)) {
    	//indent the account name according to its level in the account hiearacrchy
		$disp_level = $res['ac_level']; 
	    $slen = strlen($res['ac_nm']) + ($disp_level * 12);
    	$res['ac_nm'] = str_pad($res['ac_nm'],$slen,"&nbsp;",STR_PAD_LEFT);
    	//and construct the return array
    	$ret[$res['id']]=$res['ac_nm'];
    }
    return $ret;
  }

  /**
   * function loadEntries - load account entries for the given account
   *
   * @param SACCAccount Handle to account object
   */

  function loadEntries(&$obj) {
    $id = intval($obj->getVar('id')); //get account id
    $sql=sprintf("select id from %s where ac_id = %u",$this->db->prefix(SACC_TBL_ENTRY),$id); //get list of account entries
    if ($result=$this->db->query($sql)) {
      $ret = array();
      $entHandler =& xoops_getmodulehandler("SACCEntry",SACC_DIR);
      while ($arr=$this->db->fetchArray($result)) {
	$entry = $entHandler->get($arr['id']);
	$ret[] = $entry;
      }
      $obj->setVar('entries',$ret);
    }
  }

} //end class SACCAccountHandler
?>