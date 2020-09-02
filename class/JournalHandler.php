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
 * SACC Journal object handler
 *
 * @package       SACC
 * @subpackage    Journal
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 */

/**
 * Object handler for Journal
 *
 * @package    SACC
 * @subpackage Journal
 */
class JournalHandler extends Xbscdm\BaseHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db); //call ancestor constructor

        $this->classname = 'saccjournal';

        $this->ins_tagname = 'sacc_ins_journal';
    }

    /**
     * create a journal object
     *
     * @internal
     */
    public function _create()
    {
        return new Journal();
    }

    //end function _create

    /**
     * Create a journal object - extend ancestor create function
     *
     * @param date $jrn_dt   date of journal
     * @param null $jrn_prps purpose of journal
     * @param null $org_id   id of organisation
     * @return Journal object
     */
    public function create($jrn_dt, $jrn_prps = null, $org_id = null)
    {
        $obj = parent::create();

        $jrn_dt = (empty($jrn_dt) ? date('d/m/Y') : $jrn_dt);

        $obj->setVar('jrn_dt', $jrn_dt);

        $obj->setVar('jrn_prps', $jrn_prps);

        $obj->setVar('org_id', $org_id);

        return $obj;
    }

    /**
     * function loadEntries.  Load the account entries for the journal
     * returns journal with entries else FALSE on error
     * @param $journ
     * @return bool
     */
    public function loadEntries($journ)
    {
        $entries = [];

        $sql = sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_ENTRY), $id);

        $sql .= (empty($row_flag) ? '' : sprintf(' and row_flag = %s', $this->db->quoteString($row_flag)));

        if ($result = $this->db->query($sql)) {
            while (false !== ($row = $this->db->fetchArray($result))) {
                $entry = new AcEntry();

                $entry->assignVars($row);

                $entries[] = $entry;
            } //end while

            $journ->setVar('acc_entry', $entries);

            return $journ;
        }

        $this->setError($this->db->errno(), $this->db->error());

        //end if

        return false;
    }

    //end function loadEntries

    /**
     * function getall - overide ancestor because the call to create() is different
     *
     * @param int  $id       journal id
     * @param null $row_flag default = null. row status flag
     *
     * @param null $lang
     * @return bool|object|\Journal
     */
    public function getAll($id, $row_flag = null, $lang = null)
    {
        $test = (is_int($id) ? ($id > 0 ? true : false) : (!empty($id) ? true : false));

        if ($test) {
            $journ = $this->create(null, null, null);

            if ($journ) {
                $sql = sprintf('SELECT * FROM %s WHERE id = %u', $this->db->prefix(SACC_TBL_JOURN), $id);

                $sql .= (empty($row_flag) ? '' : sprintf(' and row_flag = %s', $this->db->quoteString($row_flag)));

                if ($result = $this->db->query($sql)) {
                    if (1 == $this->db->getRowsNum($result)) {
                        $journ->assignVars($this->db->fetchArray($result));

                        return $journ;
                    }

                    $this->setError(-1, sprintf(_MD_XBSCDM_ERR_1, (string)$id));
                    //end if
                } else {
                    $this->setError($this->db->errno(), $this->db->error());
                }//end if
            }//end if - error value set in call to create()
        } else {
            $this->setError(-1, sprintf(_MD_XBSCDM_ERR_1, (string)$id));
        }//end if
        return false; //default return
    }

    //end function &getall

    /**
     * create sql string to insert object data
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
            'INSERT INTO %s (id,org_id,jrn_dt,jrn_prps,row_flag,row_uid,row_dt) VALUES (%u,%u,%s,%s,%s,%u,%s)',
            $this->db->prefix(SACC_TBL_JOURN),
            $id,
            $org_id,
            $this->db->quoteString($jrn_dt),
            $this->db->quoteString($jrn_prps),
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt)
        );

        return $sql;
    }

    /**
     * create sql string to update object data
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
            'UPDATE %s SET jrn_dt=%s,org_id=%u,jrn_prps=%s,row_flag = %s,row_uid = %u,row_dt = %s WHERE id = %u',
            $this->db->prefix(SACC_TBL_JOURN),
            $this->db->quoteString($jrn_dt),
            $org_id,
            $this->db->quoteString($jrn_prps),
            $this->db->quoteString($row_flag),
            $row_uid,
            $this->db->quoteString($row_dt),
            $id
        );

        return $sql;
    }

    /**
     * insert object data to database - extend ancestor for post processing
     *
     * @param \XoopsObject $journal
     *
     * @return bool
     * @internal param \handle $SACCJournal to journal object to insert
     */
    public function insert(\XoopsObject $journal)
    {
        if (parent::insert($journal)) {
            // Journal header data saved, so now save any attached entries

            $entries = $journal->getVar('acc_entry');

            $id = $journal->getVar('id');

            $eHandler = \XoopsModules\Xbssacc\Helper::getInstance()->getHandler('Entry');

            foreach ($entries as $entry) {
                $entry->setVar('jrn_id', $id);

                $entry->setNew();

                if (!$eHandler->insert($entry)) {
                    return false;
                }
            }

            $journal->setVar('acc_entry', $entries);

            return true;
        }

        return false;
    }
    //end function insert
} //end class JournalHandler
