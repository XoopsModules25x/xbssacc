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
// Copyright: (c) 2004, Ashley Kitson                                        //
// URL:       http://xoobs.net                                      //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    Simple Accounts System (SAC)                                   //
// ------------------------------------------------------------------------- //

/**
 * Objects and elements to display SACC data on screen
 * 
 * Extends the xoopsForm object system
 *
 * @package     SACC
 * @subpackage  Form_Handling
 * @author	Ashley Kitson http://xoobs.net
 * @copyright	copyright (c) 2004 Ashley Kitson, UK
 */
/**
* Xoops form objects
*/
require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
/**
 * CDM Definitions
 */
require_once(XOOPS_ROOT_PATH."/modules/xbs_cdm/include/defines.php");
/**
* CDM form objects.  SACC extends these
*/
require_once CDM_PATH."/class/class.cdm.form.php";

/**
* Create an account type selector 
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/
class SACCFormSelectAccType extends CDMFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
*/
  function SACCFormSelectAccType($caption, $name, $value=null, $size=1, $lang=CDM_DEF_LANG) {
    $this->CDMFormSelect('SACCACTP', $caption, $name, $value, $size, $lang);
  }
}

/**
* Create a control account selector 
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/class SACCFormSelectControlAc extends CDMFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
*/
  function SACCFormSelectControlAc($caption, $name, $value=null, $size=1, $lang=CDM_DEF_LANG) {
    $this->CDMFormSelect('SACCCNTL', $caption, $name, $value, $size, $lang);
  }
}

/**
* Create an Organisation selector 
*
* Returns only active organisations.  Use for end user display
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/class SACCFormSelectOrg extends XoopsFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
*/
  function SACCFormSelectOrg($caption, $name, $value=null, $size=1) {
    $this->XoopsFormSelect($caption, $name, $value, $size);
    $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
    $res = $orgHandler->getSelectList();
    $this->addOptionArray($res);
  }
}

/**
* Create an Organisation selector 
*
* Returns all organisations.  Use for admin user display
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/class SACCFormSelectOrgAll extends XoopsFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
*/
  function SACCFormSelectOrgAll($caption, $name, $value=null, $size=1) {
    $this->XoopsFormSelect($caption, $name, $value, $size);
    $orgHandler =& xoops_getmodulehandler("SACCOrg",SACC_DIR);
    $res = $orgHandler->getSelectListAll();
    $this->addOptionArray($res);
  }
}

/**
* Create an account selector 
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/
class SACCFormSelectAccount extends XoopsFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
* @param 	boolean	$ignore_prnt_setting 	Default FALSE.  If true the USEPRN config flag is ignored
*/
  function SACCFormSelectAccount($caption, $name, $org, $value=null, $size=1, $ignore_prnt_setting = FALSE) {
    $this->XoopsFormSelect($caption, $name, $value, $size);
    $accountHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
    $res = $accountHandler->getSelectList($org,$ignore_prnt_setting);
    $this->addOptionArray($res);
  }
}

/**
* Create a Parent Account selector (includes No Parent)
*
* @package SACC
* @subpackage Form_Handling
* @version 1
*/
class SACCFormSelectAccPrnt extends XoopsFormSelect {
/**
* Constructor
*
* @param	string	$caption	Caption
* @param	string	$name       "name" attribute
* @param	mixed	$value	    Pre-selected value (or array of them).
* @param	int		$size	    Number of rows. "1" makes a drop-down-list
* @param   string  $lang      The language set for the returned codes, defaults to CDM_DEF_LANG (normally EN)
*/
  function SACCFormSelectAccPrnt($caption, $name, $org, $value=null, $size=1) {
    $this->XoopsFormSelect($caption, $name, $value, $size);
    $accountHandler =& xoops_getmodulehandler("SACCAccount",SACC_DIR);
    $res = $accountHandler->getSelectList($org,TRUE);
    $res[0] = _MD_SACC_NOPARENT;
    $this->addOptionArray($res);
  }
}

?>