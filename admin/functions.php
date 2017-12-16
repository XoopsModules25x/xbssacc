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
// Copyright: (c) 2005, Ashley Kitson
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    Simple Accounts System (SACC)                                  //
// ------------------------------------------------------------------------- //

/**
* Admin page functions
*
* @author Ashley Kitson http://xoobs.net
* @copyright 2005 Ashley Kitson, UK
* @package SACC
* @subpackage Admin
* @access private
* @version 1
*/


/**
* Function: Display list of organisations 
*
* Display list of organisations to allow user to choose one to edit.
* User can also create a new organisation
*
* @version 1
* @param boolean $forAccounts Is this form being called by the accounts administration screen
*/
function adminSelectOrg($forAccounts = false) {
	//Check to see if there are any organisations created yet.
	//If not then display an organisation details input form
	// else allow user to select an organisation
	$orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
	if ($orgHandler->countOrgs()==0) {
		displayOrgForm();
	} else {
		// Get data and assign to form
		$org_id = new SACCFormSelectOrgAll(_AM_SACC_SELORG,'org_id',SACC_CFG_DEFORG,4);
		$submit = new XoopsFormButton("","submit",_AM_SACC_GO,"submit");
		if ($forAccounts) {
			$orgForm = new XoopsThemeForm(_AM_SACC_ORGFORM,"orgform","adminaccount.php");
		} else {
			$insert = new XoopsFormButton(_AM_SACC_INSERT_DESC,"insert",_AM_SACC_INSERT,"submit");
			$orgForm = new XoopsThemeForm(_AM_SACC_ORGFORM,"orgform","adminorg.php");
		}
		$orgForm->addElement($org_id,true);
		$orgForm->addElement($submit);
		if (!$forAccounts) {
			$orgForm->addElement($insert);
		}
		$orgForm->display();
	}
}//end function

/**
* Function: Display organisation details form
*
* @version 1
* @param int $org_id id of organisation to edit or create a new one if zero
*/
function displayOrgForm($org_id = 0) {

  global $xoopsOption;
  //Cannot use smarty templates in admin yet (until xoops v2.2)
  //global $xoopsTpl;
  //$xoopsOption['template_main'] = _AM_SACC_EDITFORM;  // Set the template page to be used

  //Set up static text for form
  //$xoopsTpl->assign('lang_pagetitle',_AM_SACC_PAGETITLE1);
  //$xoopsTpl->assign('lang_copyright',_AM_SACC_COPYRIGHT);

  //retrieve organisation details
  $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
  if ($org_id!=0) {
    $org =& $orgHandler->getall($org_id);
  } else {
    $org =& $orgHandler->create();
  } 

  //set flag if record is defunct - very important as no changes are allowable
  // and we only show readable text for a defunct record
  $isDefunct = ($org->getVar("row_flag") == CDM_RSTAT_DEF);
  
  //Set up form fields
  if ($org_id==0) { 
    //if id = 0 then user has requested a new organisation setup so hide id
    $id = new XoopsFormHidden("org_id",0);
    $orgname ="";
    $crcy_val = SACC_CFG_DEFCURR;
    $new_flag = new XoopsFormHidden("new_flag",TRUE); //tell POST process we are new
    $old_rstat= new XoopsFormHidden("old_rstat",CDM_RSTAT_ACT); //set default old status
  } else { 
    // else display the current organasition id as label because it is primary key
    $id = new XoopsFormLabel(_AM_SACC_ORGED1,$org_id);
    $id_hid = new XoopsFormHidden("org_id",$org_id); //still need to know id in POST process
    $crcy_val = $org->getVar("base_crcy");
    $orgname = $org->getVar("org_name");
    $new_flag = new XoopsFormHidden("new_flag",FALSE);
    $old_rstat= new XoopsFormHidden("old_rstat",$org->getVar("row_flag")); //need to know old status when record saved
  }//end if org_id==0

  if ($isDefunct) {
  	$org_name = new XoopsFormLabel(_AM_SACC_ORGED2,$orgname);
  	$base_crcy = new XoopsFormLabel(_AM_SACC_ORGED3,$crcy_val);
  	$row_flag = new  XoopsFormLabel(_AM_SACC_RSTATNM, CDM_RSTAT_DEF);
  } else {
  	$org_name = new XoopsFormText(_AM_SACC_ORGED2,"org_nm",20,20,$orgname);
  	$base_crcy = new CDMFormSelectCurrency(_AM_SACC_ORGED3,'base_crcy',$crcy_val);
  	$row_flag = new CDMFormSelectRstat(_AM_SACC_RSTATNM,"row_flag",$org->getVar("row_flag"),1,$org->getVar("row_flag"));
  }
  
  
  $ret = getXoopsUser($org->getVar("row_uid"));
  $row_uid = new XoopsFormLabel(_AM_SACC_RUIDNM,$ret);
  $row_dt = new XoopsFormLabel(_AM_SACC_RDTNM,$org->getVar('row_dt'));
  $submit = new XoopsFormButton("","save",_AM_SACC_SUBMIT,"submit");
  $cancel = new XoopsFormButton("","cancel",_AM_SACC_CANCEL,"submit");
  $reset = new XoopsFormButton("","reset",_AM_SACC_RESET,"reset");

  $editForm = new XoopsThemeForm(_AM_SACC_ORGED0,"editForm","adminorg.php");
  $editForm->addElement($id);
  $editForm->addElement($org);
  if ($org_id!=0) {
    $editForm->addElement($id_hid);
  }
  $editForm->addElement($org_name);
  $editForm->addElement($base_crcy);
  $editForm->addElement($new_flag);
  $editForm->addElement($old_rstat);
  $editForm->addElement($row_flag,true);
  $editForm->addElement($row_uid,false);
  $editForm->addElement($row_dt,false);
  //if the record is defunct then don't display submit button
  if (!$isDefunct) {
  	$editForm->addElement($submit);
  }
  $editForm->addElement($cancel);
  //if the record is defunct then don't display reset button
  if (!$isDefunct) {
  	$editForm->addElement($reset);
  }
  //$editForm->assign($xoopsTpl);
  $editForm->display();
} //end function displayOrgForm

/**
* Function: Save organisation details 
*
* Write org data to database
*
* @version 1
* @param int $org_id Organisation id
*/
function submitOrgForm() {

  global $HTTP_POST_VARS;
  extract($HTTP_POST_VARS);
  $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
  if ($new_flag) {
    $orgData =& $orgHandler->create();
    $orgData->setVar('id',$org_id);
  } else {
    $orgData =& $orgHandler->getall($org_id);
  }

  $orgData->setVar('org_name',$org_nm);
  $orgData->setVar('base_crcy',$base_crcy);
  if (($old_rstat != CDM_RSTAT_DEF) AND ($row_flag == CDM_RSTAT_DEF)) { //properly defunct the record
  	$orgHandler->loadAccounts($orgData);
  	$orgData->setDefunct();
  } else {
  	$orgData->setVar('row_flag',$row_flag);
  }
  $isNew = $orgData->isNew();  
  if (!$orgHandler->insert($orgData)) {
    redirect_header(SACC_URL."/admin/adminorg.php",1,$orgHandler->getError()); 
  } else {
  	if ($isNew) {
    	$orgHandler->createAccounts($orgData);
  	}
    redirect_header(SACC_URL."/admin/adminorg.php",1,_AM_SACC_ORGED100);
  }//end if
} //end function submitOrgForm

/**
* Function: Edit an organisation data record 
*
* Edit or create a new organisation record
*
* @version 1
* @param int $org_id id of organisation to edit or create a new one if zero
* @param boolean $save If true then save organisation details else displaya form
*/
function adminEditOrg($org_id = 0, $save = false) {
	If ($save) {
		submitOrgForm($org_id);
	} else {
		displayOrgForm($org_id);
	}
}

/**
* Function: Select an account to edit 
*
* List accounts and allow selection of accpount edit or insert of a new one
* The function will always ask for user to select an organisation first
*
* @param int $org_id Id of organiastion to display list of accounts for. If zero, ask user to select organisation
* @version 1
*/
function adminSelectAcc($org_id = 0) {
	if ($org_id==0) { //ask user to select an organisation
		adminSelectOrg(true);
	} else { //display list of accounts for an organisation
		$ac_id = new SACCFormSelectAccount(_AM_SACC_SELACC,'ac_id',$org_id,null,10, TRUE);
		$org = new XoopsFormHidden("org_id",$org_id);
		$submit = new XoopsFormButton("","go",_AM_SACC_GO,"submit");
		$insert = new XoopsFormButton(_AM_SACC_INSERT_DESC,"insert",_AM_SACC_INSERT,"submit");
		$accForm = new XoopsThemeForm(_AM_SACC_ACCFORM,"accountform","adminaccount.php");
		$accForm->addElement($org);
		$accForm->addElement($ac_id,true);
		$accForm->addElement($submit);
		$accForm->addElement($insert);
		$accForm->display();
	} 
}

/**
* Function: Display the account edit form 
*
* @version 1
* @param int $org_id Identifier for an organisation
* @param int $ac_id Identifier for an account
*/
function displayAccForm($org_id, $ac_id) {

  global $HTTP_GET_VARS;
  extract($HTTP_GET_VARS);

  //cannot use smarty templates until xoops V2.2
  //$xoopsOption['template_main'] = _AM_SACC_EDITFORM;  // Set the template page to be used
  //Set up static text for form
  //$xoopsTpl->assign('lang_pagetitle',_AM_SACC_PAGETITLE4);
  //$xoopsTpl->assign('lang_copyright',_AM_SACC_COPYRIGHT);
  $accountHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
  if (!empty($ac_id) && $ac_id!="0") { //retrieve the existing data object
    $accountData =& $accountHandler->getall($ac_id);
  } else { //create a new account object
    $accountData =& $accountHandler->create(); 
    $ac_id=0;
  }//end if
   //check object instantiated and proceed
  if ($accountData) {
    //Set up form fields
    if ($ac_id=="0") { 
      //if id = "0" then user has requested a new account setup so hide account id
      $id = new XoopsFormHidden("ac_id",0);
      $new_flag = new XoopsFormHidden("new_flag",TRUE); //tell POST process we are new
      // Allow selection of organisation
      $org = new SACCFormSelectOrg(_AM_SACC_ACED2,"org_id",$org_id);
      //define default currency
      $crcy = SACC_CFG_DEFCURR;
    } else { // else display the current account id as label because it is primary key
      $id = new XoopsFormLabel(_AM_SACC_ACED1,$ac_id);
      $id_hid = new XoopsFormHidden("ac_id",$ac_id); //still need to know id in POST process
      $new_flag = new XoopsFormHidden("new_flag",FALSE);
      //display organisation as label (cannot change account organisation)
      $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR); 
      $orgData =& $orgHandler->getall($org_id);
      $org = new  XoopsFormHidden("org_id",$org_id);
      $org_label = new XoopsFormLabel(_AM_SACC_ACED2,$orgData->getVar('org_name'));
      $crcy = $accountData->getVar("ac_curr");
    }//end if ac_id==0
    $ac_tp = new SACCFormSelectAccType(_AM_SACC_ACED3,"ac_tp",$accountData->getVar("ac_tp"));
    $ac_prnt_id = new SACCFormSelectAccPrnt(_AM_SACC_ACED9,"ac_prnt_id",$org_id,$accountData->getVar("ac_prnt_id"));
    $ac_curr = new CDMFormSelectCurrency(_AM_SACC_ACED4,"ac_curr",$crcy);
    $ac_nm = new XoopsFormText(_AM_SACC_ACED5,"ac_nm",20,20,$accountData->getVar("ac_nm"));
    $ac_prps = new XoopsFormTextArea(_AM_SACC_ACED6,"ac_prps",$accountData->getVar("ac_prps"));
    $ac_note = new XoopsFormTextArea(_AM_SACC_ACED7,"ac_note",$accountData->getVar("ac_note"));
    $rf=$accountData->getVar("row_flag");
    $row_flag = new CDMFormSelectRstat(_MD_SACC_RSTATNM,"row_flag",$rf,1,$rf);
    $ret = getXoopsUser($accountData->getVar("row_uid"));
    $row_uid = new XoopsFormLabel(_MD_SACC_RUIDNM,$ret);
    $row_dt = new XoopsFormLabel(_MD_SACC_RDTNM,$accountData->getVar('row_dt'));
    $submit = new XoopsFormButton("","save",_AM_SACC_SUBMIT,"submit");
    $cancel = new XoopsFormButton("","cancel",_AM_SACC_CANCEL,"submit");
    $reset = new XoopsFormButton("","reset",_AM_SACC_RESET,"reset");
    $editForm = new XoopsThemeForm(_AM_SACC_ACED0,"editForm","adminaccount.php");
    $editForm->addElement($id);
    $editForm->addElement($org);
    if ($id!="0") {
      $editForm->addElement($id_hid);
      $editForm->addElement($org_label);
    }
    $editForm->addElement($new_flag);
    $editForm->addElement($ac_nm,true);
    $editForm->addElement($ac_tp,true);
    $editForm->addElement($ac_prnt_id,false);
    $editForm->addElement($ac_curr,true);
    $editForm->addElement($ac_prps,false);
    $editForm->addElement($ac_note,false);
    $editForm->addElement($row_flag,true);
    $editForm->addElement($row_uid,false);
    $editForm->addElement($row_dt,false);
    $editForm->addElement($submit);
    $editForm->addElement($cancel);
    $editForm->addElement($reset);
    //$editForm->assign($xoopsTpl);
    $editForm->display();
  }
} //end function displayAccForm

/**
* Function: Save an account record entry
*
* @version 1
* @param int $org_id Identifier for an organisation
* @param int $ac_id Identifier for an account
*/
function submitAccForm($org_id, $ac_id) {
  global $HTTP_POST_VARS;
  extract($HTTP_POST_VARS);
  $accountHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
  if ($new_flag) {
    $accountData =& $accountHandler->create();
    $accountData->setVar('id',$ac_id);
    $accountData->setVar('org_id',$org_id);
  } else {
    $accountData =& $accountHandler->getall($ac_id);
  }
  $accountData->setVar('ac_nm',$ac_nm);
  $accountData->setVar('ac_tp',$ac_tp);
  $accountData->setVar('ac_prnt_id',$ac_prnt_id);
  $accountData->setVar('ac_curr',$ac_curr);
  $accountData->setVar('ac_note',$ac_note);
  $accountData->setVar('ac_prps',$ac_prps);
  $accountData->setVar('row_flag',$row_flag);
  if (!$accountHandler->insert($accountData)) {
    redirect_header(SACC_URL."/admin/adminaccount.php",10,$accountHandler->getError()); 
  } else {
    redirect_header(SACC_URL."/admin/adminaccount.php?curr_org_id=".$org_id,1,_AM_SACC_ACED100);
  }//end if
	
}

/**
* Function: Edit or save an account 
*
* Displays account edit form or saves an account's details
*
* @version 1
* @param int $org_id Identifier for an organisation
* @param int $ac_id Identifier for an account
* @param boolean $save Set true if account details are to be saved
*/
function adminEditAcc($org_id = 0, $ac_id = 0, $save = false) {
	If ($save) {
		submitAccForm($org_id, $ac_id);
	} else {
		displayAccForm($org_id, $ac_id);
	}
}
?>