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
 * Class to hold a journal entry
 *
 * <b>This is probably the most important object in SACC!</b>  It is the primary
 * method for making entries in the accounts system.  The following example
 * illustrates its use:
 * <code>
 * require_once SACC_PATH."/include/functions.php";
 * $org_id = 1; //set to your organisation id
 * $purpose = "Testing";
 * $journal = initializeJournal($org_id, null, $purpose) //create a new journal
 * // a series of calls to appendEntry sets up the account entries
 * // void appendEntry( int $ac_id, string $ref, int $dr, int $cr)
 * $journal->appendEntry(2,"Ref description",11750,0); //DR bank account
 * $journal->appendEntry(6,"Ref description",0,10000); //CR Sales account
 * $journal->appendEntry(12,"Ref description",0,1750); //CR VAT In account
 * if (!saveJournal($journal)) { //oops an error
 *   print (strval(getErrNo()." - ".getErrMsg());
 * }
 * </code>
 * @package    SACC
 * @subpackage Journal
 * @version    1
 */
class Journal extends Xbscdm\BaseObject
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

        $entry = new AcEntry();

        $entry->setVar('ac_id', $ac_id);

        $entry->setVar('jrn_id', $this->getVar('id'));

        $entry->setVar('txn_ref', $ref);

        $entry->setVar('txn_dr', $dr);

        $entry->setVar('txn_cr', $cr);

        $accounts[] = $entry;

        $this->setVar('acc_entry', $accounts);
    }
}
