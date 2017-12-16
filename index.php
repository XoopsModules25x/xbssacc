<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
// Copyright: (c) 2004, Ashley Kitson										 //
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    Simple Accounts System (SACC)                                  //
// ------------------------------------------------------------------------- //

/**
* Display a list of organisations to choose from 
*
* All further operations will act on this organisation.
* As this is the first page that is shown when user selects main menu item
* it should never be missed out!
*
* @author Ashley Kitson http://xoobs.net
* @copyright 2005 Ashley Kitson, UK
* @package SACC
* @subpackage User_interface
* @access private
* @version 1
*/

/**
* Module header file
*/
require("header.php");
/**
* SACC form class declarations
*/
require_once SACC_PATH."/class/class.sacc.form.php";
/**
* CDM functions
*/
require_once CDM_PATH."/include/functions.php";
$xoopsOption['template_main'] = 'sacc_sel_org.tpl';
/**
* Xoops header file
*/
include XOOPS_ROOT_PATH."/header.php";


// Assign page titles
$xoopsTpl->assign('lang_pagetitle', _MD_SACC_PAGETITLE1);

// Get data and assign to template
$org_id = new SACCFormSelectOrg(_MD_SACC_SELORG,'org_id',intval(SACC_CFG_DEFORG),4);
$submit = new XoopsFormButton("","submit",_MD_SACC_GO,"submit");
$orgForm = new XoopsThemeForm(_MD_SACC_PAGETITLE1,"orgform","sacc_accounts_list.php");
$orgForm->addElement($org_id,true);
$orgForm->addElement($submit);
$orgForm->assign($xoopsTpl);

/**
* Display the page
*/
include XOOPS_ROOT_PATH.'/footer.php';		
?>