<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org/>                             //
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
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    Simple Accounts System (SACC)                                  //
// Sub Module:API Functions                                                  //
// ------------------------------------------------------------------------- //

/**
 * API functions
 *
 * App. Programming Interface functions
 * To use these function make sure you:
 * <code>
 * require_once SACC_PATH."/include/functions.php";
 * </code>
 *
 * After calling most functions, if you receive a FALSE return value you can get
 * the error number and string using SACCGetErrNo() and SACCGetErrMsg()
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage API_Functions
 * @version    1
 */

/**
 * CDM API functions
 */
require_once CDM_PATH . '/include/functions.php';

/* PRIVATE FUNCTIONS
 */
/**
 * Function: Save an error message
 *
 * @access  private
 * @version 1
 * @param int    $errno  error number
 * @param string $errstr error message
 */
function _SACCSaveError($errno, $errstr)
{
    /**
     * @global array User session
     */
    global $_SESSION;
    $_SESSION['SACCErrNo']  = $errno;
    $_SESSION['SACCErrStr'] = $errstr;
}

/* PUBLIC FUNCTIONS
 */

/**
 * Function: Get last SACC error number
 *
 * @global array User session
 * @return int
 */
function SACCGetErrNo()
{
    global $_SESSION;
    return $_SESSION['SACCErrNo'];
}//end function SACCGetErrNo

/**
 * Function: Get last SACC error message
 *
 * @return   string
 * @global array User session
 */
function SACCGetErrMsg()
{
    global $_SESSION;
    return $_SESSION['SACCErrStr'];
}//end function SACCGetErrMsg

/**
 * Function: Return formatted string for display of money amount
 *
 * Uses number_format() as money_format doesn't work on MSWin based servers.
 *
 * @param numeric $amount
 * @return string
 *
 */
function SACCFormatMoney($amount)
{
    return number_format($amount, (int)SACC_CFG_DECPNT, _MD_MONEY_DECPNT, _MD_MONEY_THOUSEP);
} //end function SACCFormatMoney

/**
 * Function: Create an organisation
 *
 * If $currency is null or not given then default SACC currency will be used
 *
 * @param string $orgname  Name of the organisation
 * @param string $currency Currency code (see CDM currency codes)
 * @return mixed SACCOrg Object else FALSE if unable to create organisation
 */
function SACCCreateOrg($orgname, $currency = null)
{
    //clear error messages
    _SACCSaveError(0, '');
    if (empty($currency)) {
        $crcy_val = SACC_CFG_DEFCURR; //get system default currency
    } else {
        $crcy_val = $currency;
    }//end if
    $orgHandler = xoops_getModuleHandler('SACCOrg', SACC_DIR);
    if ($orgData =& $orgHandler->create()) {
        $orgData->setVar('org_name', $orgname);
        $orgData->setVar('base_crcy', $crcy_val);
        $orgData->setVar('row_flag', SACC_RSTAT_ACT);
        if ($orgHandler->insert($orgData)) {
            if ($orgHandler->createAccounts($orgData)) {
                return $orgData;
            }//end if
        }//end if
    }//end if
    _SACCSaveError(-2, $orgHandler->getError());
    return false;
}//end function SACCCreateOrg

/**
 * Function: Get the organisation associated with $org_id org identifier
 *
 * @param int $org_id Organisation ID
 * @return mixed SACCOrg object else FALSE on error
 */
function SACCGetOrg($org_id)
{
    //clear error messages
    _SACCSaveError(0, '');
    $org_id     = (int)$org_id;
    $orgHandler = xoops_getModuleHandler('SACCOrg', SACC_DIR);
    if ($org =& $orgHandler->get($org_id)) {
        return $org;
    } else {
        _SACCSaveError(-3, $orgHandler->getError());
        return false;
    }//end if
}//end function SACCGetOrg

/**
 * Function: Create an account for an organisation
 *
 * @param int    $org_id     Id of organisation
 * @param string $ac_nm      Name of account
 * @param string $ac_tp      Type of account one of SACC_ACTP_INCOME, SACC_ACTP_EXPENSE, SACC_ACTP_ASSET, SACC_ACTP_LIABILITY, SACC_ACTP_BANK, SACC_ACTP_SUPPLIER, SACC_ACTP_CUSTOMER, SACC_ACTP_EQUITY
 * @param int    $ac_prnt_id Account id of the parent to this account
 * @param string $ac_prps    Purpose of account
 * @param string $ac_note    Note on account
 * @param string $currency   Default Null. If null or not given then default SACC currency.  If value is given then it should be from the set of CDM CURRENCY codes.
 * @return mixed SACCAccount object else false if unable to create account
 */
function SACCCreateAccount($org_id, $ac_nm, $ac_tp, $ac_prnt_id, $ac_prps, $ac_note, $currency = null)
{
    //clear error messages
    _SACCSaveError(0, '');
    $accountHandler = xoops_getModuleHandler('SACCAccount', SACC_DIR);
    $accountData    =& $accountHandler->create();
    if (empty($currency)) {
        $crcy_val = SACC_CFG_DEFCURR; //get system default currency
    } else {
        $crcy_val = $currency;
    }//end if
    $accountData->setVar('org_id', $org_id);
    $accountData->setVar('ac_nm', $ac_nm);
    $accountData->setVar('ac_tp', $ac_tp);
    $accountData->setVar('ac_prnt_id', $ac_prnt_id);
    $accountData->setVar('ac_curr', $crcy_val);
    $accountData->setVar('ac_note', $ac_note);
    $accountData->setVar('ac_prps', $ac_prps);
    $accountData->setVar('row_flag', SACC_RSTAT_ACT);
    if (!$accountHandler->insert($accountData)) {
        _SACCSaveError(-4, $accountHandler->getError());
        return false;
    } else {
        return $accountData;
    }//end if
}//end function SACCCreateAccount

/**
 * Function: Return account object associated with the account id
 *
 * @param int $ac_id Account ID
 * @return mixed SACCAccount Object else FALSE if error
 */
function SACCGetAccount($ac_id)
{
    //clear error messages
    _SACCSaveError(0, '');
    $accountHandler = xoops_getModuleHandler('SACCAccount', SACC_DIR);
    if ($accountData =& $accountHandler->get($ac_id)) {
        return $accountData;
    } else {
        _SACCSaveError(-5, $accountHandler->getError());
        return false;
    }//end if
}//end function SACCGetAccount

/**
 * Function: Returns the account associated with the specified control account
 *
 * @return mixed SACCAccount object else False
 * @param int    $org_id    Organisation identifier id
 * @param string $ctrl_name Control account name.  One of SACC_CNTL_.. constants defined in defines.php
 */
function SACCGetControlAccount($org_id, $ctrl_name)
{
    //clear error messages
    _SACCSaveError(0, '');
    $org_id      = (int)$org_id;
    $ctrlHandler = xoops_getModuleHandler('SACCControl', SACC_DIR);
    $ctrl        =& $ctrlHandler->get($org_id, $ctrl_name);
    if ($ctrl) {
        return SACCGetAccount($ctrl->getVar('ac_id'));
    } else {
        _SACCSaveError(-6, $ctrlHandler->getError());
        return false;
    }//end if
}//end function SACCGetControlAccount

/**
 * Function: Initialise a Journal object
 *
 * Initialises (but does not create on the database), a journal object.
 * You will want to call $journalObject->appendEntry() to add account
 * entries to the journal before calling SACCSaveJournal to save the
 * journal to the database.
 *
 * @return mixed SACCJournal object on success else False
 * @param int    $org_id  Organisation identifier
 * @param string $dt      Date of journal entry in YYYY-MM-DD format
 * @param string $purpose Purpose of journal.  Can be ommitted
 */
function SACCInitJournal($org_id, $dt, $purpose = null)
{
    //clear error messages
    _SACCSaveError(0, '');
    $jHandler = xoops_getModuleHandler('SACCJournal', SACC_DIR);
    if ($jData =& $jHandler->create($dt, $purpose, $org_id)) {
        return $jData;
    } else {
        _SACCSaveError(-7, $jHandler->getError());
        return false;
    }//end if
}//end function SACCInitJournal

/**
 * Function: Save a journal
 *
 * Safe save of a journal.  If you use $journalObject->insert(), no balance checking is done.
 * Use this function to check that the journal balances instead
 *
 * @return boolean TRUE if Journal balances and is saved else FALSE
 * @param object $journal SACCJournal object
 */

function SACCSaveJournal(&$journal)
{
    //clear error messages
    _SACCSaveError(0, '');
    //check that the journal balances
    $dr      = 0;
    $cr      = 0;
    $entries = $journal->getVar('acc_entry');
    foreach ($entries as $entry) {
        $dr += (int)$entry->getVar('txn_dr');
        $cr += (int)$entry->getVar('txn_cr');
    }//end foreach
    //if balanced then save journal else return false
    if ($dr == $cr) {
        $jHandler = xoops_getModuleHandler('SACCJournal', SACC_DIR);
        if ($jHandler->insert($journal)) {
            return true;
        } else {
            _SACCSaveError(-8, _MD_SACC_ERR_10);
        }//end if
    } else {
        _SACCSaveError(-8, _MD_SACC_ERR_11);
    }//end if
    return false;
}//end function SACCSaveJournal
