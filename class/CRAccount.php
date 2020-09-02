<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc;

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
 * Base classes used by Simple Accounts system
 *
 * @package       SACC
 * @subpackage    SACCBase
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
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
