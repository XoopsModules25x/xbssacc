<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc\Form;

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
 * Objects and elements to display SACC data on screen
 *
 * Extends the xoopsForm object system
 *
 * @package     SACC
 * @subpackage  Form_Handling
 * @author      Ashley Kitson http://xoobs.net
 * @copyright   copyright (c) 2004 Ashley Kitson, UK
 */
/**
 * Xoops form objects
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
/**
 * CDM Definitions
 */
require_once XOOPS_ROOT_PATH . '/modules/xbscdm/include/defines.php';

/**
 * Create an account selector
 *
 * @package    SACC
 * @subpackage Form_Handling
 * @version    1
 */
class FormSelectAccount extends \XoopsFormSelect
{
    /**
     * Constructor
     *
     * @param string     $caption             Caption
     * @param string     $name                "name" attribute
     * @param            $org
     * @param mixed      $value               Pre-selected value (or array of them).
     * @param int        $size                Number of rows. "1" makes a drop-down-list
     * @param bool       $ignore_prnt_setting Default FALSE.  If true the USEPRN config flag is ignored
     * @internal param string $lang The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
     */
    public function __construct($caption, $name, $org, $value = null, $size = 1, $ignore_prnt_setting = false)
    {
        parent::__construct($caption, $name, $value, $size);

        $accountHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

        $res = $accountHandler->getSelectList($org, $ignore_prnt_setting);

        $this->addOptionArray($res);
    }
}
