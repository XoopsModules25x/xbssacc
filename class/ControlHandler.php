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
 * Control account object hamdler
 *
 * @package       SACC
 * @subpackage    SACCControl
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 */

/**
 * SACC functions
 */
//require_once CDM_PATH . '/include/functions.php';

/**
 * Object handler for SACCControl
 *
 * @package    SACC
 * @subpackage SACCControl
 */
class ControlHandler extends Xbscdm\BaseHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db); //call ancestor constructor

        $this->classname = 'sacccontrol';

        $this->ins_tagname = 'sacc_ins_control';
    }

    /**
     * create a control object
     *
     * @internal
     */
    public function _create()
    {
        return new Control();
    }

    //end function _create

    /**
     * create sql to get data. NB This is different to normal _get as we have a 2 part key
     *
     * @param      $org_id
     * @param      $ctrl_cd
     * @param null $row_flag
     * @return string
     * @internal
     */
    public function &_get($org_id, $ctrl_cd, $row_flag = null)
    {
        $sql = sprintf('SELECT * FROM %s WHERE org_id = %u AND ctrl_cd = %s', $this->db->prefix(SACC_TBL_CTRL), $org_id, $this->db->quoteString($ctrl_cd));

        $sql .= (empty($row_flag) ? '' : ' and row_flag = ' . $this->db->quoteString($row_flag));

        return $sql;
    }

    //end function _get

    /**
     * function getall - overide ancestor because we have a 2 part key
     *
     * @param int    $org_id   organisation id
     * @param string $ctrl_cd  control account tag name
     * @param null   $row_flag default NULL. row status flag indicator
     * @param null   $lang     default NULL. Language set
     * @return SACCControl object if success else FALSE on failure.
     */
    public function getAll($org_id, $ctrl_cd, $row_flag = null, $lang = null)
    {
        $test = (is_int($org_id) ? ($org_id > 0 ? true : false) : !empty($org_id) ? true : false);

        $test2 = !empty($ctrl_cd);

        if ($test and $test2) {
            $code = $this->create(false);

            if ($code) {
                $sql = $this->_get($org_id, $ctrl_cd, $row_flag, $lang);

                if ($result = $this->db->query($sql)) {
                    if (1 == $this->db->getRowsNum($result)) {
                        $code->assignVars($this->db->fetchArray($result));

                        return $code;
                    }

                    $this->setError(-1, sprintf(_MD_XBSCDM_ERR_1, (string)$id));
                } else {
                    $this->setError($this->db->errno(), $this->db->error());
                }//end if
            }//end if - error value set in call to create()
        } else {
            $this->setError(-1, sprintf(_MD_XBSCDM_ERR_1, (string)$id));
        }

        return false; //default return
    }

    //end function &getall

    /**
     * get safe data - overide ancestor
     *
     * @param int    $org_id  organisation id
     * @param string $ctrl_cd control account tag name
     * @param string $lang    default CDM default language code. Language set
     * @return SACCControl object on success else FALSE on failure
     */
    public function get($org_id, $ctrl_cd, $lang = CDM_DEF_LANG)
    {
        return $this->getAll($org_id, $ctrl_cd, CDM_RSTAT_ACT, $lang);
    }

    /**
     * create sql string to insert data
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

        $sql = sprintf('INSERT INTO %s (org_id,ctrl_cd,ac_id,row_flag,row_uid,row_dt) VALUES (%u,%s,%u,%s,%u,%s)', $this->db->prefix(SACC_TBL_CTRL), $org_id, $this->db->quoteString($ctrl_cd), $ac_id, $this->db->quoteString($row_flag), $row_uid, $this->db->quoteString($row_dt));

        return $sql;
    }

    /**
     * create sql string to update data
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

        $sql = sprintf('UPDATE %s SET ac_id=%u,row_flag = %s,row_uid = %u,row_dt = %s WHERE org_id = %u AND ctrl_cd = %s', $this->db->prefix(SACC_TBL_CTRL), $ac_id, $this->db->quoteString($row_flag), $row_uid, $this->db->quoteString($row_dt), $org_id, $ctrl_cd);

        return $sql;
    }
} //end class ControlHandler
