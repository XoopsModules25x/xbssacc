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
 * Credit Account Base Object
 *
 * Credit accounts show positive net balance for credit entries
 *
 * @package    SACC
 * @subpackage Account
 * @version    1
 */
class CRAccount extends Account
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
