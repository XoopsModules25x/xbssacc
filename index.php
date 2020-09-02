<?php declare(strict_types=1);

use XoopsModules\Xbssacc\Form;
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
 * Display a list of organisations to choose from
 *
 * All further operations will act on this organisation.
 * As this is the first page that is shown when user selects main menu item
 * it should never be missed out!
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    User_interface
 * @access        private
 * @version       1
 */

/**
 * Module header file
 */
require __DIR__ . '/header.php';

/**
 * CDM functions
 */
//require_once CDM_PATH . '/include/functions.php';
$GLOBALS['xoopsOption']['template_main'] = 'sacc_sel_org.tpl';
/**
 * Xoops header file
 */
require XOOPS_ROOT_PATH . '/header.php';

// Assign page titles
$xoopsTpl->assign('lang_pagetitle', _MD_XBSSACC_PAGETITLE1);

// Get data and assign to template
$org_id  = new Form\FormSelectOrg(_MD_XBSSACC_SELORG, 'org_id', (int)SACC_CFG_DEFORG, 4);
$submit  = new \XoopsFormButton('', 'submit', _MD_XBSSACC_GO, 'submit');
$orgForm = new \XoopsThemeForm(_MD_XBSSACC_PAGETITLE1, 'orgform', 'sacc_accounts_list.php');
$orgForm->addElement($org_id, true);
$orgForm->addElement($submit);
$orgForm->assign($xoopsTpl);

/**
 * Display the page
 */
require XOOPS_ROOT_PATH . '/footer.php';
