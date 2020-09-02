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
 * Accounts Admin page
 *
 * Allow administrator to create or modify Ledger Account data
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Admin
 * @access        private
 * @version       1
 */

/**
 * Do all the declarations etc needed by an admin page
 */
require_once __DIR__ . '/admin_header.php';
//require_once __DIR__ . '/adminheader.php';

//Display the admin menu
//xoops_module_admin_menu(2,_AM_XBSSACC_ADMENU2);

xoops_cp_header();
/**
 * To use this as a template you need to write code to display
 * whatever it is you want displaying between here...
 */
global $_POST;
extract($_POST);
if (!isset($submit)) { //check to see if org_id has been set by a previous record save
    extract($_GET);

    if (isset($curr_org_id)) {
        $submit = true;         //set flag so that decision tree below will
        $org_id = $curr_org_id; // display list of accounts, not organisations
    }
}

if (isset($submit)) { //List the accounts for an organisation
    adminSelectAcc($org_id);
} elseif (isset($go)) { //edit an account
    adminEditAcc($org_id, $ac_id);
} elseif (isset($insert)) { //create a new account
    adminEditAcc($org_id);
} elseif (isset($save)) { //user has edited or created account so save it
    adminEditAcc($org_id, $ac_id, true);
} else { //Ask user to select an organisation prior to displaying a list of accounts to edit
    adminSelectAcc();
} //end if

/**
 * and here.
 */

//And put footer in
xoops_cp_footer();
