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
define('_MD_SACC_DR', 'DR'); //shortname debit account
define('_MD_SACC_CR', 'CR'); //shortname credit account
define('_MD_SACC_BALANCE', 'Balance'); //account balance
define('_MD_SACC_EXPENSE', 'Expense'); //dr side of expense account
define('_MD_SACC_REFUND', 'Refund'); //cr side of expense account
define('_MD_SACC_INCOME', 'Income'); //cr side of income account
define('_MD_SACC_CHARGE', 'Charge'); //dr side of income account
define('_MD_SACC_INCREASE', 'Increase'); //one side of asset (dr)/liability (cr)account
define('_MD_SACC_DECREASE', 'Decrease'); //other side of asset (cr)/liability (dr)account
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
define('_MD_SACC_COPYRIGHT', "\"Click to see software author's web page\"");

/**#@+
 * Page and form titles etc
 */
define('_MD_SACC_PAGETITLE1', 'SACC - Organisation');
define('_MD_SACC_PAGETITLE2', 'SACC - List of Accounts for %s');
define('_MD_SACC_PAGETITLE5', 'SACC - List of entries for %s - %s account');
define('_MD_SACC_PAGETITLE6', 'SACC - List of journal entries for %s');
define('_MD_SACC_SELORG', 'Select an organisation');
/**#@-*/

/**#@+
 *button names
 */
define('_MD_SACC_INSERT', 'Insert');
define('_MD_SACC_BROWSE', 'Browse');
define('_MD_SACC_SUBMIT', 'Submit');
define('_MD_SACC_CANCEL', 'Cancel');
define('_MD_SACC_RESET', 'Reset');
define('_MD_SACC_EDIT', 'Edit');
define('_MD_SACC_GO', 'Go');
define('_MD_SACC_INSERT_DESC', 'Create a new record');
/**#@-*/

/**#@+
 * Form labels
 */
define('_MD_SACC_PAGEINSTR2', 'Click browse button to display account entries.');
define('_MD_SACC_PAGE2COL1', 'A/C Id');
define('_MD_SACC_PAGE2COL2', 'Account Name');
define('_MD_SACC_PAGE2COL3', 'Currency');
define('_MD_SACC_PAGE2COL4', 'Purpose');
define('_MD_SACC_PAGE2COL5', 'Status');
define('_MD_SACC_PAGE5COL1', 'Jrn Id');
define('_MD_SACC_PAGE5COL2', 'Transaction Ref');
define('_MD_SACC_PAGE5COL3', 'Row Flag');
define('_MD_SACC_PAGE6COL1', 'Jrn Id');
define('_MD_SACC_PAGE6COL2', 'Date');
define('_MD_SACC_PAGE6COL3', 'Purpose');
define('_MD_SACC_JRNED1', 'Journal edit cancelled');
define('_MD_SACC_JRNED2', 'Journal update successful');
define('_MD_SACC_NOPARENT', 'No Parent');
/**#@-*/

/**#@+
 * Base account names created when a new organisation is created
 */
define('_MD_SACC_NAC_INCOME', 'Income Master A/C');
define('_MD_SACC_NAC_EXPENSE', 'Expense Master A/C');
define('_MD_SACC_NAC_LIABILITY', 'Liability Master A/C');
define('_MD_SACC_NAC_ASSET', 'Asset Master A/C');
define('_MD_SACC_NAC_EQUITY', 'Equity Master A/C');
define('_MD_SACC_NAC_BANK', 'Current Bank Account');
define('_MD_SACC_NAC_OPEN', 'Opening Balances');
/**#@-*/

/**#@+
 * Row status field names
 */
define('_MD_SACC_RSTATNM', 'Row Status');
define('_MD_SACC_RUIDNM', 'Last Editor');
define('_MD_SACC_RDTNM', 'Last Edit Date');
/**#@-*/

/**#@+
 * Error string constants
 */
define('_MD_SACC_ERR_0', 'You must select an organisation to work with');
define('_MD_SACC_ERR_1', 'No data for SACCObject indexed by %s');
define('_MD_SACC_ERR_2', 'Unable to instantiate SACCObject %s');
define('_MD_SACC_ERR_3', 'Unable to reload. Given class is %s. Expected %s');
define('_MD_SACC_ERR_4', 'Unable to reload SACCObject with null key');
define('_MD_SACC_ERR_5', 'You must be logged in to edit records');
define('_MD_SACC_ERR_6', 'No account type data for Account indexed by %s');
define('_MD_SACC_ERR_7', 'No data SACCAccount object indexed by %s');
define('_MD_SACC_ERR_8', 'Invalid id (%s) for SACCAccountHandler->getall()');
define('_MD_SACC_ERR_9', 'Cannot Defunct %s as it has a non zero balance');
define('_MD_SACC_ERR_10', 'Unable to save Journal - database error');
define('_MD_SACC_ERR_11', 'Unable to save Journal - journal does not balance');
/**#@-*/
