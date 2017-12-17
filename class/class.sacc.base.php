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
// Module:    Simple Accounts (SACC)                                     //
// ------------------------------------------------------------------------- //
/**
 * Base classes used by Simple Accounts system
 *
 * @package       SACC
 * @subpackage    SACCBase
 * @author        Ashley Kitson http://xoobs.net
 * @copyright (c) 2005 Ashley Kitson, Great Britain
 */

/**
 * Base accounts handling objects are derived from Code Data Management base objects
 */
require_once XOOPS_ROOT_PATH . '/modules/xbs_cdm/class/class.cdm.base.php';

/**
 * SACCOrg object - Organisation
 *
 * @package    SACC
 * @subpackage SACCOrg
 * @version    1
 */
class SACCOrg extends CDMBaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 6}
     *
     */
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, 0, true);                 //organisation id
        $this->initVar('base_crcy', XOBJ_DTYPE_TXTBOX, null, true, 3);  //base currency code
        $this->initVar('org_name', XOBJ_DTYPE_TXTBOX, null, true, 30);  //org name
        $tmp = [];                                             //dummy array to initialise variable
        $this->initVar('accounts', XOBJ_DTYPE_OTHER, $tmp);           //accounts array for organisation
        $this->initVar('journal', XOBJ_DTYPE_OTHER, $tmp);            //journal array for organisation
        parent::__construct();
    }

    /**
     * Return array data suitable for display on screen
     *
     * @access  private
     * @version 1
     * @param string $arrname Name of array variable to retrieve
     * @return array cleaned up array of key=>value pairs
     */
    public function getClean($arrname)
    {
        $arr = [];
        $raw = $this->getVar($arrname);
        foreach ($raw as $i) {
            $i->cleanVars();
            $arr[] = $i->cleanVars;
        }
        return $arr;
    }

    /**
     * Return array of account data suitable for display on screen
     *
     * Returned array looks like:
     * Array ( [0] => Array ( [id] => 1 [ac_prnt_id] => 0 [org_id] => 1 [ac_tp] => ASSET [ac_nm] => Asset Master A/C [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 0 [has_kids] => 1 [disp_order] => 0 [ac_net_bal] => 0 [ac_cr_altnm] => Decrease [ac_dr_altnm] => Increase [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [1] => Array ( [id] => 6 [ac_prnt_id] => 1 [org_id] => 1 [ac_tp] => BANK [ac_nm] => Current Bank Account [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 1 [has_kids] => 0 [disp_order] => 1 [ac_net_bal] => 0 [ac_cr_altnm] => Decrease [ac_dr_altnm] => Increase [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [2] => Array ( [id] => 2 [ac_prnt_id] => 0 [org_id] => 1 [ac_tp] => LIABIL [ac_nm] => Liability Master A/C [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 0 [has_kids] => 0 [disp_order] => 2 [ac_net_bal] => 0 [ac_cr_altnm] => Increase [ac_dr_altnm] => Decrease [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [3] => Array ( [id] => 3 [ac_prnt_id] => 0 [org_id] => 1 [ac_tp] => INCOME [ac_nm] => Income Master A/C [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 0 [has_kids] => 0 [disp_order] => 3 [ac_net_bal] => 0 [ac_cr_altnm] => Income [ac_dr_altnm] => Charge [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [4] => Array ( [id] => 4 [ac_prnt_id] => 0 [org_id] => 1 [ac_tp] => EXPENS [ac_nm] => Expense Master A/C [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 0 [has_kids] => 0 [disp_order] => 4 [ac_net_bal] => 0 [ac_cr_altnm] => Refund [ac_dr_altnm] => Expense [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [5] => Array ( [id] => 5 [ac_prnt_id] => 0 [org_id] => 1 [ac_tp] => EQUITY [ac_nm] => Equity Master A/C [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 0 [has_kids] => 1 [disp_order] => 5 [ac_net_bal] => 0 [ac_cr_altnm] => Increase [ac_dr_altnm] => Decrease [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     *  [6] => Array ( [id] => 7 [ac_prnt_id] => 5 [org_id] => 1 [ac_tp] => EQUITY [ac_nm] => Opening Balances [ac_prps] => [ac_note] => [ac_curr] => GBP [ac_dr] => 0 [ac_cr] => 0 [ac_level] => 1 [has_kids] => 0 [disp_order] => 6 [ac_net_bal] => 0 [ac_cr_altnm] => Increase [ac_dr_altnm] => Decrease [entries] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508144536 )
     * )
     *
     * @version 1
     * @return array cleaned up array for each account held for the organisation
     */
    public function getAccounts()
    {
        return $this->getClean('accounts');
    }

    /**
     * Return array of journal data suitable for display on screen
     *
     * Returned array looks like this:
     * Array ( [0] => Array ( [id] => 1 [org_id] => 1 [jrn_dt] => 2005-05-08 00:00:00 [jrn_prps] => Test [acc_entry] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508145557 )
     * [1] => Array ( [id] => 2 [org_id] => 1 [jrn_dt] => 2005-05-08 00:00:00 [jrn_prps] => Test 2 [acc_entry] => Array ( ) [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508145657 )
     * )
     *
     * @version 1
     * @return array cleaned up array for each journal entry for the organisation
     */
    public function getJournal()
    {
        return $this->getClean('journal');
    }

    /**
     * Function: setDefunct
     *
     * Extend ancestor.  Defunct any accounts that the organisation has.  Although the account data is
     * saved to the database, the organisation data is not until inesrt() is called
     *
     * @version 1
     * @return boolean TRUE on success else FALSE
     */
    public function setDefunct()
    {
        $acc        = $this->getAccounts();
        $accHandler = xoops_getModuleHandler('SACCAccount', SACC_DIR);
        foreach ($acc as $account) {
            if ($thisAcc = $accHandler->getall($account['id'])) {
                if ($thisAcc->setDefunct()) {
                    if ($accHandler->insert($thisAcc)) {
                        unset($thisAcc);
                    } else {
                        redirect_header(null, 1, $accHandler->getError());
                    }
                }
            }
        }//end foreach
        return parent::setDefunct();    //defunct the organisation
    }//end function
} //end class SACCOrg

/**
 * SACCAccount object
 *
 * The Base account object from which all account types are descended
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCAccount extends CDMBaseObject
{

    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 20}
     *
     */
    public function __construct()
    { //constructor
        $this->initVar('id', XOBJ_DTYPE_INT, 0, true); //account id
        $this->initVar('ac_prnt_id', XOBJ_DTYPE_INT, 0, true); //account parent id
        $this->initVar('org_id', XOBJ_DTYPE_INT, 0, true); //organisation to which account belongs
        $this->initVar('ac_tp', XOBJ_DTYPE_TXTBOX, null, true, 6); //account type
        $this->initVar('ac_nm', XOBJ_DTYPE_TXTBOX, null, true, 20); //account name
        $this->initVar('ac_prps', XOBJ_DTYPE_TXTAREA, null, false, 255); //account purpose
        $this->initVar('ac_note', XOBJ_DTYPE_TXTAREA, null, false, 255); //account notes
        $this->initVar('ac_curr', XOBJ_DTYPE_TXTBOX, null, true, 3); //currency of account
        $this->initVar('ac_dr', XOBJ_DTYPE_INT, 0, false); //debit balance
        $this->initVar('ac_cr', XOBJ_DTYPE_INT, 0, false); //credit balance
        $this->initVar('ac_level', XOBJ_DTYPE_INT, 0, false);//display level
        $this->initVar('has_kids', XOBJ_DTYPE_INT, 0, false);//this account has child accounts
        $this->initVar('disp_order', XOBJ_DTYPE_INT, 0, false);//display order

        /* following are not held on database but computed by the object */
        $this->initVar('ac_net_bal', XOBJ_DTYPE_INT, 0, false); //account balance
        $this->initVar('ac_cr_altnm', XOBJ_DTYPE_OTHER, _MD_SACC_CR, false); //alt name for CR value
        $this->initVar('ac_dr_altnm', XOBJ_DTYPE_OTHER, _MD_SACC_DR, false); //alt name for DR value
        $entry = [];
        $this->initVar('entries', XOBJ_DTYPE_OTHER, $entry, false); //account entries

        parent::__construct();  //call ancestor constructor last for row flags
    }

    /**
     * Get the account balance
     *
     * Returns the current account balance.  Account balance is updated
     * when an entry is posted into an account
     *
     * IMPORTANT - Read the SACC help file for additional information on monetary values
     *
     * @version 1
     * @return int current balance as an integer
     */
    public function getBalance()
    {
        return $this->getVar('ac_net_bal');
    }

    /**
     * Set current balance
     *
     * Abstract function.  Must be overidden in ancestor to set ac_net_bal
     *
     * @version 1
     * @access  private
     */
    public function setBalance()
    {
    }

    /**
     * Return array of entry data suitable for display on screen
     *
     * Array ( [0] => Array ( [id] => 1 [ac_id] => 6 [jrn_id] => 2 [txn_ref] => Money in [txn_dr] => 10000 [txn_cr] => 0 [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508145657 )
     * [1] => Array ( [id] => 4 [ac_id] => 6 [jrn_id] => 3 [txn_ref] => Money out :-( [txn_dr] => 0 [txn_cr] => 2000 [row_flag] => Active [row_uid] => 1 [row_dt] => 20050508150843 )
     * )
     *
     * @version 1
     * @return array cleaned up array of entries for this account
     */
    public function getEntries()
    {
        $arr     = [];
        $entries = $this->getVar('entries');
        foreach ($entries as $entry) {
            $entry->cleanVars();
            $arr[] = $entry->cleanVars;
        }
        return $arr;
    }//end function getEntries
}//end class SACCAccount

/**
 * Debit Account Base Object
 *
 * Debit accounts show positive net balance for debit entries
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCDRAccount extends SACCAccount
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set current balance
     *
     * There is not normally any need to call this function if you are using the
     * API for account objects to insert() the data, usually by creating a
     * journal and saving it.
     *
     * @version 1
     * @access  private
     */
    public function setBalance()
    {
        $net = $this->getVar('ac_dr') - $this->getVar('ac_cr');
        $this->assignVar('ac_net_bal', $net);
    }
}

/**
 * Credit Account Base Object
 *
 * Credit accounts show positive net balance for credit entries
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCCRAccount extends SACCAccount
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set current balance
     *
     * There is not normally any need to call this function if you are using the
     * API for account objects to insert() the data, usually by creating a
     * journal and saving it.
     *
     * @version 1
     * @access  private
     */
    public function setBalance()
    {
        $net = $this->getVar('ac_cr') - $this->getVar('ac_dr');
        $this->assignVar('ac_net_bal', $net);
    }
}

/**
 * Expense Account
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCExpenseAc extends SACCDRAccount
{
    /**
     * Constructor
     *
     * @access private
     */
    public function __construct()
    {
        parent::__construct();
        $this->assignVar('ac_dr_altnm', _MD_SACC_EXPENSE);
        $this->assignVar('ac_cr_altnm', _MD_SACC_REFUND);
    }
}

/**
 * Asset Account
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCAssetAc extends SACCDRAccount
{
    /**
     * Constructor
     *
     * @access private
     */
    public function __construct()
    {
        parent::__construct();
        $this->assignVar('ac_dr_altnm', _MD_SACC_INCREASE);
        $this->assignVar('ac_cr_altnm', _MD_SACC_DECREASE);
    }
}

/**
 * Customer Account
 *
 * Customer accounts have associated address details
 * Not defined yet
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCCustomerAc extends SACCAssetAc
{
}

/**
 * Bank Account
 *
 * Bank accounts have associated bank sort code and account numbers
 * Not defined yet
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCBankAc extends SACCCustomerAc
{
}

/**
 * Income Account
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCIncomeAc extends SACCCRAccount
{
    /**
     * Constructor
     *
     * @access private
     */
    public function __construct()
    {
        parent::__construct();
        $this->assignVar('ac_cr_altnm', _MD_SACC_INCOME);
        $this->assignVar('ac_dr_altnm', _MD_SACC_CHARGE);
    }
}

/**
 * Liability Account
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCLiabilityAc extends SACCCRAccount
{
    /**
     * Constructor
     *
     * @access private
     */
    public function __construct()
    {
        parent::__construct();
        $this->assignVar('ac_cr_altnm', _MD_SACC_INCREASE);
        $this->assignVar('ac_dr_altnm', _MD_SACC_DECREASE);
    }
}

/**
 * Supplier Account
 *
 * Supplier accounts have associated address details
 * Not defined yet
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCSupplierAc extends SACCLiabilityAc
{
}

/**
 * Equity/Capital account
 *
 * Really just another name for a Liability account
 *
 * @package    SACC
 * @subpackage SACCAccount
 * @version    1
 */
class SACCEquityAc extends SACCLiabilityAc
{
}

/**
 * Class to hold an account entry
 *
 * @package    SACC
 * @subpackage SACCEntry
 * @version    1
 */
class SACCAcEntry extends CDMBaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 6}
     *
     */
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, 0, true); //transaction id
        $this->initVar('ac_id', XOBJ_DTYPE_INT, 0, true); //account id
        $this->initVar('jrn_id', XOBJ_DTYPE_INT, 0, true); //journal id
        $this->initVar('txn_ref', XOBJ_DTYPE_TXTBOX, null, true, 30); //reference for this entry
        $this->initVar('txn_dr', XOBJ_DTYPE_INT, 0, false); //transaction debit amount
        $this->initVar('txn_cr', XOBJ_DTYPE_INT, 0, false); //transaction credit amount
        parent::__construct();
    }
}

/**
 * Class to hold a journal entry
 *
 * <b>This is probably the most important object in SACC!</b>  It is the primary
 * method for making entries in the accounts system.  The following example
 * illustrates its use:
 * <code>
 * require_once SACC_PATH."/include/functions.php";
 * $org_id = 1; //set to your organisation id
 * $purpose = "Testing";
 * $journal = SACCInitJournal($org_id, null, $purpose) //create a new journal
 * // a series of calls to appendEntry sets up the account entries
 * // void appendEntry( int $ac_id, string $ref, int $dr, int $cr)
 * $journal->appendEntry(2,"Ref description",11750,0); //DR bank account
 * $journal->appendEntry(6,"Ref description",0,10000); //CR Sales account
 * $journal->appendEntry(12,"Ref description",0,1750); //CR VAT In account
 * if (!SACCSaveJournal($journal)) { //oops an error
 *   print (strval(SACCGetErrNo()." - ".SACCGetErrMsg());
 * }
 * </code>
 * @package    SACC
 * @subpackage SACCJournal
 * @version    1
 */
class SACCJournal extends CDMBaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 6}
     */
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, 0, true); //journal id
        $this->initVar('org_id', XOBJ_DTYPE_INT, 0, true); //organisation id
        $this->initVar('jrn_dt', XOBJ_DTYPE_TXTBOX, 0, false); //journal date
        $this->initVar('jrn_prps', XOBJ_DTYPE_TXTBOX, 0, false); //journal purpose
        $a = [];
        $this->initVar('acc_entry', XOBJ_DTYPE_OTHER, $a, false); //account entries
        parent::__construct();
    }

    /**
     * Add an accounting entry to the journal
     *
     * There should never be a case when both the debit and credit amounts
     * are non zero.  See SACC Help documentation for further details on
     * the handling of monetary values.
     *
     * @param int    $ac_id Account ID
     * @param string $ref   Entry reference
     * @param int    $dr    Debit amount
     * @param int    $cr    Credit amount
     */
    public function appendEntry($ac_id, $ref, $dr, $cr)
    {
        $accounts = $this->getVar('acc_entry');
        $entry    = new SACCAcEntry();
        $entry->setVar('ac_id', $ac_id);
        $entry->setVar('jrn_id', $this->getVar('id'));
        $entry->setVar('txn_ref', $ref);
        $entry->setVar('txn_dr', $dr);
        $entry->setVar('txn_cr', $cr);
        $accounts[] = $entry;
        $this->setVar('acc_entry', $accounts);
    }
}

/**
 * Class to hold a control account
 *
 * @package    SACC
 * @subpackage SACCControl
 * @version    1
 */
class SACCControl extends CDMBaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 3}
     *
     */
    public function __construct()
    {
        $this->initVar('org_id', XOBJ_DTYPE_INT, 0, true); //organisation id
        $this->initVar('ctrl_cd', XOBJ_DTYPE_TXTBOX, 0, true); //control code
        $this->initVar('ac_id', XOBJ_DTYPE_INT, 0, true); //id of control account
        parent::__construct();
    }

    /**
     * Return the control account id
     *
     * @version 1
     * @return int control account ID
     */
    public function getCtlAc()
    {
        return $this->getVar('ac_id');
    }
}
