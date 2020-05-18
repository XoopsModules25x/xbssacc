<?php declare(strict_types=1);

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
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
// URL:       http://akitson.bbcb.co.uk                                      //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    Simple Accounts (SACC)                                         //
// ------------------------------------------------------------------------- //

/**
 * Edit an account information record
 *
 * Display a single account information record, allow edit and submission to database
 *
 * @author     Ashley Kitson http://akitson.bbcb.co.uk
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage User_interface
 * @version    1
 */

/**
 * MUST include module header
 */
require __DIR__ . '/header.php';
/**
 * Include Xoops header
 */
require XOOPS_ROOT_PATH . '/header.php';
/**
 * SACC form objects
 */
require_once SACC_PATH . '/class/class.sacc.form.php';
/**
 * CDM form objects
 */
require_once CDM_PATH . '/class/class.cdm.form.php';
/**
 * CDM common functions
 */
require_once CDM_PATH . '/include/functions.php';

//Check to see if user logged in
global $xoopsUser;
if (empty($xoopsUser)) {
    redirect_header(SACC_URL . '/sacc_list_accounts.php', 1, _MD_SACC_ERR_5);
}

/**
 * Function: Display account information form
 *
 * @version 1
 */
function dispForm()
{
    global $xoopsOption;

    global $xoopsTpl;

    global $_GET;

    $GLOBALS['xoopsOption']['template_main'] = 'sacc_edit_form.tpl';  // Set the template page to be used

    //Set up static text for form

    $xoopsTpl->assign('lang_pagetitle', _MD_SACC_PAGETITLE4);

    $xoopsTpl->assign('lang_copyright', _MD_SACC_COPYRIGHT);

    $accountHandler = xoops_getModuleHandler('SACCAccount', SACC_DIR);

    $ac_id = (int)$_GET['ac_id'];

    $org_id = (int)$_GET['org_id'];

    if (!empty($ac_id) && '0' != $ac_id) { //retrieve the existing data object
        $accountData = &$accountHandler->getall($ac_id);
    } else { //create a new account object
        $accountData = $accountHandler->create();

        $ac_id = 0;
    }//end if

    //check object instantiated and proceed

    if ($accountData) {
        //Set up form fields

        if ('0' == $ac_id) {
            //if id = "0" then user has requested a new account setup so hide account id

            $id = new XoopsFormHidden('ac_id', 0);

            $new_flag = new XoopsFormHidden('new_flag', true); //tell POST process we are new

            // Allow selection of organisation

            $org = new SACCFormSelectOrg(_MD_SACC_ACED2, 'org_id', $org_id);

            //define default currency

            $crcy = CDMGetCode('SACCCONF', 'DEFCUR');
        } else { // else display the current account id as label because it is primary key
            $id = new XoopsFormLabel(_MD_SACC_ACED1, $ac_id);

            $id_hid = new XoopsFormHidden('ac_id', $ac_id); //still need to know id in POST process

            $new_flag = new XoopsFormHidden('new_flag', false);

            //display organisation as label (cannot change account organisation)

            $orgHandler = xoops_getModuleHandler('SACCOrg', SACC_DIR);

            $orgData = &$orgHandler->getall($org_id);

            $org = new XoopsFormHidden('org_id', $org_id);

            $org_label = new XoopsFormLabel(_MD_SACC_ACED2, $orgData->getVar('org_name'));

            $crcy = $accountData->getVar('ac_curr');
        }//end if ac_id==0

        $ac_tp = new SACCFormSelectAccType(_MD_SACC_ACED3, 'ac_tp', $accountData->getVar('ac_tp'));

        $ac_prnt_id = new SACCFormSelectAccPrnt(_MD_SACC_ACED9, 'ac_prnt_id', $org_id, $accountData->getVar('ac_prnt_id'));

        $ac_curr = new CDMFormSelectCurrency(_MD_SACC_ACED4, 'ac_curr', $crcy);

        $ac_nm = new XoopsFormText(_MD_SACC_ACED5, 'ac_nm', 20, 20, $accountData->getVar('ac_nm'));

        $ac_prps = new XoopsFormTextArea(_MD_SACC_ACED6, 'ac_prps', $accountData->getVar('ac_prps'));

        $ac_note = new XoopsFormTextArea(_MD_SACC_ACED7, 'ac_note', $accountData->getVar('ac_note'));

        $rf = $accountData->getVar('row_flag');

        $row_flag = new CDMFormSelectRstat(_MD_SACC_RSTATNM, 'row_flag', $rf, 1, $rf);

        $ret = getXoopsUser($accountData->getVar('row_uid'));

        $row_uid = new XoopsFormLabel(_MD_SACC_RUIDNM, $ret);

        $row_dt = new XoopsFormLabel(_MD_SACC_RDTNM, $accountData->getVar('row_dt'));

        $submit = new XoopsFormButton('', 'submit', _MD_SACC_SUBMIT, 'submit');

        $cancel = new XoopsFormButton('', 'cancel', _MD_SACC_CANCEL, 'submit');

        $reset = new XoopsFormButton('', 'reset', _MD_SACC_RESET, 'reset');

        $editForm = new XoopsThemeForm(_MD_SACC_ACED0, 'editForm', 'sacc_acc_edit.php');

        $editForm->addElement($id);

        $editForm->addElement($org);

        if ('0' != $id) {
            $editForm->addElement($id_hid);

            $editForm->addElement($org_label);
        }

        $editForm->addElement($new_flag);

        $editForm->addElement($ac_nm, true);

        $editForm->addElement($ac_tp, true);

        $editForm->addElement($ac_prnt_id, false);

        $editForm->addElement($ac_curr, true);

        $editForm->addElement($ac_prps, false);

        $editForm->addElement($ac_note, false);

        $editForm->addElement($row_flag, true);

        $editForm->addElement($row_uid, false);

        $editForm->addElement($row_dt, false);

        $editForm->addElement($submit);

        $editForm->addElement($cancel);

        $editForm->addElement($reset);

        $editForm->assign($xoopsTpl);
    }
}//end function dispForm

/**
 * Function: Submit account information data to database
 *
 * @version 1
 */
function submitForm()
{
    global $_POST;

    extract($_POST);

    $accountHandler = xoops_getModuleHandler('SACCAccount', SACC_DIR);

    if ($new_flag) {
        $accountData = $accountHandler->create();

        $accountData->setVar('id', $ac_id);

        $accountData->setVar('org_id', $org_id);
    } else {
        $accountData = &$accountHandler->getall($ac_id);
    }

    $accountData->setVar('ac_nm', $ac_nm);

    $accountData->setVar('ac_tp', $ac_tp);

    $accountData->setVar('ac_prnt_id', $ac_prnt_id);

    $accountData->setVar('ac_curr', $ac_curr);

    $accountData->setVar('ac_note', $ac_note);

    $accountData->setVar('ac_prps', $ac_prps);

    $accountData->setVar('row_flag', $row_flag);

    if (!$accountHandler->insert($accountData)) {
        redirect_header(SACC_URL . '/sacc_accounts_list.php', 10, $accountHandler->getError());
    } else {
        redirect_header(SACC_URL . '/sacc_accounts_list.php', 1, _MD_SACC_ACED10);
    }//end if
}//end function submitForm

/* Main Program Block */
//if submit and cancel buttons not pressed then display a form
if (empty($_POST['submit'])) {
    if (empty($_POST['cancel'])) {//present new form for input
        dispForm();

        /**
         * Display the page
         */

        require XOOPS_ROOT_PATH . '/footer.php';
    } else {
        redirect_header(SACC_URL . '/sacc_accounts_list.php', 1, _MD_SACC_ACED8);
    }//end if empty cancel
} else { //User has submitted form
    submitForm();
}//end if
