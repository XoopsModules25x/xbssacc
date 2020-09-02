<?php declare(strict_types=1);

use XoopsModules\Xbssacc\{Form,
    Helper,
    Utility
};

use XoopsModules\Xbscdm;

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
 * Admin page functions
 *
 * @param mixed $forAccounts
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Admin
 * @access        private
 * @version       1
 * @copyright (c) 2004, Ashley Kitson
 */

/**
 * Function: Display list of organisations
 *
 * Display list of organisations to allow user to choose one to edit.
 * User can also create a new organisation
 *
 * @param bool $forAccounts Is this form being called by the accounts administration screen
 * @version 1
 */
function adminSelectOrg($forAccounts = false)
{
    //Check to see if there are any organisations created yet.

    //If not then display an organisation details input form

    // else allow user to select an organisation

    $orgHandler = Helper::getInstance()->getHandler('Org');

    if (0 == $orgHandler->countOrgs()) {
        displayOrgForm();
    } else {
        // Get data and assign to form

        $org_id = new Form\FormSelectOrgAll(_AM_XBSSACC_SELORG, 'org_id', SACC_CFG_DEFORG, 4);

        $submit = new \XoopsFormButton('', 'submit', _AM_XBSSACC_GO, 'submit');

        if ($forAccounts) {
            $orgForm = new \XoopsThemeForm(_AM_XBSSACC_ORGFORM, 'orgform', 'adminaccount.php');
        } else {
            $insert = new \XoopsFormButton(_AM_XBSSACC_INSERT_DESC, 'insert', _AM_XBSSACC_INSERT, 'submit');

            $orgForm = new \XoopsThemeForm(_AM_XBSSACC_ORGFORM, 'orgform', 'adminorg.php');
        }

        $orgForm->addElement($org_id, true);

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
 * @param int $org_id id of organisation to edit or create a new one if zero
 * @version 1
 */
function displayOrgForm($org_id = 0)
{
    global $xoopsOption;

    //Cannot use smarty templates in admin yet (until xoops v2.2)

    //global $xoopsTpl;

    //$GLOBALS['xoopsOption']['template_main'] = _AM_XBSSACC_EDITFORM;  // Set the template page to be used

    //Set up static text for form

    //$xoopsTpl->assign('lang_pagetitle',_AM_XBSSACC_PAGETITLE1);

    //$xoopsTpl->assign('lang_copyright',_AM_XBSSACC_COPYRIGHT);

    //retrieve organisation details

    $orgHandler = Helper::getInstance()->getHandler('Org');

    if (0 != $org_id) {
        $org = $orgHandler->getAll($org_id);
    } else {
        $org = $orgHandler->create();
    }

    //set flag if record is defunct - very important as no changes are allowable

    // and we only show readable text for a defunct record

    $isDefunct = (CDM_RSTAT_DEF == $org->getVar('row_flag'));

    //Set up form fields

    if (0 == $org_id) {
        //if id = 0 then user has requested a new organisation setup so hide id

        $id = new \XoopsFormHidden('org_id', 0);

        $orgname = '';

        $crcy_val = SACC_CFG_DEFCURR;

        $new_flag  = new \XoopsFormHidden('new_flag', true); //tell POST process we are new
        $old_rstat = new \XoopsFormHidden('old_rstat', CDM_RSTAT_ACT); //set default old status
    } else {
        // else display the current organasition id as label because it is primary key

        $id = new \XoopsFormLabel(_AM_XBSSACC_ORGED1, $org_id);

        $id_hid = new \XoopsFormHidden('org_id', $org_id); //still need to know id in POST process

        $crcy_val = $org->getVar('base_crcy');

        $orgname = $org->getVar('org_name');

        $new_flag = new \XoopsFormHidden('new_flag', false);

        $old_rstat = new \XoopsFormHidden('old_rstat', $org->getVar('row_flag')); //need to know old status when record saved
    }//end if org_id==0

    if ($isDefunct) {
        $org_name = new \XoopsFormLabel(_AM_XBSSACC_ORGED2, $orgname);

        $base_crcy = new \XoopsFormLabel(_AM_XBSSACC_ORGED3, $crcy_val);

        $row_flag = new \XoopsFormLabel(_AM_XBSSACC_RSTATNM, CDM_RSTAT_DEF);
    } else {
        $org_name = new \XoopsFormText(_AM_XBSSACC_ORGED2, 'org_nm', 20, 20, $orgname);

        $base_crcy = new Xbscdm\Form\FormSelectCurrency(_AM_XBSSACC_ORGED3, 'base_crcy', $crcy_val);

        $row_flag = new Xbscdm\Form\FormSelectRstat(_AM_XBSSACC_RSTATNM, 'row_flag', $org->getVar('row_flag'), 1, $org->getVar('row_flag'));
    }

    $ret = Xbscdm\Utility::getXoopsUser($org->getVar('row_uid'));

    $row_uid = new \XoopsFormLabel(_AM_XBSSACC_RUIDNM, $ret);

    $row_dt = new \XoopsFormLabel(_AM_XBSSACC_RDTNM, $org->getVar('row_dt'));

    $submit = new \XoopsFormButton('', 'save', _AM_XBSSACC_SUBMIT, 'submit');

    $cancel = new \XoopsFormButton('', 'cancel', _AM_XBSSACC_CANCEL, 'submit');

    $reset = new \XoopsFormButton('', 'reset', _AM_XBSSACC_RESET, 'reset');

    $editForm = new \XoopsThemeForm(_AM_XBSSACC_ORGED0, 'editForm', 'adminorg.php');

    $editForm->addElement($id);

    $editForm->addElement($org);

    if (0 != $org_id) {
        $editForm->addElement($id_hid);
    }

    $editForm->addElement($org_name);

    $editForm->addElement($base_crcy);

    $editForm->addElement($new_flag);

    $editForm->addElement($old_rstat);

    $editForm->addElement($row_flag, true);

    $editForm->addElement($row_uid, false);

    $editForm->addElement($row_dt, false);

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
 */
function submitOrgForm()
{
    global $_POST;

    extract($_POST);

    $orgHandler = Helper::getInstance()->getHandler('Org');

    if ($new_flag) {
        $orgData = $orgHandler->create();

        $orgData->setVar('id', $org_id);
    } else {
        $orgData = &$orgHandler->getAll($org_id);
    }

    $orgData->setVar('org_name', $org_nm);

    $orgData->setVar('base_crcy', $base_crcy);

    if ((CDM_RSTAT_DEF != $old_rstat) and (CDM_RSTAT_DEF == $row_flag)) { //properly defunct the record
        $orgHandler->loadAccounts($orgData);

        $orgData->setDefunct();
    } else {
        $orgData->setVar('row_flag', $row_flag);
    }

    $isNew = $orgData->isNew();

    if (!$orgHandler->insert($orgData)) {
        redirect_header(SACC_URL . '/admin/adminorg.php', 1, $orgHandler->getError());
    } else {
        if ($isNew) {
            $orgHandler->createAccounts($orgData);
        }

        redirect_header(SACC_URL . '/admin/adminorg.php', 1, _AM_XBSSACC_ORGED100);
    }//end if
} //end function submitOrgForm

/**
 * Function: Edit an organisation data record
 *
 * Edit or create a new organisation record
 *
 * @param int  $org_id id of organisation to edit or create a new one if zero
 * @param bool $save   If true then save organisation details else displaya form
 * @version 1
 */
function adminEditOrg($org_id = 0, $save = false)
{
    if ($save) {
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
function adminSelectAcc($org_id = 0)
{
    if (0 == $org_id) { //ask user to select an organisation
        adminSelectOrg(true);
    } else { //display list of accounts for an organisation
        $ac_id = new Form\FormSelectAccount(_AM_XBSSACC_SELACC, 'ac_id', $org_id, null, 10, true);

        $org = new \XoopsFormHidden('org_id', $org_id);

        $submit = new \XoopsFormButton('', 'go', _AM_XBSSACC_GO, 'submit');

        $insert = new \XoopsFormButton(_AM_XBSSACC_INSERT_DESC, 'insert', _AM_XBSSACC_INSERT, 'submit');

        $accForm = new \XoopsThemeForm(_AM_XBSSACC_ACCFORM, 'accountform', 'adminaccount.php');

        $accForm->addElement($org);

        $accForm->addElement($ac_id, true);

        $accForm->addElement($submit);

        $accForm->addElement($insert);

        $accForm->display();
    }
}

/**
 * Function: Display the account edit form
 *
 * @param int $org_id Identifier for an organisation
 * @param int $ac_id  Identifier for an account
 * @version 1
 */
function displayAccForm($org_id, $ac_id)
{
    global $_GET;

    extract($_GET);

    //cannot use smarty templates until xoops V2.2

    //$GLOBALS['xoopsOption']['template_main'] = _AM_XBSSACC_EDITFORM;  // Set the template page to be used

    //Set up static text for form

    //$xoopsTpl->assign('lang_pagetitle',_AM_XBSSACC_PAGETITLE4);

    //$xoopsTpl->assign('lang_copyright',_AM_XBSSACC_COPYRIGHT);

    $accountHandler = Helper::getInstance()->getHandler('Account');

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

            $org = new Form\FormSelectOrg(_AM_XBSSACC_ACED2, 'org_id', $org_id);

            //define default currency

            $crcy = SACC_CFG_DEFCURR;
        } else { // else display the current account id as label because it is primary key
            $id = new \XoopsFormLabel(_AM_XBSSACC_ACED1, $ac_id);

            $id_hid = new \XoopsFormHidden('ac_id', $ac_id); //still need to know id in POST process

            $new_flag = new \XoopsFormHidden('new_flag', false);

            //display organisation as label (cannot change account organisation)

            $orgHandler = Helper::getInstance()->getHandler('Org');

            $orgData = &$orgHandler->getAll($org_id);

            $org = new \XoopsFormHidden('org_id', $org_id);

            $org_label = new \XoopsFormLabel(_AM_XBSSACC_ACED2, $orgData->getVar('org_name'));

            $crcy = $accountData->getVar('ac_curr');
        }//end if ac_id==0

        $ac_tp = new Form\FormSelectAccType(_AM_XBSSACC_ACED3, 'ac_tp', $accountData->getVar('ac_tp'));

        $ac_prnt_id = new Form\FormSelectAccPrnt(_AM_XBSSACC_ACED9, 'ac_prnt_id', $org_id, $accountData->getVar('ac_prnt_id'));

        $ac_curr = new Xbscdm\Form\FormSelectCurrency(_AM_XBSSACC_ACED4, 'ac_curr', $crcy);

        $ac_nm = new \XoopsFormText(_AM_XBSSACC_ACED5, 'ac_nm', 20, 20, $accountData->getVar('ac_nm'));

        $ac_prps = new \XoopsFormTextArea(_AM_XBSSACC_ACED6, 'ac_prps', $accountData->getVar('ac_prps'));

        $ac_note = new \XoopsFormTextArea(_AM_XBSSACC_ACED7, 'ac_note', $accountData->getVar('ac_note'));

        $rf = $accountData->getVar('row_flag');

        $row_flag = new Xbscdm\Form\FormSelectRstat(_MD_XBSSACC_RSTATNM, 'row_flag', $rf, 1, $rf);

        $ret = getXoopsUser($accountData->getVar('row_uid'));

        $row_uid = new \XoopsFormLabel(_MD_XBSSACC_RUIDNM, $ret);

        $row_dt = new \XoopsFormLabel(_MD_XBSSACC_RDTNM, $accountData->getVar('row_dt'));

        $submit = new \XoopsFormButton('', 'save', _AM_XBSSACC_SUBMIT, 'submit');

        $cancel = new \XoopsFormButton('', 'cancel', _AM_XBSSACC_CANCEL, 'submit');

        $reset = new \XoopsFormButton('', 'reset', _AM_XBSSACC_RESET, 'reset');

        $editForm = new \XoopsThemeForm(_AM_XBSSACC_ACED0, 'editForm', 'adminaccount.php');

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

        //$editForm->assign($xoopsTpl);

        $editForm->display();
    }
} //end function displayAccForm

/**
 * Function: Save an account record entry
 *
 * @param int $org_id Identifier for an organisation
 * @param int $ac_id  Identifier for an account
 * @version 1
 */
function submitAccForm($org_id, $ac_id)
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
        redirect_header(SACC_URL . '/admin/adminaccount.php', 10, $accountHandler->getError());
    } else {
        redirect_header(SACC_URL . '/admin/adminaccount.php?curr_org_id=' . $org_id, 1, _AM_XBSSACC_ACED100);
    }//end if
}

/**
 * Function: Edit or save an account
 *
 * Displays account edit form or saves an account's details
 *
 * @param int  $org_id Identifier for an organisation
 * @param int  $ac_id  Identifier for an account
 * @param bool $save   Set true if account details are to be saved
 * @version 1
 */
function adminEditAcc($org_id = 0, $ac_id = 0, $save = false)
{
    if ($save) {
        submitAccForm($org_id, $ac_id);
    } else {
        displayAccForm($org_id, $ac_id);
    }
}
