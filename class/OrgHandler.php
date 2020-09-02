<?php declare(strict_types=1);

namespace XoopsModules\Xbssacc;

use XoopsModules\Xbscdm\BaseHandler;

/**
 * Organisation object handler
 *
 * @package       SACC
 * @subpackage    Org
 * @author        Ashley Kitson <akitson@bbcb.co.uk>
 * @copyright (c) 2004 Ashley Kitson, Great Britain
 */

/**
 * SACC base object definitions
 */

/**
 * SACC Organisation object handler
 *
 * XoopsModule handler
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Org
 * @version       1
 */
class OrgHandler extends BaseHandler
{
    /**
     * Function: constructor
     *
     * Organisation handler constructor
     *
     * @param \XoopsDatabase $db handle to current database
     * @version 1
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db); //call ancestor constructor

        $this->classname = Org::class;

        $this->ins_tagname = 'sacc_ins_org';
    }

    /**
     * Function: _create Create Organisation object
     *
     * @version 1
     * @access  private
     */
    public function _create()
    {
        return new Org();
    }

    //end function _create

    /**
     * Function: _get create sql string to get data for object
     *
     * @param      $key
     * @param null $row_flag
     * @param null $lang
     * @return string
     * @version 1
     * @access  private
     */
    public function &_get($key, $row_flag = null, $lang = null)
    {
        $sql = sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ORG), $key);

        $sql .= (empty($row_flag) ? '' : ' and row_flag = ' . $this->db->quoteString($row_flag));

        return $sql;
    }

    //end function _get

    /**
     * Function: _reload reload an object from database
     *
     * @param null $key
     * @return string
     * @version 1
     * @access  private
     */
    public function _reload($key = null)
    {
        return sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ORG), $key);
    }

    /**
     * Load up the accounts belonging to this organisation
     *
     * @param mixed $obj
     * @version 1
     */
    public function loadAccounts($obj)
    {
        $id = (int)$obj->getVar('id');

        $sql = sprintf('SELECT id FROM %s WHERE org_id = %u ORDER BY disp_order', $this->db->prefix(SACC_TBL_ACC), $id);

        if ($result = $this->db->query($sql)) {
            $ret = [];

            $accHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

            while (false !== ($arr = $this->db->fetchArray($result))) {
                $ac = $accHandler->getAll($arr['id']);

                $ac->setBalance();

                $ret[] = $ac;
            }

            $obj->setVar('accounts', $ret);
        }
    }

    /**
     * Load up the journal entries belonging to this organisation
     *
     * @param mixed $obj
     * @version 1
     */
    public function loadJournal($obj)
    {
        $id = (int)$obj->getVar('id');

        $sql = sprintf('SELECT id FROM %s WHERE org_id = %u', $this->db->prefix(SACC_TBL_JOURN), $id);

        if ($result = $this->db->query($sql)) {
            $ret = [];

            $jHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Journal');

            while (false !== ($arr = $this->db->fetchArray($result))) {
                $ret[] = $jHandler->get($arr['id']);
            }

            $obj->setVar('journal', $ret);
        }
    }

    /**
     * function createControl.  Create a control account
     *
     * @param mixed $org_id
     * @param mixed $ac_id
     * @param mixed $ctrlName
     * @version 1
     */
    public function createControl($org_id, $ac_id, $ctrlName)
    {
        //set the control account details

        $ctrlHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Control');

        $ctrlAc = $ctrlHandler->create();

        $ctrlAc->setVar('org_id', $org_id);

        $ctrlAc->setVar('ctrl_cd', $ctrlName);

        $ctrlAc->setVar('ac_id', $ac_id);

        $ctrlHandler->insert($ctrlAc);
    }

    /**
     * function createAccounts - Create new base accounts for the organisation.
     *
     * @param mixed $org
     *
     * @return bool
     * @version 1
     */
    public function createAccounts($org)
    {
        $org_id = $org->getVar('id');

        $base_crcy = $org->getVar('base_crcy');

        // Set up an array of new Base account information

        $nac = [
            'asset'     => ['ac_tp' => SACC_ACTP_ASSET, 'ac_nm' => _MD_XBSSACC_NAC_ASSET],
            'liability' => ['ac_tp' => SACC_ACTP_LIABILITY, 'ac_nm' => _MD_XBSSACC_NAC_LIABILITY],
            'income'    => ['ac_tp' => SACC_ACTP_INCOME, 'ac_nm' => _MD_XBSSACC_NAC_INCOME],
            'expense'   => ['ac_tp' => SACC_ACTP_EXPENSE, 'ac_nm' => _MD_XBSSACC_NAC_EXPENSE],
            'equity'    => ['ac_tp' => SACC_ACTP_EQUITY, 'ac_nm' => _MD_XBSSACC_NAC_EQUITY],
            'bank'      => ['ac_tp' => SACC_ACTP_BANK, 'ac_nm' => _MD_XBSSACC_NAC_BANK],
            'open'      => ['ac_tp' => SACC_ACTP_EQUITY, 'ac_nm' => _MD_XBSSACC_NAC_OPEN],
        ];

        $accHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

        $ctrlHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Control');

        $account = [];

        //loop through the array and create the new accounts

        foreach ($nac as $ac) {
            $accs = $accHandler->create();

            $accs->setVar('ac_prnt_id', _MD_XBSSACC_NOPARENT);

            $accs->setVar('org_id', $org_id);

            $accs->setVar('ac_curr', $base_crcy);

            $accs->setVar('id', 0);

            $accs->setVar('ac_nm', $ac['ac_nm']);

            $accs->setVar('ac_tp', $ac['ac_tp']);

            $accs->setVar('ac_dr', 0);

            $accs->setVar('ac_cr', 0);

            $accHandler->insert($accs); //create the account
            $account[] = $accs;  //Save details
        } //for loop end

        //get the account id's for each of the accounts

        $bank_key = array_search('bank', array_keys($nac));

        $asset_key = array_search('asset', array_keys($nac));

        $open_key = array_search('open', array_keys($nac));

        $equity_key = array_search('equity', array_keys($nac));

        $liability_key = array_search('liability', array_keys($nac));

        $income_key = array_search('income', array_keys($nac));

        $expense_key = array_search('expense', array_keys($nac));

        //reset the parent account for the bank account

        $account[$bank_key]->setVar('ac_prnt_id', $account[$asset_key]->getVar('id'));

        $accHandler->insert($account[$bank_key]); //update the account

        //reset the parent account for the Opening Balances account

        $account[$open_key]->setVar('ac_prnt_id', $account[$equity_key]->getVar('id'));

        $accHandler->insert($account[$open_key]); //update the account

        //add the control account information

        $this->createControl($org_id, $account[$bank_key]->getVar('id'), SACC_CNTL_BANK);

        $this->createControl($org_id, $account[$open_key]->getVar('id'), SACC_CNTL_OPEN);

        $this->createControl($org_id, $account[$asset_key]->getVar('id'), SACC_CNTL_ASST);

        $this->createControl($org_id, $account[$liability_key]->getVar('id'), SACC_CNTL_LIAB);

        $this->createControl($org_id, $account[$equity_key]->getVar('id'), SACC_CNTL_EQUI);

        $this->createControl($org_id, $account[$income_key]->getVar('id'), SACC_CNTL_INCO);

        $this->createControl($org_id, $account[$expense_key]->getVar('id'), SACC_CNTL_EXPE);

        return true;
        //$this->loadAccounts($org);  //load the accounts
    }

    /**
     * return an array of All id, orgname pairs for use in an admin user form select box
     *
     * @return array
     */
    public function getSelectListAll()
    {
        $sql = sprintf('SELECT id, org_name, row_flag FROM %s', $this->db->prefix(SACC_TBL_ORG));

        $result = $this->db->query($sql);

        $ret = [];

        while (false !== ($res = $this->db->fetchArray($result))) {
            switch ($res['row_flag']) {
                case SACC_RSTAT_DEF:
                    $ret[$res['id']] = $res['org_name'] . ' (' . SACC_RSTAT_DEF . ')';
                    break;
                case SACC_RSTAT_SUS:
                    $ret[$res['id']] = $res['org_name'] . ' (' . SACC_RSTAT_SUS . ')';
                    break;
                default:
                    $ret[$res['id']] = $res['org_name'];
                    break;
            }
        }

        return $ret;
    }

    /**
     * return an array of Active id, orgname pairs for use in a end user form select box
     *
     * @return array
     */
    public function getSelectList()
    {
        $sql = sprintf('SELECT id, org_name FROM %s WHERE row_flag= %s', $this->db->prefix(SACC_TBL_ORG), $this->db->quoteString(SACC_RSTAT_ACT));

        $result = $this->db->query($sql);

        $ret = [];

        while (false !== ($res = $this->db->fetchArray($result))) {
            $ret[$res['id']] = $res['org_name'];
        }

        return $ret;
    }

    /**
     * create sql string to insert object data
     *
     * @access private
     * @param $cleanVars
     * @return string
     */
    public function _ins_insert($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $sql = sprintf(
            'INSERT INTO %s (id, base_crcy, org_name,row_flag,row_uid,row_dt) VALUES (%u,%s,%s,%s,%u,%s)',
            $this->db->prefix(SACC_TBL_ORG),
            $id,
            $this->db->quoteString($base_crcy),
            $this->db->quoteString($org_name),
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt)
        );

        return $sql;
    }

    /**
     * create sql string to update object data
     *
     * @access private
     * @param $cleanVars
     * @return string
     */
    public function _ins_update($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $sql = sprintf(
            'UPDATE %s SET base_crcy = %s,org_name = %s,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u',
            $this->db->prefix(SACC_TBL_ORG),
            $this->db->quoteString($base_crcy),
            $this->db->quoteString($org_name),
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt),
            $id
        );

        return $sql;
    }

    /**
     * Insert data into database - extend ancestor
     *
     * @param \XoopsObject $code
     *
     * @return bool
     * @internal param \Handle $SACCOrg to organisation object
     */
    public function insert(\XoopsObject $code)
    {
        $base_crcy = $code->getVar('base_crcy');

        $base_crcy = (empty($base_crcy) ? SACC_DEF_CRCY : $base_crcy);

        $code->setVar('base_crcy', $base_crcy); //default currency if none given

        return parent::insert($code);
    }

    //end function insert

    /**
     * Function: countOrgs
     *
     * Count the number of organisations
     *
     * @return int number of organisations
     * @version 1
     */
    public function countOrgs()
    {
        $sql = sprintf('SELECT count(*) FROM %s', $this->db->prefix(SACC_TBL_ORG));

        $result = $this->db->queryF($sql);

        $ret = $this->db->fetchRow($result);

        return $ret[0];
    }
    //end function countOrgs
} //end class OrgHandler
