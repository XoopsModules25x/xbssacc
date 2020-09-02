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
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 */

// ------------------------------------------------------------------------- //
// Display selection of organisations to choose from.                        //
// All further operations will act on this organisation.                     //
// As this is the first pahge that is shown when user selects main menu item //
// it should never be missed out!

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'sacc_pl.tpl';
require XOOPS_ROOT_PATH . '/header.php';

// Assign page titles
$xoopsTpl->assign('lang_pagetitle', _MD_XBSSACC_PAGETITLE1);

// Get data and assign to template

// Display the page
require XOOPS_ROOT_PATH . '/footer.php';      //display the page!
