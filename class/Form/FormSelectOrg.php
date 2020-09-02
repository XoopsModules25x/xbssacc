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
 * Create an Organisation selector
 *
 * Returns only active organisations.  Use for end user display
 *
 * @package    SACC
 * @subpackage Form_Handling
 * @version    1
 */
class FormSelectOrg extends \XoopsFormSelect
{
    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name    "name" attribute
     * @param mixed  $value   Pre-selected value (or array of them).
     * @param int    $size    Number of rows. "1" makes a drop-down-list
     *
     * @internal param string $lang The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
     */
    public function __construct($caption, $name, $value = null, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size);

        $orgHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Org');

        $res = $orgHandler->getSelectList();

        $this->addOptionArray($res);
    }
}
