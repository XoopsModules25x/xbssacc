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

/**
 * List Journal entries
 *
 * Display a list of journal entries
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
 * MUST include module header
 */

use XoopsModules\Xbssacc\Helper;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'sacc_list_jrn.tpl';
/**
 * Xoops header file
 */
require XOOPS_ROOT_PATH . '/header.php';

if (!empty($_POST['org_id'])) {
    //Save the organisation id for use by other screens

    $org_id = $_POST['org_id'];

    $_SESSION['sacc_org_id'] = $org_id;
} else {
    //see if org_id is in session variable

    $org_id = (empty($_SESSION['sacc_org_id']) ? null : $_SESSION['sacc_org_id']);
}

if (!empty($org_id)) {
    //set up organisation

    $orgHandler = Helper::getInstance()->getHandler('Org');

    $org = $orgHandler->get($org_id);

    $orgHandler->loadJournal($org);

    $entries = $org->getJournal();

    foreach ($entries as $entry) {
        $xoopsTpl->append('entries', $entry);
    }

    // Assign page and column titles

    $xoopsTpl->assign('lang_pagetitle', sprintf(_MD_XBSSACC_PAGETITLE6, $org->getVar('org_name')));

    //  $xoopsTpl->assign('lang_instruction',_MD_XBSSACC_PAGEINSTR2);
    $xoopsTpl->assign('lang_col1', _MD_XBSSACC_PAGE6COL1); //jrn id
    $xoopsTpl->assign('lang_col2', _MD_XBSSACC_PAGE6COL2); //date
    $xoopsTpl->assign('lang_col3', _MD_XBSSACC_PAGE6COL3); //purpose
    $xoopsTpl->assign('lang_col4', _MD_XBSSACC_PAGE5COL3); //row flag
    $xoopsTpl->assign('org_id', $org_id);

    /**
     * Display the page
     */

    require XOOPS_ROOT_PATH . '/footer.php';        //display the page!
} else {
    redirect_header(SACC_URL . '/index.php', 1, _MD_XBSSACC_ERR_0);
}
