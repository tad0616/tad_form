<?php
namespace XoopsModules\Tad_form;

use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_form\Tad_form_main;
use XoopsModules\Tad_form\Tad_form_value;
use XoopsModules\Tad_form\Tools;

/**
 * Tad_form module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    Tad_form
 * @since      2.5
 * @author     tad
 * @version    $Id $
 **/

class Tad_form_col
{
    // 過濾用變數的設定
    public static $filter_arr = [
        'int' => ['csn', 'ofsn', 'chk', 'sort', 'public'], //數字類的欄位
        'html' => [], //含網頁語法的欄位（所見即所得的內容）
        'text' => [], //純大量文字欄位
        'json' => [], //內容為 json 格式的欄位
        'pass' => [], //不予過濾的欄位
        'explode' => [], //用分號隔開的欄位
    ];

    // 可用的欄位種類
    public static $col_type = [
        'text' => _MD_TAD_FORM_COL_TEXT,
        'radio' => _MD_TAD_FORM_COL_RADIO,
        'checkbox' => _MD_TAD_FORM_COL_CHECKBOX,
        'select' => _MD_TAD_FORM_COL_SELECT,
        'textarea' => _MD_TAD_FORM_COL_TEXTAREA,
        'upload' => _MD_TAD_FORM_COL_UPLOAD,
        'date' => _MD_TAD_FORM_COL_DATE,
        'datetime' => _MD_TAD_FORM_COL_DATETIME,
        'show' => _MD_TAD_FORM_COL_SHOW,
    ];

    //列出所有 Tad_form_col::index() 資料
    public static function index($ofsn, $where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = '', $amount = '')
    {
        global $xoopsTpl;

        if ($amount) {
            list($all_tad_form_col, $total, $bar) = self::get_all($ofsn, $where_arr, $other_arr, $view_cols, $order_arr, $key_name, null, 'read', $amount);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('total', $total);
        } else {
            $all_tad_form_col = self::get_all($ofsn, $where_arr, $other_arr, $view_cols, $order_arr, $key_name);
        }

        $xoopsTpl->assign('all_tad_form_col', $all_tad_form_col);
        $xoopsTpl->assign('col_type', self::$col_type);
        $xoopsTpl->assign('ofsn', $where_arr['ofsn']);

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_col_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_col_destroy&ofsn={$where_arr['ofsn']}&csn=", 'csn');

        $form = Tad_form_main::get(['ofsn' => $where_arr['ofsn']], ['sign_group_title', 'view_result_group_title']);
        $xoopsTpl->assign('form', $form);
    }

    //取得 Tad_form_col::get_all() 所有資料陣列
    public static function get_all($ofsn = '', $where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = false, $get_value = '', $filter = 'read', $amount = '')
    {
        global $xoopsDB;
        if (empty($ofsn)) {
            $ofsn = $where_arr['ofsn'];
        }

        $and_sql = Tools::get_and_where($where_arr);
        $view_col = Tools::get_view_col($view_cols);
        $order_sql = Tools::get_order($order_arr);
        $order = $amount ? '' : $order_sql;
        $sql = "SELECT {$view_col} FROM `" . $xoopsDB->prefix("tad_form_col") . "` WHERE 1 {$and_sql} {$order}";

        // Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        if ($amount) {
            $PageBar = Utility::getPageBar($sql, $amount, 10, '', '', $_SESSION['bootstrap'], 'none', $order_sql);
            $bar = $PageBar['bar'];
            $sql = $PageBar['sql'];
            $total = $PageBar['total'];
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data_arr = [];
        $i = 0;
        if (isset($other_arr['ssn']) && !empty($other_arr['ssn'])) {
            $db_ans = Tad_form_value::get_all($ofsn, ['ssn' => $other_arr['ssn']], [], [], [], 'csn', 'val');
        }

        while ($data = $xoopsDB->fetchArray($result)) {

            $data = Tools::filter_all_data($filter, $data, self::$filter_arr);

            foreach (self::$filter_arr['explode'] as $item) {
                if (strpos($data[$item], '=') !== false) {
                    foreach (explode(';', $data[$item]) as $key => $value) {
                        list($k, $v) = explode('=', $value);
                        $data[$item . '_arr'][$k] = $v;
                    }
                } else {
                    $data[$item . '_arr'] = explode(';', $data[$item]);
                }
            }

            if ($ofsn) {
                $sign_group = Tad_form_main::get(['ofsn' => $ofsn], [], 'read', 'sign_group');
                if (in_array(3, $sign_group) && $data['kind'] == "upload") {
                    continue;
                }
            }

            $data['col_form'] = Tad_form_value::create($data, $other_arr['ssn'], $db_ans[$data['csn']]);

            $new_key = $key_name ? $data[$key_name] : $i;
            $data_arr[$new_key] = $get_value ? $data[$get_value] : $data;
            $i++;
        }

        if ($amount) {
            return [$data_arr, $total, $bar];
        } else {
            return $data_arr;
        }
    }

    //以流水號秀出某筆 tad_form_col 資料內容
    public static function show($where_arr = [], $other_arr = [], $mode = '')
    {
        global $xoopsTpl;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $all = self::get($where_arr, $other_arr);

        if (empty($all)) {
            return false;
        }

        foreach ($all as $key => $value) {
            $value = Tools::filter($key, $value, 'read', self::$filter_arr);
            $all[$key] = $value;
            $$key = $value;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_col_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_col_destroy&ofsn=", 'ofsn');

        if ($mode == "return") {
            return $all;
        } elseif ($mode == "assign_all") {
            $xoopsTpl->assign('tad_form_col', $all);
        } else {
            foreach ($all as $key => $value) {
                $xoopsTpl->assign($key, $value);
            }
        }
    }

    //以流水號取得某筆 tad_form_col 資料
    public static function get($where_arr = [], $other_arr = [], $filter = 'read', $only_key = '')
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $and_sql = Tools::get_and_where($where_arr);

        $sql = "SELECT * FROM `" . $xoopsDB->prefix("tad_form_col") . "` WHERE 1 $and_sql";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data = $xoopsDB->fetchArray($result);
        $data = Tools::filter_all_data($filter, $data, self::$filter_arr);

        foreach (self::$filter_arr['explode'] as $item) {
            if (strpos($data[$item], '=') !== false) {
                foreach (explode(';', $data[$item]) as $key => $value) {
                    list($k, $v) = explode('=', $value);
                    $data[$item . '_arr'][$k] = $v;
                }
            } else {
                $data[$item . '_arr'] = explode(';', $data[$item]);
            }
        }

        if ($only_key) {
            return $data[$only_key];
        } else {
            return $data;
        }
    }

    //tad_form_col 編輯表單
    public static function create($ofsn = '', $csn = '')
    {
        global $xoopsTpl;
        if ($ofsn) {
            Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);
        } else {
            Tools::chk_is_adm('', '', __FILE__, __LINE__);
        }

        //抓取預設值
        $tad_form_col = !empty($csn) ? self::get(['csn' => $csn]) : [];

        //預設值設定
        $def['ofsn'] = $ofsn;
        $def['max_sort'] = self::max_sort($ofsn);

        if (empty($tad_form_col)) {
            $tad_form_col = $def;
        }

        foreach ($tad_form_col as $key => $value) {
            $value = Tools::filter($key, $value, 'edit', self::$filter_arr);
            $$key = isset($tad_form_col[$key]) ? $tad_form_col[$key] : $def[$key];
            $xoopsTpl->assign($key, $value);
        }

        $op = (!empty($csn)) ? "tad_form_col_update" : "tad_form_col_store";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        $form = Tad_form_main::get(['ofsn' => $ofsn]);
        $xoopsTpl->assign('form', $form);

        //加入Token安全機制
        Tools::token_form();

    }

    //新增資料到 tad_form_col 中
    public static function store($ofsn, $data_arr = [])
    {
        global $xoopsDB;
        if ($ofsn) {
            Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);
        } else {
            Tools::chk_is_adm('', '', __FILE__, __LINE__);
        }

        //XOOPS表單安全檢查
        if (empty($data_arr)) {
            Utility::xoops_security_check(__FILE__, __LINE__);

            $data_arr = $_POST;
        }

        foreach ($data_arr as $key => $value) {
            $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
        }
        $sort = self::max_sort($ofsn);

        $sign_group = Tad_form_main::get(['ofsn' => $ofsn], [], 'read', 'sign_group');
        if (in_array(3, $sign_group) && $kind == "upload") {
            redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_TAD_FORM_CANT_UPLOAD);
        }

        $sql = "INSERT INTO `" . $xoopsDB->prefix("tad_form_col") . "` (
            `ofsn`, `title`, `descript`, `kind`, `size`, `val`, `chk`, `func`, `sort`, `public`
        ) VALUES(
            '{$ofsn}','{$title}','{$descript}','{$kind}','{$size}', '{$val}' , '{$chk}','{$func}','{$sort}','{$public}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        //取得最後新增資料的流水編號
        $csn = $xoopsDB->getInsertId();

        // Tools::get_session(true);
        return $csn;
    }

    //自動取得排序
    public static function max_sort($ofsn = '')
    {
        global $xoopsDB;
        $sql = 'select max(sort) from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn={$ofsn}";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        list($sort) = $xoopsDB->fetchRow($result);

        return ++$sort;
    }

    //更新 Tad_form_col::update() 某一筆資料
    public static function update($ofsn, $where_arr = [], $data_arr = [])
    {
        global $xoopsDB;

        $and = Tools::get_and_where($where_arr);

        if (!empty($data_arr)) {
            if ($ofsn) {
                Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);
            } else {
                Tools::chk_is_adm('', '', __FILE__, __LINE__);
            }

            $col_arr = [];

            foreach ($data_arr as $key => $value) {
                $value = Tools::filter($key, $value, 'write', self::$filter_arr);
                $col_arr[] = "`$key` = '{$value}'";
            }
            $update_cols = implode(', ', $col_arr);
            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_col") . "` SET
            $update_cols WHERE 1 $and";
        } else {
            //XOOPS表單安全檢查
            Utility::xoops_security_check(__FILE__, __LINE__);
            Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);

            foreach ($_POST as $key => $value) {
                $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
            }

            $sign_group = Tad_form_main::get(['ofsn' => $ofsn], [], 'read', 'sign_group');
            if (in_array(3, $sign_group) && $kind == "upload") {
                redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_TAD_FORM_CANT_UPLOAD);
            }

            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_col") . "` SET
            `ofsn` = '{$ofsn}', `title` = '{$title}', `descript` = '{$descript}', `kind` = '{$kind}', `size` = '{$size}', `val` = '{$val}', `chk` = '{$chk}', `func` = '{$func}', `sort` = '{$sort}', `public` = '{$public}'
            WHERE 1 $and";
        }

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        return $ofsn;
    }

    //刪除 Tad_form_col::destroy() 某筆資料資料
    public static function destroy($ofsn = '', $where_arr = [])
    {
        global $xoopsDB;
        Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);

        if (empty($where_arr)) {
            return;
        }
        $destroy_item_arr = self::get_all($ofsn, $where_arr);

        $and = Tools::get_and_where($where_arr);

        $sql = "DELETE FROM `" . $xoopsDB->prefix("tad_form_col") . "`
        WHERE 1 $and";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        foreach ($destroy_item_arr as $destroy_item) {
            Tad_form_value::destroy($ofsn, ['csn' => $destroy_item['csn']]);
        }
    }

}
