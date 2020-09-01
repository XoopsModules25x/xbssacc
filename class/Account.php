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
 * Account object
 *
 * The Base account object from which all account types are descended
 *
 * @package    SACC
 * @subpackage Account
 * @version    1
 */
class Account extends Xbscdm\BaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 20}
     */
    public function __construct()
    {
        //constructor
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
        $this->initVar('ac_level', XOBJ_DTYPE_INT, 0, false); //display level
        $this->initVar('has_kids', XOBJ_DTYPE_INT, 0, false); //this account has child accounts
        $this->initVar('disp_order', XOBJ_DTYPE_INT, 0, false); //display order

        /* following are not held on database but computed by the object */

        $this->initVar('ac_net_bal', XOBJ_DTYPE_INT, 0, false); //account balance
        $this->initVar('ac_cr_altnm', XOBJ_DTYPE_OTHER, _MD_XBSSACC_CR, false); //alt name for CR value
        $this->initVar('ac_dr_altnm', XOBJ_DTYPE_OTHER, _MD_XBSSACC_DR, false); //alt name for DR value
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
     * @return int current balance as an integer
     * @version 1
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
     * @return array cleaned up array of entries for this account
     * @version 1
     */
    public function getEntries()
    {
        $arr = [];

        $entries = $this->getVar('entries');

        foreach ($entries as $entry) {
            $entry->cleanVars();

            $arr[] = $entry->cleanVars;
        }

        return $arr;
    }
    //end function getEntries
}//end class Account
