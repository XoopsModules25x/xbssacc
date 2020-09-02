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
 * Account entry object handler
 *
 * @package       SACC
 * @subpackage    SACCEntry
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 */

/**
 * Object handler for SACCEntry
 *
 * @package    SACC
 * @subpackage SACCEntry
 */
class EntryHandler extends Xbscdm\BaseHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db); //call ancestor constructor

        $this->classname = 'saccacentry';

        $this->ins_tagname = 'sacc_ins_entry';
    }

    /**
     * create new Entry object
     *
     * @internal
     */
    public function _create()
    {
        return new AcEntry();
    }

    //end function _create

    /**
     * get a journal object
     *
     * @param int        $key journal id to get
     * @param null|mixed $row_flag
     *
     * @return string
     * @internal
     */
    public function _get($key, $row_flag = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix(SACC_TBL_ENTRY) . ' WHERE id = ' . $key;

        $sql .= (empty($row_flag) ? '' : ' and row_flag = ' . $this->db->quoteString($row_flag));

        return $sql;
    }

    /**
     * sql string to insert object data
     *
     * @param $cleanVars
     * @return string
     * @internal
     */
    public function _ins_insert($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $sql = sprintf(
            'INSERT INTO %s (id,jrn_id,ac_id,txn_ref,txn_dr,txn_cr,row_flag,row_uid,row_dt) VALUES (%u,%u,%u,%s,%u,%u,%s,%u,%s)',
            $this->db->prefix(SACC_TBL_ENTRY),
            $id,
            $jrn_id,
            $ac_id,
            $this->db->quoteString($txn_ref),
            $txn_dr,
            $txn_cr,
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt)
        );

        return $sql;
    }

    /**
     * sql string to update object data
     *
     * @param $cleanVars
     * @return string
     * @internal
     */
    public function _ins_update($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $sql = sprintf(
            'UPDATE %s SET jrn_id=%u,ac_id=%u,txn_ref=%s,txn_dr=%u,txn_cr=%u,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u',
            $this->db->prefix(SACC_TBL_ENTRY),
            $jrn_id,
            $ac_id,
            $this->db->quoteString($txn_ref),
            $txn_dr,
            $txn_cr,
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt),
            $id
        );

        return $sql;
    }

    /**
     * Insert journal object to database - extend ancestor for post process
     *
     * @param \XoopsObject $entry
     *
     * @return bool TRUE on success else FALSE
     * @internal param \handle $SACCJournal to object to insert
     */
    public function insert(\XoopsObject $entry)
    {
        if (parent::insert($entry)) {
            //Now need to bubble up the account entry into the account totals

            $accHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Account');

            $ac_id = $entry->getVar('ac_id');

            $txn_dr = $entry->getVar('txn_dr');

            $txn_cr = $entry->getVar('txn_cr');

            return $accHandler->updateBalances($ac_id, $txn_dr, $txn_cr);
        }

        return false;
    }
    //end function insert
} //end class EntryHandler
