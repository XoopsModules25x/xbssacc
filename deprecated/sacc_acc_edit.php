<?php declare(strict_types=1);

use XoopsModules\Xbscdm;
use XoopsModules\Xbssacc\Form;
use XoopsModules\Xbssacc\Helper;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

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
 * CDM common functions
 */
//require_once CDM_PATH . '/include/functions.php';

//Check to see if user logged in
global $xoopsUser;
if (empty($xoopsUser)) {
    redirect_header(SACC_URL . '/sacc_list_accounts.php', 1, _MD_XBSSACC_ERR_5);
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

    $xoopsTpl->assign('lang_pagetitle', _MD_XBSSACC_PAGETITLE4);

    $xoopsTpl->assign('lang_copyright', _MD_XBSSACC_COPYRIGHT);

    $accountHandler = Helper::getInstance()->getHandler('Account');

    $ac_id = (int)$_GET['ac_id'];

    $org_id = (int)$_GET['org_id'];

    if (!empty($ac_id) && '0' != $ac_id) { //retrieve the existing data object
        $accountData = &$accountHandler->getAll($ac_id);
    } else { //create a new account object
        $accountData = $accountHandler->create();

        $ac_id = 0;
    }//end if

    //check object instantiated and proceed

    if ($accountData) {
        //Set up form fields

        if ('0' == $ac_id) {
            //if id = "0" then user has requested a new account setup so hide account id

            $id = new \XoopsFormHidden('ac_id', 0);

            $new_flag = new \XoopsFormHidden('new_flag', true); //tell POST process we are new

            // Allow selection of organisation

            $org = new Form\FormSelectOrg(_MD_XBSSACC_ACED2, 'org_id', $org_id);

            //define default currency

            $crcy = CDMGetCode('SACCCONF', 'DEFCUR');
        } else { // else display the current account id as label because it is primary key
            $id = new \XoopsFormLabel(_MD_XBSSACC_ACED1, $ac_id);

            $id_hid = new \XoopsFormHidden('ac_id', $ac_id); //still need to know id in POST process

            $new_flag = new \XoopsFormHidden('new_flag', false);

            //display organisation as label (cannot change account organisation)

            $orgHandler = Helper::getInstance()->getHandler('Org');

            $orgData = &$orgHandler->getAll($org_id);

            $org = new \XoopsFormHidden('org_id', $org_id);

            $org_label = new \XoopsFormLabel(_MD_XBSSACC_ACED2, $orgData->getVar('org_name'));

            $crcy = $accountData->getVar('ac_curr');
        }//end if ac_id==0

        $ac_tp = new Form\FormSelectAccType(_MD_XBSSACC_ACED3, 'ac_tp', $accountData->getVar('ac_tp'));

        $ac_prnt_id = new Form\FormSelectAccPrnt(_MD_XBSSACC_ACED9, 'ac_prnt_id', $org_id, $accountData->getVar('ac_prnt_id'));

        $ac_curr = new Xbscdm\Form\FormSelectCurrency(_MD_XBSSACC_ACED4, 'ac_curr', $crcy);

        $ac_nm = new \XoopsFormText(_MD_XBSSACC_ACED5, 'ac_nm', 20, 20, $accountData->getVar('ac_nm'));

        $ac_prps = new \XoopsFormTextArea(_MD_XBSSACC_ACED6, 'ac_prps', $accountData->getVar('ac_prps'));

        $ac_note = new \XoopsFormTextArea(_MD_XBSSACC_ACED7, 'ac_note', $accountData->getVar('ac_note'));

        $rf = $accountData->getVar('row_flag');

        $row_flag = new Xbscdm\Form\FormSelectRstat(_MD_XBSSACC_RSTATNM, 'row_flag', $rf, 1, $rf);

        $ret = getXoopsUser($accountData->getVar('row_uid'));

        $row_uid = new \XoopsFormLabel(_MD_XBSSACC_RUIDNM, $ret);

        $row_dt = new \XoopsFormLabel(_MD_XBSSACC_RDTNM, $accountData->getVar('row_dt'));

        $submit = new \XoopsFormButton('', 'submit', _MD_XBSSACC_SUBMIT, 'submit');

        $cancel = new \XoopsFormButton('', 'cancel', _MD_XBSSACC_CANCEL, 'submit');

        $reset = new \XoopsFormButton('', 'reset', _MD_XBSSACC_RESET, 'reset');

        $editForm = new \XoopsThemeForm(_MD_XBSSACC_ACED0, 'editForm', 'sacc_acc_edit.php');

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

    $accountHandler = Helper::getInstance()->getHandler('Account');

    if ($new_flag) {
        $accountData = $accountHandler->create();

        $accountData->setVar('id', $ac_id);

        $accountData->setVar('org_id', $org_id);
    } else {
        $accountData = &$accountHandler->getAll($ac_id);
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
        redirect_header(SACC_URL . '/sacc_accounts_list.php', 1, _MD_XBSSACC_ACED10);
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
        redirect_header(SACC_URL . '/sacc_accounts_list.php', 1, _MD_XBSSACC_ACED8);
    }//end if empty cancel
} else { //User has submitted form
    submitForm();
}//end if
