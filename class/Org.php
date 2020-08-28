<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc;

use XoopsModules\Xbscdm;

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

/**
 * Org object - Organisation
 *
 * @package    SACC
 * @subpackage Org
 * @version    1
 */
class Org extends Xbscdm\BaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 6}
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
     * @param string $arrname Name of array variable to retrieve
     * @return array cleaned up array of key=>value pairs
     * @version 1
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
     * @return array cleaned up array for each account held for the organisation
     * @version 1
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
     * @return array cleaned up array for each journal entry for the organisation
     * @version 1
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
     * @return bool TRUE on success else FALSE
     * @version 1
     */
    public function setDefunct()
    {
        $acc = $this->getAccounts();

        $accHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

        foreach ($acc as $account) {
            if ($thisAcc = $accHandler->getAll($account['id'])) {
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
    }
    //end function
} //end class Org
