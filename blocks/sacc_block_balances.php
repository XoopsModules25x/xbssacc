<?php declare(strict_types=1);

use XoopsModules\Xbssacc\Form;

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
 * Balances Block show and edit functions
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Blocks
 * @version       1
 * @access        private
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
require_once XOOPS_ROOT_PATH . '/modules/xbssacc/include/defines.php';

/**
 * SACC Functions
 */
//require_once SACC_PATH . '/include/functions.php';

/**
 * Function: Create display data for block
 *
 * Retrieve block configuration data and format block output
 *
 * @param array $options block config options
 *                       [0] = Organisation Id
 * @return array output parameters for smarty template
 * @version 1
 */
function b_sacc_balances_show($options)
{
    $orgId = ($options[0] ?? 1);

    $orgId = (int)$orgId;  //need to ensure it is numeric as options[] stores as a string

    $block = [];

    $decpnt = pow(10, SACC_CFG_DECPNT);

    //Organisation Name

    $org = getOrg($orgId);

    $block['org'] = $org->getVar('org_name');

    $block['orgname'] = _MB_XBSSACC_BALANCE_ORG;

    //Balance Sheet

    $acc1 = getControlAccount($orgId, SACC_CNTL_ASST);

    $acc2 = getControlAccount($orgId, SACC_CNTL_LIAB);

    $block['balance'] = formatMoney(($acc1->getBalance() - $acc2->getBalance()) / $decpnt);

    $block['balancename'] = _MB_XBSSACC_BALANCE_BALNAME;

    unset($acc1);

    unset($acc2);

    //P&L

    $acc1 = getControlAccount($orgId, SACC_CNTL_INCO);

    $acc2 = getControlAccount($orgId, SACC_CNTL_EXPE);

    $block['pandl'] = formatMoney(($acc1->getBalance() - $acc2->getBalance()) / $decpnt);

    $block['pandlname'] = _MB_XBSSACC_BALANCE_PLNAME;

    unset($acc1);

    unset($acc2);

    //Equity

    $acc1 = getControlAccount($orgId, SACC_CNTL_EQUI);

    $block['equity'] = formatMoney($acc1->getBalance() / $decpnt);

    $block['equityname'] = _MB_XBSSACC_BALANCE_EQNAME;

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
 * @param array $options block config options
 *                       [0] = Organisation Id
 * @return string Output html for smarty template
 * @version 1
 */
function b_sacc_balances_edit($options)
{
    /*create input fields using XoopsForm objects
    * It is clearer to use XoopsForm object->render() to create the form elements
    * rather than hand coding the html.
    */

    $s = new Form\FormSelectOrg('', 'options[]');

    $s->setValue($options[0]);

    $fld = $s->render();

    //construct the table that will be placed into the admin form

    $form = '<table>';

    $form .= '<tr><td>' . _MB_XBSSACC_BALANCE_ORGNAME . '</td><td>' . $fld . '</td></tr>';

    $form .= '</table>';

    return $form;
}
