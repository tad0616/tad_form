<?php
namespace XoopsModules\Tad_form;

use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
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

class Tad_form_value
{
    // 過濾用變數的設定
    public static $filter_arr = [
        'int' => ['ssn', 'csn'], //數字類的欄位
        'html' => [], //含網頁語法的欄位（所見即所得的內容）
        'text' => [], //純大量文字欄位
        'json' => [], //內容為 json 格式的欄位
        'pass' => ['val'], //不予過濾的欄位
        'explode' => [], //用分號隔開的欄位
    ];

    //列出所有 Tad_form_value::index() 資料
    public static function index($ofsn, $where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = '', $amount = '')
    {
        global $xoopsTpl;

        if ($amount) {
            list($all_tad_form_value, $total, $bar) = self::get_all($ofsn, $where_arr, $other_arr, $view_cols, $order_arr, $key_name, null, 'read', $amount);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('total', $total);
        } else {
            $all_tad_form_value = self::get_all($ofsn, $where_arr, $other_arr, $view_cols, $order_arr, $key_name);
        }

        $xoopsTpl->assign('all_tad_form_value', $all_tad_form_value);

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_value_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_value_destroy&ofsn=", 'ofsn');
    }

    //取得 Tad_form_value::get_all() 所有資料陣列
    public static function get_all($ofsn = "", $where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = false, $get_value = '', $filter = 'read', $amount = '')
    {
        global $xoopsDB;

        $and_sql = Tools::get_and_where($where_arr);
        $view_col = Tools::get_view_col($view_cols);
        $order_sql = Tools::get_order($order_arr);
        $order = $amount ? '' : $order_sql;

        $sql = "SELECT {$view_col} FROM `" . $xoopsDB->prefix("tad_form_value") . "` WHERE 1 {$and_sql} {$order}";
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

            if ($data['val'] == "upload{$data['csn']}") {
                $TadUpFiles = new TadUpFiles("tad_form", "/{$ofsn}/{$data['csn']}");
                $TadUpFiles->set_col('ofsn-csn-ssn', "{$ofsn}-{$data['csn']}-{$data['ssn']}");
                $data['val'] = $TadUpFiles->show_files("upload{$data['csn']}", true, 'filename', true, false);
            }

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

    //以流水號秀出某筆 Tad_form_value::show() 資料內容
    public static function show($ofsn, $where_arr = [], $other_arr = [], $mode = '')
    {
        global $xoopsTpl;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $all = self::get($ofsn, $where_arr, $other_arr);

        if (empty($all)) {
            return false;
        }

        foreach ($all as $key => $value) {
            $value = Tools::filter($key, $value, 'read', self::$filter_arr);
            $all[$key] = $value;
            $$key = $value;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_value_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_value_destroy&ofsn=", 'ofsn');

        if ($mode == "return") {
            return $all;
        } elseif ($mode == "assign_all") {
            $xoopsTpl->assign('tad_form_value', $all);
        } else {
            foreach ($all as $key => $value) {
                $xoopsTpl->assign($key, $value);
            }
        }
    }

    //以流水號取得某筆  Tad_form_value::get()  資料
    public static function get($ofsn, $where_arr = [], $other_arr = [], $filter = 'read', $only_key = '')
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $and_sql = Tools::get_and_where($where_arr);

        $sql = "SELECT * FROM `" . $xoopsDB->prefix("tad_form_value") . "` WHERE 1 $and_sql";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data = $xoopsDB->fetchArray($result);
        $data = Tools::filter_all_data($filter, $data, self::$filter_arr);

        if ($data['val'] == "upload{$data['csn']}") {
            $TadUpFiles = new TadUpFiles("tad_form", "/{$ofsn}/{$data['csn']}");
            $TadUpFiles->set_col('ofsn-csn-ssn', "{$ofsn}-{$data['csn']}-{$data['ssn']}");
            $data['val'] = $TadUpFiles->show_files("upload{$data['csn']}", true, 'filename', true, false);
        }

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

    //tad_form_value 編輯表單
    public static function create($col = [], $ssn = '', $db_ans = [])
    {
        foreach ($col as $k => $v) {
            $$k = $v;
        }
        $default_val = isset($val) ? $val : '';
        $kind = isset($kind) ? $kind : '';

        $main = '';
        switch ($kind) {
            case 'text':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt = ($chk) ? ' validate[required]' : '';
                $span = empty($size) ? 12 : round($size / 10, 0);
                $main = "
                <div class='col-sm-{$span}'>
                    <label for='tf{$csn}' style='display:none;'>{$csn}</label>
                    <input type='text' name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}' value='{$default_val}'>
                    <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>
                </div>";
                break;

            case 'radio':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $opt = explode(';', $size);
                $i = 1;
                $main = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                foreach ($opt as $val) {
                    $checked = ($default_val == $val) ? 'checked' : '';
                    $chktxt = ($chk) ? "validate[required] radio" : '';

                    $main .= "
                        <div class='form-check-inline radio-inline'>
                            <label class='form-check-label' for='inlineRadio{$csn}-{$i}'>
                                <input class='form-check-input $chktxt' type='radio' name='ans[$csn]' id='inlineRadio{$csn}-{$i}' value='{$val}' $checked>
                                {$val}
                            </label>
                        </div>";
                    $i++;
                }
                break;

            case 'checkbox':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $db = explode(';', $default_val);

                $opt = explode(';', $size);
                $i = 1;
                $main = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";

                foreach ($opt as $val) {
                    $checked = (in_array($val, $db)) ? "checked='checked'" : '';
                    $chktxt = ($chk) ? "validate[required] checkbox" : '';

                    $main .= "
                        <div class='form-check-inline checkbox-inline'>
                            <label class='form-check-label' for='inlineCheckbox{$csn}-{$i}'>
                                <input type='checkbox' name='ans[$csn][]' id='inlineCheckbox{$csn}-{$i}' value='{$val}' $checked $chktxt>
                                {$val}
                            </label>
                        </div>";

                    $i++;
                }
                break;

            case 'select':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt = ($chk) ? 'validate[required]' : '';
                $opt = explode(';', $size);
                $main = "<label for='tf{$csn}' style='display:none;'>{$csn}</label><select name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}'>";
                foreach ($opt as $val) {
                    $selected = ($default_val == $val) ? 'selected' : '';
                    $main .= "<option value='{$val}' $selected>{$val}</option>";
                }
                $main .= "</select><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;
            case 'textarea':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt = ($chk) ? 'validate[required]' : '';
                if (empty($size)) {
                    $size = 60;
                }

                $main = "<label for='tf{$csn}' style='display:none;'>{$csn}</label><textarea name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}' style='height:{$size}px;'>{$default_val}</textarea><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";

                break;

            case 'upload':
                // $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $span = empty($size) ? 6 : round($size / 10, 0);
                $TadUpFiles = new TadUpFiles("tad_form", "/{$ofsn}/{$csn}");
                if ($chk and empty($db_ans)) {
                    $TadUpFiles->set_var('require', true);
                }
                $TadUpFiles->set_var("show_tip", false);
                $TadUpFiles->set_col('ofsn-csn-ssn', "{$ofsn}-{$csn}-{$ssn}");
                $up_form = $TadUpFiles->upform('list', "upload{$csn}", '', true, $size);

                $main = "<label for='tf{$csn}' style='display:none;'>{$csn}</label>$up_form
                <input type='hidden' name='ans[{$csn}]' value='upload{$csn}'>
                <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;

            case 'date':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $span = empty($size) ? 6 : round($size / 10, 0);
                $chktxt = ($chk) ? 'validate[required]' : '';
                $main = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}' class='form-control {$chktxt}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})\"></div>
                <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;
            case 'datetime':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $span = empty($size) ? 6 : round($size / 10, 0);
                $chktxt = ($chk) ? 'validate[required]' : '';
                $main = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}'  class='form-control {$chktxt}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm' , startDate:'%y-%M-%d %H:%m}'})\"></div>
                <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;
            case 'show':
                $main = '';
                break;
        }
        return $main;
    }

    //新增資料到 Tad_form_value::store() 中
    public static function store($data_arr = [], $insert = 'INSERT', $batch = false)
    {
        global $xoopsDB;

        if ($data_arr) {
            if ($batch) {
                $values = implode(',', $data_arr);
                $sql = "{$insert} INTO `" . $xoopsDB->prefix("tad_form_value") . "` (
                `ssn`, `csn`, `val`
                ) VALUES{$values}";
            } else {
                foreach ($data_arr as $key => $value) {
                    $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
                }

                $sql = "{$insert} INTO `" . $xoopsDB->prefix("tad_form_value") . "` (
                `ssn`, `csn`, `val`
                ) VALUES(
                    '{$ssn}','{$csn}','{$val}'
                )";
            }
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        }
    }

    //刪除 Tad_form_value::destroy() 某筆資料資料
    public static function destroy($ofsn = '', $where_arr = [])
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            return;
        }

        $destroy_item_arr = self::get_all($ofsn, $where_arr);

        $and = Tools::get_and_where($where_arr);

        $sql = "DELETE FROM `" . $xoopsDB->prefix("tad_form_value") . "`
        WHERE 1 $and";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        foreach ($destroy_item_arr as $destroy_item) {
            $TadUpFiles = new TadUpFiles("tad_form", "/$ofsn/{$destroy_item['csn']}");
            if ($destroy_item['val'] == "upload{$destroy_item['csn']}") {
                $TadUpFiles->set_col('ofsn-csn-ssn', "{$ofsn}-{$destroy_item['val']}-{$destroy_item['ssn']}");
                $TadUpFiles->del_files();
            }
        }

    }

}
