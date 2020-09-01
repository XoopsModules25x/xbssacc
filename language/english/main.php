<?php declare(strict_types=1);

//%%%%%%        Module Name 'SACC'      %%%%%
/**
 * Module language constant definitions
 *
 * This is the language specific file for UK English language
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    SACC
 * @subpackage Definitions
 * @version    1
 */

/**#@+
 * Account DR/CR names
 */
define('_MD_XBSSACC_DR', 'DR'); //shortname debit account
define('_MD_XBSSACC_CR', 'CR'); //shortname credit account
define('_MD_XBSSACC_BALANCE', 'Balance'); //account balance
define('_MD_XBSSACC_EXPENSE', 'Expense'); //dr side of expense account
define('_MD_XBSSACC_REFUND', 'Refund'); //cr side of expense account
define('_MD_XBSSACC_INCOME', 'Income'); //cr side of income account
define('_MD_XBSSACC_CHARGE', 'Charge'); //dr side of income account
define('_MD_XBSSACC_INCREASE', 'Increase'); //one side of asset (dr)/liability (cr)account
define('_MD_XBSSACC_DECREASE', 'Decrease'); //other side of asset (cr)/liability (dr)account
/**#@-*/

/**#@+
 * Money string seperators
 */
define('_MD_MONEY_DECPNT', '.'); //Money decimal point string
define('_MD_MONEY_THOUSEP', ','); //Money thousands seperator.
/*#@-*/
/**
 * Copyright notice - do not remove
 */
define('_MD_XBSSACC_COPYRIGHT', "\"Click to see software author's web page\"");

/**#@+
 * Page and form titles etc
 */
define('_MD_XBSSACC_PAGETITLE1', 'SACC - Organisation');
define('_MD_XBSSACC_PAGETITLE2', 'SACC - List of Accounts for %s');
define('_MD_XBSSACC_PAGETITLE5', 'SACC - List of entries for %s - %s account');
define('_MD_XBSSACC_PAGETITLE6', 'SACC - List of journal entries for %s');
define('_MD_XBSSACC_SELORG', 'Select an organisation');
/**#@-*/

/**#@+
 *button names
 */
define('_MD_XBSSACC_INSERT', 'Insert');
define('_MD_XBSSACC_BROWSE', 'Browse');
define('_MD_XBSSACC_SUBMIT', 'Submit');
define('_MD_XBSSACC_CANCEL', 'Cancel');
define('_MD_XBSSACC_RESET', 'Reset');
define('_MD_XBSSACC_EDIT', 'Edit');
define('_MD_XBSSACC_GO', 'Go');
define('_MD_XBSSACC_INSERT_DESC', 'Create a new record');
/**#@-*/

/**#@+
 * Form labels
 */
define('_MD_XBSSACC_PAGEINSTR2', 'Click browse button to display account entries.');
define('_MD_XBSSACC_PAGE2COL1', 'A/C Id');
define('_MD_XBSSACC_PAGE2COL2', 'Account Name');
define('_MD_XBSSACC_PAGE2COL3', 'Currency');
define('_MD_XBSSACC_PAGE2COL4', 'Purpose');
define('_MD_XBSSACC_PAGE2COL5', 'Status');
define('_MD_XBSSACC_PAGE5COL1', 'Jrn Id');
define('_MD_XBSSACC_PAGE5COL2', 'Transaction Ref');
define('_MD_XBSSACC_PAGE5COL3', 'Row Flag');
define('_MD_XBSSACC_PAGE6COL1', 'Jrn Id');
define('_MD_XBSSACC_PAGE6COL2', 'Date');
define('_MD_XBSSACC_PAGE6COL3', 'Purpose');
define('_MD_XBSSACC_JRNED1', 'Journal edit cancelled');
define('_MD_XBSSACC_JRNED2', 'Journal update successful');
define('_MD_XBSSACC_NOPARENT', 'No Parent');
/**#@-*/

/**#@+
 * Base account names created when a new organisation is created
 */
define('_MD_XBSSACC_NAC_INCOME', 'Income Master A/C');
define('_MD_XBSSACC_NAC_EXPENSE', 'Expense Master A/C');
define('_MD_XBSSACC_NAC_LIABILITY', 'Liability Master A/C');
define('_MD_XBSSACC_NAC_ASSET', 'Asset Master A/C');
define('_MD_XBSSACC_NAC_EQUITY', 'Equity Master A/C');
define('_MD_XBSSACC_NAC_BANK', 'Current Bank Account');
define('_MD_XBSSACC_NAC_OPEN', 'Opening Balances');
/**#@-*/

/**#@+
 * Row status field names
 */
define('_MD_XBSSACC_RSTATNM', 'Row Status');
define('_MD_XBSSACC_RUIDNM', 'Last Editor');
define('_MD_XBSSACC_RDTNM', 'Last Edit Date');
/**#@-*/

/**#@+
 * Error string constants
 */
define('_MD_XBSSACC_ERR_0', 'You must select an organisation to work with');
define('_MD_XBSSACC_ERR_1', 'No data for SACCObject indexed by %s');
define('_MD_XBSSACC_ERR_2', 'Unable to instantiate SACCObject %s');
define('_MD_XBSSACC_ERR_3', 'Unable to reload. Given class is %s. Expected %s');
define('_MD_XBSSACC_ERR_4', 'Unable to reload SACCObject with null key');
define('_MD_XBSSACC_ERR_5', 'You must be logged in to edit records');
define('_MD_XBSSACC_ERR_6', 'No account type data for Account indexed by %s');
define('_MD_XBSSACC_ERR_7', 'No data Account object indexed by %s');
define('_MD_XBSSACC_ERR_8', 'Invalid id (%s) for SACCAccountHandler->getAll()');
define('_MD_XBSSACC_ERR_9', 'Cannot Defunct %s as it has a non zero balance');
define('_MD_XBSSACC_ERR_10', 'Unable to save Journal - database error');
define('_MD_XBSSACC_ERR_11', 'Unable to save Journal - journal does not balance');
/**#@-*/
