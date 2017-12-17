<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org/>                             //
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
// Copyright: (c) 2005, Ashley Kitson
// URL:       http://xoobs.net                                               //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    Simple Accounts (SACC)                                         //
// ------------------------------------------------------------------------- //
/**
 * Balances Block show and edit functions
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage Blocks
 * @version    1
 * @access     private
 */

//avoid declaring the functions repeatedly
//if(defined('SACC_BBALANCES_DEFINED')) return;
/**
 * Flag to tell script it is already parsed.  If set then script is exited
 */
define('SACC_BBALANCES_DEFINED', true);

/**
 * SACC constant definitions
 */
include_once XOOPS_ROOT_PATH . '/modules/xbs_sacc/include/defines.php';
/**
 * SACC form element class
 */
include_once SACC_PATH . '/class/class.sacc.form.php';
/**
 * SACC Functions
 */
include_once SACC_PATH . '/include/functions.php';

/**
 * Function: Create display data for block
 *
 * Retrieve block configuration data and format block output
 *
 * @version 1
 * @param array $options block config options
 *                       [0] = Organisation Id
 * @return array $block output parameters for smarty template
 */

function b_sacc_balances_show($options)
{
    $orgId  = (isset($options[0]) ? $options[0] : 1);
    $orgId  = (int)$orgId;  //need to ensure it is numeric as options[] stores as a string
    $block  = [];
    $decpnt = pow(10, SACC_CFG_DECPNT);

    //Organisation Name
    $org              =& SACCGetOrg($orgId);
    $block['org']     = $org->getVar('org_name');
    $block['orgname'] = _MB_SACC_BALANCE_ORG;
    //Balance Sheet
    $acc1                 =& SACCGetControlAccount($orgId, SACC_CNTL_ASST);
    $acc2                 =& SACCGetControlAccount($orgId, SACC_CNTL_LIAB);
    $block['balance']     = SACCFormatMoney(($acc1->getBalance() - $acc2->getBalance()) / $decpnt);
    $block['balancename'] = _MB_SACC_BALANCE_BALNAME;
    unset($acc1);
    unset($acc2);
    //P&L
    $acc1               = SACCGetControlAccount($orgId, SACC_CNTL_INCO);
    $acc2               = SACCGetControlAccount($orgId, SACC_CNTL_EXPE);
    $block['pandl']     = SACCFormatMoney(($acc1->getBalance() - $acc2->getBalance()) / $decpnt);
    $block['pandlname'] = _MB_SACC_BALANCE_PLNAME;
    unset($acc1);
    unset($acc2);
    //Equity
    $acc1                = SACCGetControlAccount($orgId, SACC_CNTL_EQUI);
    $block['equity']     = SACCFormatMoney($acc1->getBalance() / $decpnt);
    $block['equityname'] = _MB_SACC_BALANCE_EQNAME;
    unset($acc1);

    return $block;
}

/**
 * Function: Create additional data items for block admin edit form
 *
 * Format a mini table for block options to be included in the
 * main block admin edit form.  All data field names must be 'options[]'
 * and declared in the form in the order of the parameter to this function.
 *
 * @version 1
 * @param array $options block config options
 *                       [0] = Organisation Id
 * @return string $form Output html for smarty template
 */

function b_sacc_balances_edit($options)
{

    /*create input fields using XoopsForm objects
    * It is clearer to use XoopsForm object->render() to create the form elements
    * rather than hand coding the html.
    */

    $s = new SACCFormSelectOrg('', 'options[]');
    $s->setValue($options[0]);
    $fld = $s->render();

    //construct the table that will be placed into the admin form
    $form = '<table>';
    $form .= '<tr><td>' . _MB_SACC_BALANCE_ORGNAME . '</td><td>' . $fld . '</td></tr>';
    $form .= '</table>';
    return $form;
}
