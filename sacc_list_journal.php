<?php declare(strict_types=1);

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author:    Ashley Kitson                                                  //
// Copyright: (c) 2004, Ashley Kitson
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    Simple Accounts System (SACC)                                  //
// ------------------------------------------------------------------------- //

/**
 * List Journal entries
 *
 * Display a list of journal entries
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage User_interface
 * @access     private
 * @version    1
 */

/**
 * MUST include module header
 */
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

    $orgHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Org');

    $org = $orgHandler->get($org_id);

    $orgHandler->loadJournal($org);

    $entries = $org->getJournal();

    foreach ($entries as $entry) {
        $xoopsTpl->append('entries', $entry);
    }

    // Assign page and column titles

    $xoopsTpl->assign('lang_pagetitle', sprintf(_MD_SACC_PAGETITLE6, $org->getVar('org_name')));

    //  $xoopsTpl->assign('lang_instruction',_MD_SACC_PAGEINSTR2);
    $xoopsTpl->assign('lang_col1', _MD_SACC_PAGE6COL1); //jrn id
    $xoopsTpl->assign('lang_col2', _MD_SACC_PAGE6COL2); //date
    $xoopsTpl->assign('lang_col3', _MD_SACC_PAGE6COL3); //purpose
    $xoopsTpl->assign('lang_col4', _MD_SACC_PAGE5COL3); //row flag
    $xoopsTpl->assign('org_id', $org_id);

    /**
     * Display the page
     */

    require XOOPS_ROOT_PATH . '/footer.php';        //display the page!
} else {
    redirect_header(SACC_URL . '/index.php', 1, _MD_SACC_ERR_0);
}
