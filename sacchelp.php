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
 * SACC Help file
 *
 * Wrapper for the HTML help file for SACC
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Help
 * @access        private
 * @version       1
 */

/**
 * MUST include module header
 */
require __DIR__ . '/header.php';
/**
 * Xoops header file
 */
require XOOPS_ROOT_PATH . '/header.php';
/**
 * The HTML help file
 */
include SACC_PATH . '/sacchelp.html';
/**
 * Display the page
 */
require XOOPS_ROOT_PATH . '/footer.php';      //display the page!
