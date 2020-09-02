<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @license      https://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2000-2020 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

use XoopsModules\Xbssacc\{Common,
    Helper
};

/** @var Helper $helper */
/** @var Utility $utility */

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    /* PRIVATE FUNCTIONS
     */
    /**
     * Function: Save an error message
     *
     * @access  private
     * @param int    $errno  error number
     * @param string $errstr error message
     * @version 1
     */
    public static function saveError($errno, $errstr)
    {
        /**
         * @global array User session
         */

        global $_SESSION;

        $_SESSION['SACCErrNo'] = $errno;

        $_SESSION['SACCErrStr'] = $errstr;
    }

    /* PUBLIC FUNCTIONS
     */

    /**
     * Function: Get last SACC error number
     *
     * @return int
     * @global array User session
     */
    public static function getErrNo()
    {
        global $_SESSION;

        return $_SESSION['SACCErrNo'];
    }//end function getErrNo

    /**
     * Function: Get last SACC error message
     *
     * @return   string
     * @global array User session
     */
    public static function getErrMsg()
    {
        global $_SESSION;

        return $_SESSION['SACCErrStr'];
    }//end function getErrMsg

    /**
     * Function: Return formatted string for display of money amount
     *
     * Uses number_format() as money_format doesn't work on MSWin based servers.
     *
     * @param numeric $amount
     * @return string
     */
    public static function formatMoney($amount)
    {
        return number_format($amount, (int)SACC_CFG_DECPNT, _MD_MONEY_DECPNT, _MD_MONEY_THOUSEP);
    } //end function formatMoney

    /**
     * Function: Create an organisation
     *
     * If $currency is null or not given then default SACC currency will be used
     *
     * @param string $orgname  Name of the organisation
     * @param null   $currency Currency code (see CDM currency codes)
     * @return mixed Org Object else FALSE if unable to create organisation
     */
    public static function createOrg($orgname, $currency = null)
    {
        //clear error messages

        self::saveError(0, '');

        if (empty($currency)) {
            $crcy_val = SACC_CFG_DEFCURR; //get system default currency
        } else {
            $crcy_val = $currency;
        }//end if

        $orgHandler = Helper::getInstance()->getHandler('Org');

        if ($orgData = $orgHandler->create()) {
            $orgData->setVar('org_name', $orgname);

            $orgData->setVar('base_crcy', $crcy_val);

            $orgData->setVar('row_flag', SACC_RSTAT_ACT);

            if ($orgHandler->insert($orgData)) {
                if ($orgHandler->createAccounts($orgData)) {
                    return $orgData;
                }//end if
            }//end if
        }//end if
        self::saveError(-2, $orgHandler->getError());

        return false;
    }//end function createOrg

    /**
     * Function: Get the organisation associated with $org_id org identifier
     *
     * @param int $org_id Organisation ID
     * @return mixed Org object else FALSE on error
     */
    public static function getOrg($org_id)
    {
        //clear error messages

        self::saveError(0, '');

        $org_id = (int)$org_id;

        $orgHandler = Helper::getInstance()->getHandler('Org');

        if ($org = $orgHandler->get($org_id)) {
            return $org;
        }

        self::saveError(-3, $orgHandler->getError());

        return false;
        //end if
    }//end function getOrg

    /**
     * Function: Create an account for an organisation
     *
     * @param int    $org_id     Id of organisation
     * @param string $ac_nm      Name of account
     * @param string $ac_tp      Type of account one of SACC_ACTP_INCOME, SACC_ACTP_EXPENSE, SACC_ACTP_ASSET, SACC_ACTP_LIABILITY, SACC_ACTP_BANK, SACC_ACTP_SUPPLIER, SACC_ACTP_CUSTOMER, SACC_ACTP_EQUITY
     * @param int    $ac_prnt_id Account id of the parent to this account
     * @param string $ac_prps    Purpose of account
     * @param string $ac_note    Note on account
     * @param null   $currency   Default Null. If null or not given then default SACC currency.  If value is given then it should be from the set of CDM CURRENCY codes.
     * @return mixed Account object else false if unable to create account
     */
    public static function createAccount($org_id, $ac_nm, $ac_tp, $ac_prnt_id, $ac_prps, $ac_note, $currency = null)
    {
        //clear error messages

        self::saveError(0, '');

        $accountHandler = Helper::getInstance()->getHandler('Account');

        $accountData = $accountHandler->create();

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
            self::saveError(-4, $accountHandler->getError());

            return false;
        }

        return $accountData;
        //end if
    }//end function createAccount

    /**
     * Function: Return account object associated with the account id
     *
     * @param int $ac_id Account ID
     * @return mixed Account Object else FALSE if error
     */
    public static function getAccount($ac_id)
    {
        //clear error messages

        self::saveError(0, '');

        $accountHandler = Helper::getInstance()->getHandler('Account');

        if ($accountData = $accountHandler->get($ac_id)) {
            return $accountData;
        }

        self::saveError(-5, $accountHandler->getError());

        return false;
        //end if
    }//end function getAccount

    /**
     * Function: Returns the account associated with the specified control account
     *
     * @param int    $org_id    Organisation identifier id
     * @param string $ctrl_name Control account name.  One of SACC_CNTL_.. constants defined in defines.php
     * @return mixed Account object else False
     */
    public static function getControlAccount($org_id, $ctrl_name)
    {
        //clear error messages

        self::saveError(0, '');

        $org_id = (int)$org_id;

        $ctrlHandler = Helper::getInstance()->getHandler('Control');

        $ctrl = $ctrlHandler->get($org_id, $ctrl_name);

        if ($ctrl) {
            return getAccount($ctrl->getVar('ac_id'));
        }

        self::saveError(-6, $ctrlHandler->getError());

        return false;
        //end if
    }//end function getControlAccount

    /**
     * Function: Initialise a Journal object
     *
     * Initialises (but does not create on the database), a journal object.
     * You will want to call $journalObject->appendEntry() to add account
     * entries to the journal before calling saveJournal to save the
     * journal to the database.
     *
     * @param int    $org_id  Organisation identifier
     * @param string $dt      Date of journal entry in YYYY-MM-DD format
     * @param null   $purpose Purpose of journal.  Can be ommitted
     * @return mixed Journal object on success else False
     */
    public static function initializeJournal($org_id, $dt, $purpose = null)
    {
        //clear error messages

        self::saveError(0, '');

        $jHandler = Helper::getInstance()->getHandler('Journal');

        if ($jData = $jHandler->create($dt, $purpose, $org_id)) {
            return $jData;
        }

        self::saveError(-7, $jHandler->getError());

        return false;
        //end if
    }//end function initializeJournal

    /**
     * Function: Save a journal
     *
     * Safe save of a journal.  If you use $journalObject->insert(), no balance checking is done.
     * Use this function to check that the journal balances instead
     *
     * @param object $journal Journal object
     * @return bool TRUE if Journal balances and is saved else FALSE
     */
    public static function saveJournal($journal)
    {
        //clear error messages

        self::saveError(0, '');

        //check that the journal balances

        $dr = 0;

        $cr = 0;

        $entries = $journal->getVar('acc_entry');

        foreach ($entries as $entry) {
            $dr += (int)$entry->getVar('txn_dr');

            $cr += (int)$entry->getVar('txn_cr');
        }//end foreach

        //if balanced then save journal else return false

        if ($dr == $cr) {
            $jHandler = Helper::getInstance()->getHandler('Journal');

            if ($jHandler->insert($journal)) {
                return true;
            }

            self::saveError(-8, _MD_XBSSACC_ERR_10);
            //end if
        } else {
            self::saveError(-8, _MD_XBSSACC_ERR_11);
        }//end if

        return false;
    }//end function saveJournal
}
