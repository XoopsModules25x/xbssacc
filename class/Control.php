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
 * Class to hold a control account
 *
 * @package    SACC
 * @subpackage SACCControl
 * @version    1
 */
class Control extends Xbscdm\BaseObject
{
    /**
     * Constructor
     *
     * The following variables are declared for retrieval via ->getVar()
     * {@source 2 3}
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
     * @return int control account ID
     * @version 1
     */
    public function getCtlAc()
    {
        return $this->getVar('ac_id');
    }
}
