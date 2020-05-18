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
// Copyright: (c) 2005, Ashley Kitson
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    Simple Accounts System (SACC)                                  //
// ------------------------------------------------------------------------- //
/**
 * Accounts Admin page
 *
 * Allow administrator to create or modify Ledger Account data
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage Admin
 * @access     private
 * @version    1
 */

/**
 * Do all the declarations etc needed by an admin page
 */
require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/adminheader.php';

//Display the admin menu
//xoops_module_admin_menu(2,_AM_SACC_ADMENU2);

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
