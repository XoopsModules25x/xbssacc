<?php declare(strict_types=1);

// $Id: modinfo.php,v 1.14 2004/09/11 10:37:03 onokazu Exp $
// Module Info
/**
 * Module installation language definitions
 *
 * English UK language definitions for module installation
 * Read the source file for definitions
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage Definitions
 * @access     private
 * @version    1
 */

/**
 * The name of this module
 */
define('_MI_SACC_NAME', 'Simple Accounts');

/**
 * A brief description of this module
 */
define('_MI_SACC_DESC', 'Provides simple double entry book-keeping system.  Intended as a support module for larger business applications.  System has simple input and output screens.  Requires CDM (Code Data Management) to be installed first.');

/**#@+
 *  Sub menu titles
 */
define('_MI_SACC_SMNAME1', 'List of Accounts');
define('_MI_SACC_SMNAME2', 'Balance Sheet');
define('_MI_SACC_SMNAME3', 'P&L Statement');
define('_MI_SACC_SMNAME3a', 'List of Journals');
define('_MI_SACC_SMNAME4', 'Enter Journal');
define('_MI_SACC_SMNAME5', 'Organisations');
define('_MI_SACC_SMNAME6', 'Help with SACC');
/**#@-*/

/**#@+
 * Admin menu titles
 */
define('_MI_SACC_ADMENU1', 'Organisations');
define('_MI_SACC_ADMENU2', 'Account Setup');
/**#@-*/

/**#@+
 * Configuration item names and descriptions
 */
define('_MI_SACC_DEFCURRNAME', 'Default Currency');
define('_MI_SACC_DEFCURRNAMEDESC', 'Select the default account currency');
define('_MI_SACC_DEFORGNAME', 'Default Organisation');
define('_MI_SACC_DEFORGNAMEDESC', 'The identifier for the default organisation to display');
define('_MI_SACC_USEPRNTNAME', 'Use Parent for entry');
define('_MI_SACC_USEPRNTNAMEDESC', 'Defines if we can use Parent Accounts to enter journal entries from the input screen.  If 0 (zero) then only accounts with no child accounts can be selected.  If 1, then any account can be selected.');
define('_MI_SACC_DECPNTNAME', 'Currency Decimal Points');
define('_MI_SACC_DECPNTNAMEDESC', 'The number of decimal points to display currency.  All monetary values are stored as integers on the database so we need to know 10^DECPNT to display and convert display values. For the majority of currencies this will be = 2.');
/**#@-*/

/**#@+
 * Block names and descriptions
 */
define('_MI_SACC_BLOCK_BALANCENAME', 'Trial Balance');
define('_MI_SACC_BLOCK_BALANCEDESC', 'Displays summary trial balance for an organisation');
/**#@-*/
