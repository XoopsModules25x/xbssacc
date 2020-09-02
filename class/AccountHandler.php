<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc;

use XoopsModules\Xbscdm;

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
 * Account Object Handler
 *
 * @package       SACC
 * @subpackage    Account
 * @author        Ashley Kitson http://xoobs.net
 * @copyright (c) 2004 Ashley Kitson, UK
 */

/**
 * SACC functions
 */
//require_once CDM_PATH . '/include/functions.php';

/**
 * Object handler for Account
 *
 * @package       SACC
 * @subpackage    Account
 * @author        Ashley Kitson http://xoobs.net
 * @copyright (c) 2004 Ashley Kitson, UK
 */
class AccountHandler extends Xbscdm\BaseHandler
{
    /**
     * Function: Constructor
     *
     * @param mixed $db
     * @version 1
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db); //call ancestor constructor

        $this->classname = 'saccaccount';

        $this->ins_tagname = 'sacc_ins_account';
    }

    /**
     * Create account object -  overide ancestor because we need to know what type of account it is
     *
     * @param null $accType                   default null. Type of account to create, SACC_ACTP_INCOME, SACC_ACTP_EXPENSE, SACC_ACTP_ASSET,
     *                                        SACC_ACTP_LIABILITY, SACC_ACTP_BANK, SACC_ACTP_SUPPLIER, SACC_ACTP_CUSTOMER,
     *                                        SACC_ACTP_EQUITY
     * @param bool $isNew                     default TRUE. This is a new account we are creating
     * @return Account object else FALSE on failure
     */
    public function create($accType = null, $isNew = true)
    {
        switch ($accType) {
            case SACC_ACTP_INCOME:
                $obj = new IncomeAc();
                break;
            case SACC_ACTP_EXPENSE:
                $obj = new ExpenseAc();
                break;
            case SACC_ACTP_ASSET:
                $obj = new AssetAc();
                break;
            case SACC_ACTP_LIABILITY:
                $obj = new LiabilityAc();
                break;
            case SACC_ACTP_BANK:
                $obj = new BankAc();
                break;
            case SACC_ACTP_SUPPLIER:
                $obj = new SupplierAc();
                break;
            case SACC_ACTP_CUSTOMER:
                $obj = new CustomerAc();
                break;
            case SACC_ACTP_EQUITY:
                $obj = new EquityAc();
                break;
            default:
                //we will always get an object even if it the base account type
                $obj = new Account();
        }//end switch
        if ($isNew && $obj) { //if it is new and the object was created
            $obj->setNew();

            $obj->unsetDirty();
        } elseif ($obj) {         //it is not new (forced by caller, usually &getAll()) but obj was created
            $obj->unsetNew();

            $obj->unsetDirty();
        } else {
            $this->setError(-1, sprintf(_MD_XBSCDM_ERR_2, $classname));

            return false;      //obj was not created so return False to caller.
        }

        return $obj;
    }

    //end function create

    /**
     * create sql string for getting object data
     *
     * @param      $key
     * @param null $row_flag
     * @param null $lang
     * @return string
     * @internal
     */
    public function &_get($key, $row_flag = null, $lang = null)
    {
        $sql = sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ACC), $key);

        $sql .= (empty($row_flag) ? '' : ' and row_flag = ' . $this->db->quoteString($row_flag));

        return $sql;
    }

    //end function _get

    /**
     * function getall - overide ancestor because we need to know account type
     *
     * @param int  $id       id of account to get
     * @param null $row_flag default null.  Row status flag
     * @param null $lang
     * @return bool object on success else FALSE on failure
     */
    public function getAll($id, $row_flag = null, $lang = null)
    {
        $test = (is_int($id) ? ($id > 0 ? true : false) : (!empty($id) ? true : false));

        if ($test) {
            $sql = 'SELECT ac_tp FROM ' . $this->db->prefix(SACC_TBL_ACC) . ' WHERE id = ' . $id;

            if ($result = $this->db->query($sql)) {
                $ret = $this->db->fetchRow($result);

                $actype = $ret[0];
            } else {//no result
                $this->setError(-1, sprintf(_MD_XBSSACC_ERR_6, (string)$id));
            }

            $account = $this->create($actype, false);

            if ($account) {
                $sql = $this->_get($id, $row_flag);

                if ($result = $this->db->query($sql)) {
                    if (1 == $this->db->getRowsNum($result)) {
                        $account->assignVars($this->db->fetchArray($result));

                        $account->setBalance();

                        return $account;
                    }

                    $this->setError(-1, sprintf(_MD_XBSSACC_ERR_7, (string)$id));
                } else {
                    $this->setError($this->db->errno(), $this->db->error());
                }//end if
            }//end if - error value set in call to create()
        } else {
            $this->setError(-1, sprintf(_MD_XBSSACC_ERR_8, (string)$id));
        }//end if
        return false; //default return
    }

    //end function &getall

    /**
     * create sql to reload object data
     *
     * @param null $key
     * @return string
     * @internal
     */
    public function _reload($key = null)
    {
        return sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ACC), $key);
    }

    /**
     * function reload - extend ancestor to add account information
     *
     * @param Account $obj handle to account object
     * @param null    $key Accoint id
     * @return bool
     */
    public function reload($obj, $key = null)
    {
        $ret = parent::reload($obj, $key);

        $this->aggregate($ret);

        return $ret;
    }

    /**
     * Update the account balance for a given account
     *
     * Recursive function that will update balances for the parent account
     *
     * @param int $ac_id account id
     * @param int $ac_dr debit account balance
     * @param int $ac_cr credit account balance
     *
     * @return bool
     */
    public function updateBalances($ac_id, $ac_dr = 0, $ac_cr = 0)
    {
        if (0 == $ac_id) {
            return true;
        }  //all done

        //else update the balance for this account

        $accountHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

        $accountData = &$accountHandler->getAll($ac_id);

        $new_dr = $accountData->getVar('ac_dr') + $ac_dr;

        $new_cr = $accountData->getVar('ac_cr') + $ac_cr;

        $accountData->setVar('ac_dr', $new_dr);

        $accountData->setVar('ac_cr', $new_cr);

        $accountData->setBalance();

        $accountHandler->insert($accountData);

        $this->updateBalances($accountData->getVar('ac_prnt_id'), $ac_dr, $ac_cr);

        return true;
    }

    /**
     * function setOrder - sets the display order for accounts
     *
     * Recursive function, will set order for child accounts
     *
     * @param int $ac_id account id of account to set order for
     * @param int $order starting order number
     *
     * @return int
     */
    public function setOrder($ac_id, $order)
    {
        $sql = sprintf('UPDATE %s SET disp_order = %u WHERE id = %u', $this->db->prefix(SACC_TBL_ACC), $order, $ac_id);

        $result = $this->db->query($sql);

        $order++;

        $sql = sprintf('SELECT id FROM %s WHERE ac_prnt_id = %u', $this->db->prefix(SACC_TBL_ACC), $ac_id);

        if ($result = $this->db->query($sql)) {
            while (false !== ($arr = $this->db->fetchArray($result))) {
                $order = $this->setOrder($arr['id'], $order);
            }
        }

        return $order;
    }

    //end function setOrder

    /**
     * create sql string for object insert
     *
     * @param $cleanVars
     * @return string
     * @internal
     */
    public function _ins_insert($cleanVars)
    {
        extract($cleanVars);

        $sql = sprintf('INSERT INTO %s (id,ac_prnt_id,org_id,ac_curr,ac_tp,ac_nm,ac_prps,ac_note,ac_dr,ac_cr,ac_level,has_kids,row_flag,row_uid,row_dt)', $this->db->prefix(SACC_TBL_ACC));

        $sql .= sprintf(
            ' VALUES (%u,%u,%u,%s,%s,%s,%s,%s,%u,%u,%u,%u,%s,%u,%s)',
            $id,
            $ac_prnt_id,
            $org_id,
            $this->db->quoteString($ac_curr),
            $this->db->quoteString($ac_tp),
            $this->db->quoteString($ac_nm),
            $this->db->quoteString($ac_prps),
            $this->db->quoteString($ac_note),
            $ac_dr,
            $ac_cr,
            $ac_level,
            $has_kids,
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt)
        );

        return $sql;
    }

    /**
     * create sql string for object update
     *
     * @param $cleanVars
     * @return string
     * @internal
     */
    public function _ins_update($cleanVars)
    {
        extract($cleanVars);

        return sprintf(
            'UPDATE %s SET ac_prnt_id=%u,org_id=%u,ac_curr=%s,ac_tp=%s,ac_nm=%s,ac_prps=%s,ac_note=%s,ac_dr=%u,ac_cr=%u,ac_level=%u,has_kids=%u,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u',
            $this->db->prefix(SACC_TBL_ACC),
            $ac_prnt_id,
            $org_id,
            $this->db->quoteString($ac_curr),
            $this->db->quoteString($ac_tp),
            $this->db->quoteString($ac_nm),
            $this->db->quoteString($ac_prps),
            $this->db->quoteString($ac_note),
            $ac_dr,
            $ac_cr,
            $ac_level,
            $has_kids,
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt),
            $id
        );
    }

    /**
     * Defunct any children of an account that has been defuncted itself
     *
     * @param int $account Account object to process
     */
    public function defunctChildAccounts($account)
    {
        if (1 == $account->getVar('has_kids')) {
            $sql = sprintf('SELECT id FROM %s WHERE ac_prnt_id = %u', $this->db->prefix(SACC_TBL_ACC), $account->getVar('id'));

            if ($result = $this->db->query($sql)) {
                $accHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

                while (false !== ($arr = $this->db->fetchArray($result))) {
                    $acc = $accHandler->get($arr['id']);

                    $acc->setDefunct();

                    $accHandler->insert($acc);

                    $this->defunctChildAccounts($acc);
                }//end while
            }//end if
        }//end if
    }

    //end function defunctChildAccounts

    /**
     * Insert data into database - extend ancestor for pre processing
     *
     * @param \XoopsObject $account
     *
     * @return bool
     * @internal param \Handle $SACCAccount to account object
     */
    public function insert(\XoopsObject $account)
    {
        if (!$account->isDirty()) {
            return true;
        }    // if data is untouched then don't save

        //Check if we are about to defunct the account and if so if the balance is zero

        if (SACC_RSTAT_DEF == $account->getVar('row_flag')) {
            $account->setBalance();

            if (0 != $account->getVar('ac_net_bal')) {
                $this->setError(-1, sprintf(_MD_XBSSACC_ERR_9, $account->getVar('ac_nm')));

                return false;
            }

            //defunct any children of this account as well

            $this->defunctChildAccounts($account);
        }//end if

        //  get organisation base currency

        $orgHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Org');

        $org = $orgHandler->get($account->getVar('org_id'));

        //  set default currency

        $crcy = $account->getVar('ac_curr');

        $crcy = (empty($crcy) ? $org->getVar('base_crcy') : $crcy);

        $account->setVar('ac_curr', $crcy);

        //work out level information for later account display

        $ac_prnt_id = $account->getVar('ac_prnt_id');

        if ($ac_prnt_id > 0) { //has a parent so process
            $sql = sprintf('SELECT ac_level FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ACC), $ac_prnt_id);

            if ($result = $this->db->query($sql)) {
                $arr = $this->db->fetchArray($result);

                $account->setVar('ac_level', $arr['ac_level'] + 1);

                //tell parent it has kids

                $sql = sprintf('UPDATE %s SET has_kids = 1 WHERE id = %u', $this->db->prefix(SACC_TBL_ACC), $ac_prnt_id);

                $result = $this->db->query($sql);
            }//end if
        } else {
            $account->setVar('ac_level', 0);
        }//end if
        $account->setVar('has_kids', 0); //this account doesn't have kids yet!
        //run ancestor
        $newac = parent::insert($account);

        //set up the display levels for the accounts

        $sql = sprintf('SELECT id FROM %s WHERE ac_prnt_id = 0', $this->db->prefix(SACC_TBL_ACC));

        $order = 0;

        $result = $this->db->query($sql);

        while (false !== ($arr = $this->db->fetchArray($result))) {
            $order = $this->setOrder($arr['id'], $order);
        }//end while

        return $newac;
    }

    //end function insert

    /**
     * Function: return array of code, value pairs for use in drop down select box
     *
     * @param int  $org_id              Id of organisation to get list for
     * @param bool $ignore_prnt_setting Ignore the Use parent config setting.  This ensures all accounts are included
     * @return array array([id]->account name)
     * @version 1.1
     */
    public function getSelectList($org_id = 1, $ignore_prnt_setting = false)
    {
        $sql = sprintf('SELECT id, ac_nm, ac_level FROM %s WHERE org_id = %u AND row_flag= %s ', $this->db->prefix(SACC_TBL_ACC), $org_id, $this->db->quoteString(SACC_RSTAT_ACT));

        $sql .= ((SACC_CFG_USEPRNT == 0 and !$ignore_prnt_setting) ? 'and has_kids = 0 ' : '');

        $sql .= 'order by disp_order';

        $result = $this->db->query($sql);

        $ret = [];

        while (false !== ($res = $this->db->fetchArray($result))) {
            //indent the account name according to its level in the account hiearacrchy

            $disp_level = $res['ac_level'];

            $slen = mb_strlen($res['ac_nm']) + ($disp_level * 12);

            $res['ac_nm'] = str_pad($res['ac_nm'], $slen, '&nbsp;', STR_PAD_LEFT);

            //and construct the return array

            $ret[$res['id']] = $res['ac_nm'];
        }

        return $ret;
    }

    /**
     * function loadEntries - load account entries for the given account
     *
     * @param mixed $obj
     */
    public function loadEntries($obj)
    {
        $id  = (int)$obj->getVar('id'); //get account id
        $sql = sprintf('SELECT id FROM %s WHERE ac_id = %u', $this->db->prefix(SACC_TBL_ENTRY), $id); //get list of account entries
        if ($result = $this->db->query($sql)) {
            $ret = [];

            $entHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Entry');

            while (false !== ($arr = $this->db->fetchArray($result))) {
                $entry = $entHandler->get($arr['id']);

                $ret[] = $entry;
            }

            $obj->setVar('entries', $ret);
        }
    }
} //end class AccountHandler
