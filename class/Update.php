<?php

use XoopsModules\Tadtools\Utility;

namespace XoopsModules\Tad_form;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{
    //刪除錯誤的重複欄位及樣板檔
    public static function chk_tad_form_block()
    {
        global $xoopsDB;
        //die(var_export($xoopsConfig));
        require XOOPS_ROOT_PATH . '/modules/tad_form/xoops_version.php';

        //先找出該有的區塊以及對應樣板
        foreach ($modversion['blocks'] as $i => $block) {
            $show_func = $block['show_func'];
            $tpl_file_arr[$show_func] = $block['template'];
            $tpl_desc_arr[$show_func] = $block['description'];
        }

        //找出目前所有的樣板檔
        $sql = 'SELECT bid,name,visible,show_func,template FROM `' . $xoopsDB->prefix('newblocks') . "`
        WHERE `dirname` = 'tad_form' ORDER BY `func_num`";
        $result = $xoopsDB->query($sql);
        while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
            //假如現有的區塊和樣板對不上就刪掉
            if ($template != $tpl_file_arr[$show_func]) {
                $sql = 'delete from ' . $xoopsDB->prefix('newblocks') . " where bid='{$bid}'";
                $xoopsDB->queryF($sql);

                //連同樣板以及樣板實體檔案也要刪掉
                $sql = 'delete from ' . $xoopsDB->prefix('tplfile') . ' as a
                left join ' . $xoopsDB->prefix('tplsource') . "  as b on a.tpl_id=b.tpl_id
                where a.tpl_refid='$bid' and a.tpl_module='tad_form' and a.tpl_type='block'";
                $xoopsDB->queryF($sql);
            } else {
                $sql = 'update ' . $xoopsDB->prefix('tplfile') . "
                set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}'
                where tpl_refid='{$bid}'";
                $xoopsDB->queryF($sql);
            }
        }
    }

    public static function chk_chk1()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`kind`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . ' ADD `kind` VARCHAR(255) NOT NULL';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk2()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`result_col`) FROM ' . $xoopsDB->prefix('tad_form_fill');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update2()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_fill') . ' ADD `result_col` VARCHAR(255) NOT NULL';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk3()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`adm_email`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update3()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . ' ADD `adm_email` VARCHAR(255) NOT NULL';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk4()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`captcha`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update4()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . " ADD `captcha` ENUM('1','0') NOT NULL DEFAULT '1'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk5()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`show_result`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update5()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . " ADD `show_result` ENUM('1','0') NOT NULL DEFAULT '1'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk6()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`view_result_group`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update6()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . " ADD `view_result_group` VARCHAR(255) NOT NULL DEFAULT ''";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk7()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`multi_sign`) FROM ' . $xoopsDB->prefix('tad_form_main');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update7()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_main') . " ADD `multi_sign` ENUM('0','1') NOT NULL DEFAULT '0'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk8()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`public`) FROM ' . $xoopsDB->prefix('tad_form_col');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update8()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_col') . " ADD `public`  ENUM('0','1') NOT NULL DEFAULT '0'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //修正uid欄位
    public static function chk_uid()
    {
        global $xoopsDB;
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = '" . $xoopsDB->prefix('tad_form_main') . "' AND COLUMN_NAME = 'uid'";
        $result = $xoopsDB->query($sql);
        list($type) = $xoopsDB->fetchRow($result);
        if ('smallint' === $type) {
            return true;
        }

        return false;
    }

    //執行更新
    public static function go_update_uid()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_form_main') . '` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_form_fill') . '` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    public static function chk_chk9()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`code`) FROM ' . $xoopsDB->prefix('tad_form_fill');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update9()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_form_fill') . ' ADD `code` VARCHAR(255) NOT NULL';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'update ' . $xoopsDB->prefix('tad_form_fill') . ' set code=md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, `fill_time`)) ';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

}
