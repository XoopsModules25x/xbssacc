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
 * Income Account
 *
 * @package    SACC
 * @subpackage Account
 * @version    1
 */
class IncomeAc extends CRAccount
{
    /**
     * Constructor
     *
     * @access private
     */
    public function __construct()
    {
        parent::__construct();

        $this->assignVar('ac_cr_altnm', _MD_XBSSACC_INCOME);

        $this->assignVar('ac_dr_altnm', _MD_XBSSACC_CHARGE);
    }
}
