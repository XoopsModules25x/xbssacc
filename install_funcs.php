<?php declare(strict_types=1);

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
 * Installation callback functions
 *
 * Functions called during the module installation, update or delete process
 *
 * @param mixed $module
 * @param mixed $oldVersion
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Installation
 * @access        private
 * @version       1
 */

/**
 * Function: Module Update callback
 *
 * Called during update process to alter data table structure or values in tables
 *
 * @param xoopsModule &$module     handle to the module object being updated
 * @param int          $oldVersion version * 100 prior to update
 * @return bool True if successful else False
 * @version 1
 */
function xoops_module_update_sacc(&$module, $oldVersion)
{
    global $xoopsDB;

    /*
    if ($oldVersion < 110) { //upgrading from version 1.00
      //add a new column to cdm_code table
      $sql = "ALTER TABLE ".$xoopsDB->prefix(cdm_code)." ADD cd_param VARCHAR(255) AFTER cd_desc";
      $ret = ($result = $xoopsDB->queryF($sql));
      if (!$ret) {
        $module->setErrors('Unable to execute query "'.$sql.'" whilst updating CDM module');
        return false;
      }
    }

    if ($oldVersion < 120) { //upgrading from version 1.10
      //perform other upgrading stuff
    }
    */

    return true;
}//end function

/**
 * Function: Module Install callback
 *
 * The basic SQL install is done via the SQL script but we also need to check that CDM is installed.
 * This really needs to be called before the main module install but
 * as at Xoops 2.0.10 this is not possible.  This will not be called
 * until Xoops allows a pre process install script.  IF CDM is not
 * present the main install process will fall over because cdm tables
 * are not available.
 *
 * @param xoopsModule &$module Handle to module object being installed
 * @return bool True if successful else False
 * @version 1
 */
function xoops_module_install_sacc(&$module)
{
    //The basic SQL install is done via the SQL script

    // but we also need to check that CDM is installed

    // This really needs to be called before the main module install but

    // as at Xoops 2.0.10 this is not possible.  This will not be called

    // until Xoops allows a pre process install script.  IF CDM is not

    // present the main install process will fall over because cdm tables

    // are not available.

    global $xoopsDB;

    $sql = 'SELECT 1 FROM ' . $xoopsDB->prefix('modules') . " WHERE direname = 'xbscdm' AND isactive = 1";

    return $result = $xoopsDB->queryF($sql);
}//end function

/**
 * Function: Module deletion callback
 *
 * SACC tables are deleted via the Xoops uninstaller
 * but we need to remove SACC entries in the CDM tables
 *
 * @param xoopsModule &$module Handle to module object being installed
 * @return bool True if successful else False
 * @version 1
 */
function xoops_module_uninstall_sacc($module)
{
    global $xoopsDB;

    $sql1 = 'DELETE FROM ' . $xoopsDB->prefix(cdm_code) . " WHERE cd_set LIKE 'SACC%'";

    $sql2 = 'DELETE FROM ' . $xoopsDB->prefix(cdm_meta) . " WHERE cd_set LIKE 'SACC%'";

    $ret1 = ($result = $xoopsDB->queryF($sql1));

    $ret2 = ($result = $xoopsDB->queryF($sql2));

    if (!($ret1 && $ret2)) {
        $module->setErrors('Unable to remove SACC data from CDM tables whilst uninstalling SACC module');

        return false;
    }

    return true;
}//end function
