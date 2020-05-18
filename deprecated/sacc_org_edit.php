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
 * Edit an organisation name record
 *
 * Display form to allow organisation name to be changed
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
require __DIR__ . '/header.php';  //MUST include page header
/**
 * Xoops header file
 */
require_once XOOPS_ROOT_PATH . '/header.php'; // include the main header file
/**
 * CDM API functions
 */
require_once CDM_PATH . '/include/functions.php';
/**
 * SACC form objects and elements
 */
require_once SACC_PATH . '/class/class.sacc.form.php';

//Check to see if user logged in
global $xoopsUser;
if (empty($xoopsUser)) {
    redirect_header(SACC_URL . '/sacc_list_accounts.php', 1, _MD_SACC_ERR_5);
}

/**
 * Function: Display organisation details form
 * @version 1
 */
function dispForm()
{
    global $xoopsOption;

    global $xoopsTpl;

    global $_GET;

    $GLOBALS['xoopsOption']['template_main'] = 'sacc_edit_form.tpl';  // Set the template page to be used

    //Set up static text for form

    $xoopsTpl->assign('lang_pagetitle', _MD_SACC_PAGETITLE1);

    $xoopsTpl->assign('lang_copyright', _MD_SACC_COPYRIGHT);

    //retrieve organisation details

    $org_id = (int)$_GET['org_id'];

    $orgHandler = xoops_getModuleHandler('SACCOrg', SACC_DIR);

    if (0 != $org_id) {
        $org = $orgHandler->get($org_id);
    } else {
        $org = $orgHandler->create();
    }

    //Set up form fields

    if (0 == $org_id) {
        //if id = 0 then user has requested a new organisation setup so hide id

        $id = new XoopsFormHidden('org_id', 0);

        $crcy_val = CDMGetCode('SACCCONF', 'DEFCUR'); //get system default currency

        $orgname = '';

        $new_flag = new XoopsFormHidden('new_flag', true); //tell POST process we are new
    } else {
        // else display the current account id as label because it is primary key

        $id = new XoopsFormLabel(_MD_SACC_ORG1, $org_id);

        $id_hid = new XoopsFormHidden('org_id', $org_id); //still need to know id in POST process

        $crcy_val = $org->getVar('base_crcy');

        $orgname = $org->getVar('org_name');

        $new_flag = new XoopsFormHidden('new_flag', false);
    }//end if org_id==0

    $org_name = new XoopsFormText(_MD_SACC_ORG2, 'org_nm', 20, 20, $orgname);

    // display organisation base currency

    $base_crcy = new CDMFormSelectCurrency(_MD_SACC_PAGE2COL3, 'base_crcy', $crcy_val);

    $row_flag = new CDMFormSelectRstat(_MD_SACC_RSTATNM, 'row_flag', $org->getVar('row_flag'), 1, $org->getVar('row_flag'));

    $ret = getXoopsUser($org->getVar('row_uid'));

    $row_uid = new XoopsFormLabel(_MD_SACC_RUIDNM, $ret);

    $row_dt = new XoopsFormLabel(_MD_SACC_RDTNM, $org->getVar('row_dt'));

    $submit = new XoopsFormButton('', 'submit', _MD_SACC_SUBMIT, 'submit');

    $cancel = new XoopsFormButton('', 'cancel', _MD_SACC_CANCEL, 'submit');

    $reset = new XoopsFormButton('', 'reset', _MD_SACC_RESET, 'reset');

    $editForm = new XoopsThemeForm(_MD_SACC_PAGETITLE3, 'editForm', 'sacc_org_edit.php');

    $editForm->addElement($id);

    $editForm->addElement($org);

    if (0 != $org_id) {
        $editForm->addElement($id_hid);
    }

    $editForm->addElement($org_name);

    $editForm->addElement($base_crcy);

    $editForm->addElement($new_flag);

    $editForm->addElement($row_flag, true);

    $editForm->addElement($row_uid, false);

    $editForm->addElement($row_dt, false);

    $editForm->addElement($submit);

    $editForm->addElement($cancel);

    $editForm->addElement($reset);

    $editForm->assign($xoopsTpl);
    //   print_r($editForm);
} //end function dispForm

/**
 * Function: Submit form data to database
 * @version 1
 */
function submitForm()
{
    global $_POST;

    extract($_POST);

    $orgHandler = xoops_getModuleHandler('SACCOrg', SACC_DIR);

    if ($new_flag) {
        $orgData = $orgHandler->create();

        $orgData->setVar('id', $org_id);
    } else {
        $orgData = &$orgHandler->getall($org_id);
    }

    $orgData->setVar('org_name', $org_nm);

    $orgData->setVar('base_crcy', $base_crcy);

    $orgData->setVar('row_flag', $row_flag);

    if (!$orgHandler->insert($orgData)) {
        redirect_header(SACC_URL . '/index.php', 10, $orgHandler->getError());
    } else {
        //    print_r($orgData);

        $orgHandler->createAccounts($orgData);

        redirect_header(SACC_URL . '/index.php', 1, _MD_SACC_ORGED1);
    }//end if
} //end function submitForm

/* Main Program Block */
//if submit and cancel buttons not pressed then display a form
if (empty($_POST['submit'])) {
    if (empty($_POST['cancel'])) { //present new form for input
        dispForm();

        /**
         * Display the page
         */

        require XOOPS_ROOT_PATH . '/footer.php';
    } else { //user has cancelled form
        redirect_header(SACC_URL . '/index.php', 1, _MD_SACC_ORGED2);
    }//end if empty cancel
} else { //User has submitted form
    submitForm();
}//end if
