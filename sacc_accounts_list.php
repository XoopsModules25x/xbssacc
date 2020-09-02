<?php declare(strict_types=1);

use XoopsModules\Xbscdm;
use XoopsModules\Xbssacc\Helper;

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

// Sub Module:List account details                                           //
// ------------------------------------------------------------------------- //

/**
 * Display list of accounts
 *
 * Display the list of accounts for the organisation and allow editing of teh account details
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
 * Must include module header
 */
require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'sacc_list_accounts.tpl';
/**
 * Xoops header
 */
require XOOPS_ROOT_PATH . '/header.php';
/**
 * CDM API functions
 */
//require_once CDM_PATH . '/include/functions.php';
/**
 * SACC API functions
 */
//require_once SACC_PATH . '/include/functions.php';

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

    //and get the set of accounts that this organisation has

    $orgHandler->loadAccounts($org);

    $accounts = $org->getAccounts(); //this is an array of account data arrays

    foreach ($accounts as $acs) {
        $decpnt = pow(10, SACC_CFG_DECPNT); //get the divisor to display money values

        $acs['ac_dr'] = formatMoney($acs['ac_dr'] / $decpnt);

        $acs['ac_cr'] = formatMoney($acs['ac_cr'] / $decpnt);

        $acs['ac_net_bal'] = formatMoney($acs['ac_net_bal'] / $decpnt);

        //indent the account name according to its hierarchical level

        $disp_level = $acs['ac_level'];

        $slen = mb_strlen($acs['ac_nm']) + ($disp_level * 12);

        $acs['ac_nm'] = str_pad($acs['ac_nm'], $slen, '&nbsp;', STR_PAD_LEFT);

        $xoopsTpl->append('accounts', $acs);
    }

    // Assign page and column titles

    $xoopsTpl->assign('lang_pagetitle', sprintf(_MD_XBSSACC_PAGETITLE2, $org->getVar('org_name')));

    $xoopsTpl->assign('lang_instruction', _MD_XBSSACC_PAGEINSTR2);

    $xoopsTpl->assign('lang_col1', _MD_XBSSACC_PAGE2COL1);

    $xoopsTpl->assign('lang_col2', _MD_XBSSACC_PAGE2COL2);

    $xoopsTpl->assign('lang_col3', _MD_XBSSACC_PAGE2COL3);

    $xoopsTpl->assign('lang_col4', _MD_XBSSACC_PAGE2COL4);

    $xoopsTpl->assign('lang_col5', _MD_XBSSACC_DR);

    $xoopsTpl->assign('lang_col6', _MD_XBSSACC_CR);

    $xoopsTpl->assign('lang_col6b', _MD_XBSSACC_BALANCE);

    $xoopsTpl->assign('lang_col7', _MD_XBSSACC_PAGE2COL5);

    $xoopsTpl->assign('lang_insert', _MD_XBSSACC_INSERT);

    $xoopsTpl->assign('lang_select', _MD_XBSSACC_BROWSE);

    $xoopsTpl->assign('lang_edit', _MD_XBSSACC_EDIT);

    $xoopsTpl->assign('org_id', $org_id);

    /**
     * Display the page
     */

    require XOOPS_ROOT_PATH . '/footer.php';        //display the page!
} else {
    redirect_header(SACC_URL . '/index.php', 1, _MD_XBSSACC_ERR_0);
}
