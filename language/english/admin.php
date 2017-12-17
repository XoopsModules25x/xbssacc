<?php

//%%%%%%        Module Name 'SACC'      %%%%%
/**
 * Module administration language constant definitions
 *
 * This is the language specific file for UK English language
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage Definitions
 * @access     private
 * @version    1
 */

/**#@+
 * Constants for Admin menu - non language specific
 */

/**
 * Admin menu parameters
 *
 * These MUST follow the format _AM_<ModDir>_URL_DOCS etc
 * so that the xoops_module_admin_header functions can work.  The suffix after <modDir> is not optional!
 * Leave them commented out if you do not have the functionality for your module
 *
 * Relative url from module directory for documentation
 */
define('_AM_XBS_SACC_URL_DOCS', 'admin/help.php');
/**
 * Absolute url for module support site
 */
define('_AM_XBS_SACC_URL_SUPPORT', 'http://www.xoobs.net/modules/newbb/viewforum.php?forum=1');
/**
 * absolute url for module donations site
 *
 * //define("_AM_XBS_SACC_URL_DONATIONS","");
 */

/**
 * Module configuration option - MUST follow the format _AM_<ModDir>_MODCONFIG
 *
 * Value MUST be "xoops", "module" or "none"
 */
define('_AM_XBS_SACC_MODCONFIG', 'xoops');
/**
 * If module configuration option = "module" then define the name of the script
 * to call for module configuration.  This is relative to modDir/admin/
 *
 * MUST follow the format _AM_<ModDir>_MODCONFIGURL
 */
//define("_AM_XBS_SACC_MODCONFIGURL","saccConfig.php");
/**
 * SACC config is done via CDM so the config page redirects there
 */
//define("_AM_XBS_SACC_MODCONFIGREDIRECT","Configuration is done via the CDM system. You will shortly be redirected there.");

/**#@-*/

/**#@+
 * Constants for Admin menu - language specific
 */

//Admin menu breadcrumb titles
define('_AM_SACC_ADMENU1', 'Organisations');
define('_AM_SACC_ADMENU2', 'Account Setup');
define('_AM_SACC_ADMENU3', 'Configuration');

//Organisations - choose an organisation
define('_AM_SACC_SELORG', 'Choose an organisation to work with');
define('_AM_SACC_ORGFORM', 'Organisation');

//Organisations - edit an organisation
define('_AM_SACC_ORGED0', 'SACC - Edit an Organisation');
define('_AM_SACC_ORGED1', 'Organisation Id');
define('_AM_SACC_ORGED2', 'Organisation Name');
define('_AM_SACC_ORGED3', 'Currency');
define('_AM_SACC_ORGED100', 'Organisation details changed');
define('_AM_SACC_ORGED101', 'Organisation edit cancelled');

//Accounts - choose an account
define('_AM_SACC_SELACC', 'Choose an account to work with');
define('_AM_SACC_ACCFORM', 'Account');

//Accounts - edit an account
define('_AM_SACC_ACED0', 'SACC - Edit an Account');
define('_AM_SACC_ACED1', 'Account Id');
define('_AM_SACC_ACED2', 'Organisation');
define('_AM_SACC_ACED3', 'Account Type');
define('_AM_SACC_ACED4', 'Currency');
define('_AM_SACC_ACED5', 'Account Name');
define('_AM_SACC_ACED6', 'Account Purpose');
define('_AM_SACC_ACED7', 'Account Note');
define('_AM_SACC_ACED8', 'Account edit cancelled');
define('_AM_SACC_ACED9', 'Parent Account');
define('_AM_SACC_ACED100', 'Account details changed');
define('_AM_SACC_ACED101', 'Account edit cancelled');

//buttons
define('_AM_SACC_INSERT', 'Insert');
define('_AM_SACC_BROWSE', 'Browse');
define('_AM_SACC_SUBMIT', 'Submit');
define('_AM_SACC_CANCEL', 'Cancel');
define('_AM_SACC_RESET', 'Reset');
define('_AM_SACC_EDIT', 'Edit');
define('_AM_SACC_GO', 'Go');

//button labels
define('_AM_SACC_INSERT_DESC', 'Create a new record');

//Common row status descriptions
define('_AM_SACC_RSTATNM', 'Row Status');
define('_AM_SACC_RUIDNM', 'Last edited by');
define('_AM_SACC_RDTNM', 'Last edit datetime');

/**#@-*/
