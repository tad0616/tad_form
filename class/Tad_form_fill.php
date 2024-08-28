<?php
namespace XoopsModules\Tad_form;

use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\DataList;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\My97DatePicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadUpFiles;
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

class Tad_form_fill
{
    // 過濾用變數的設定
    public static $filter_arr = [
        'int' => ['ssn', 'ofsn', 'uid'], //數字類的欄位
        'html' => [], //含網頁語法的欄位（所見即所得的內容）
        'text' => [], //純大量文字欄位
        'json' => [], //內容為 json 格式的欄位
        'pass' => ['form', 'ans'], //不予過濾的欄位
        'explode' => [], //用分號隔開的欄位
    ];

    //列出所有 Tad_form_fill::index() 資料
    public static function index($ofsn = '', $where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = '', $amount = '')
    {
        global $xoopsTpl;

        if ($amount) {
            if (in_array('analysis', $other_arr)) {
                list($all_tad_form_fill, $analysis, $total, $bar) = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name, null, 'read', $amount);
                $xoopsTpl->assign('analysis', $analysis);
            } else {
                list($all_tad_form_fill, $total, $bar) = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name, null, 'read', $amount);
            }
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('total', $total);
        } else {
            if (in_array('analysis', $other_arr)) {
                list($all_tad_form_fill, $analysis) = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name);
                $xoopsTpl->assign('analysis', $analysis);
            } else {
                $all_tad_form_fill = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name);
            }
        }

        $xoopsTpl->assign('all_tad_form_fill', $all_tad_form_fill);
        $form = Tad_form_main::get(['ofsn' => $ofsn], ['can_view_result', 'col']);
        $xoopsTpl->assign('form', $form);

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_fill_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_fill_destroy&ofsn={$where_arr['ofsn']}&ssn=", 'ssn');
    }

    //取得tad_form_fill所有資料陣列
    public static function get_all($where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = false, $get_value = '', $filter = 'read', $amount = '')
    {
        global $xoopsDB;

        $and_sql = Tools::get_and_where($where_arr);
        $view_col = Tools::get_view_col($view_cols);
        $order_sql = Tools::get_order($order_arr);
        $order = $amount ? '' : $order_sql;

        $sql = "SELECT {$view_col} FROM `" . $xoopsDB->prefix("tad_form_fill") . "` WHERE 1 {$and_sql} {$order}";

        // Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        if ($amount) {
            $PageBar = Utility::getPageBar($sql, $amount, 10, '', '', $_SESSION['bootstrap'], 'none', $order_sql);
            $bar = $PageBar['bar'];
            $sql = $PageBar['sql'];
            $total = $PageBar['total'];
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data_arr = $analysis = $col_arr = [];
        $i = 0;
        if (in_array('analysis', $other_arr) || in_array('all', $other_arr)) {
            $col_arr = Tad_form_col::get_all($where_arr['ofsn'], ['ofsn' => $where_arr['ofsn']], [], [], [], 'csn');
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

            if (in_array('form', $other_arr) || in_array('all', $other_arr)) {
                $data['form'] = Tad_form_main::get(['ofsn' => $data['ofsn']], ['can_view_result', 'can_fill', 'col']);
            }

            if (in_array('ans', $other_arr) || in_array('analysis', $other_arr) || in_array('all', $other_arr)) {
                $data['ans'] = Tad_form_value::get_all($data['ofsn'], ['ssn' => $data['ssn']], [], [], [], 'csn', 'val');
                if (in_array('analysis', $other_arr) || in_array('all', $other_arr)) {
                    foreach ($col_arr as $csn => $col) {
                        if ($col['func'] == "sum") {
                            $analysis[$csn]['sum'] += (int) $data['ans'][$csn];
                        } elseif ($col['func'] == "avg") {
                            $analysis[$csn]['sum'] += (int) $data['ans'][$csn];
                            $analysis[$csn]['count']++;
                        } elseif ($col['func'] == "count") {
                            $val_arr = explode(';', $data['ans'][$csn]);
                            foreach ($val_arr as $value) {
                                $analysis[$csn]['count'][$value]++;
                            }
                        }
                    }
                }
            }

            if (in_array('email_id', $other_arr) || in_array('all', $other_arr)) {
                list($data['email'], $mail) = explode('@', $data['email']);
            }

            $new_key = $key_name ? $data[$key_name] : $i;
            $data_arr[$new_key] = $get_value ? $data[$get_value] : $data;
            $i++;
        }

        if (in_array('analysis', $other_arr) || in_array('all', $other_arr)) {
            foreach ($col_arr as $csn => $col) {
                $analysis[$csn]['title'] = $col['title'];
                $analysis[$csn]['func'] = $col['func'];
                if ($col['func'] == "avg") {
                    $analysis[$csn]['val'] = $analysis[$csn]['sum'] / $analysis[$csn]['count'];
                } elseif ($col['func'] == "sum") {
                    $analysis[$csn]['val'] = $analysis[$csn]['sum'];
                } elseif ($col['func'] == "count") {
                    $analysis[$csn]['val'] = $analysis[$csn]['count'];
                }
            }

            if ($amount) {
                return [$data_arr, $analysis, $total, $bar];
            } else {
                return [$data_arr, $analysis];
            }
        } else {
            if ($amount) {
                return [$data_arr, $total, $bar];
            } else {
                return $data_arr;
            }

        }

    }

    //以流水號秀出某筆 Tad_form_fill::show() 資料內容
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
        $SweetAlert->render('tad_form_fill_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_fill_destroy&ofsn=", 'ofsn');

        $history_fill = [];
        if ($_SESSION['now_user'] && $form['multi_sign']) {
            $history_fill = self::get_all(['ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid']], [], [], ['fill_time' => 'desc']);
            //刪除確認的JS
            $SweetAlert = new SweetAlert();
            $SweetAlert->render('tad_form_fill_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_fill_destroy&ofsn={$ofsn}&ssn=", 'ssn');
        }
        $xoopsTpl->assign('history_fill', $history_fill);

        if ($mode == "return") {
            return $all;
        } elseif ($mode == "assign_all") {
            $xoopsTpl->assign('tad_form_fill', $all);
        } else {
            foreach ($all as $key => $value) {
                $xoopsTpl->assign($key, $value);
            }
        }
    }

    //以流水號取得某筆 tad_form_fill 資料
    public static function get($ofsn, $where_arr = [], $other_arr = [], $filter = 'read', $only_key = '')
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $and_sql = Tools::get_and_where($where_arr);

        $sql = "SELECT * FROM `" . $xoopsDB->prefix("tad_form_fill") . "` WHERE 1 $and_sql";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data = $xoopsDB->fetchArray($result);
        $data = Tools::filter_all_data($filter, $data, self::$filter_arr);

        if (in_array('form', $other_arr) || in_array('all', $other_arr)) {
            $data['form'] = Tad_form_main::get(['ofsn' => $ofsn], ['can_view_result', 'can_fill', 'col', 'ssn' => $data['ssn']]);
        }

        if (in_array('ans', $other_arr) || in_array('all', $other_arr)) {
            $data['ans'] = Tad_form_value::get_all($ofsn, ['ssn' => $data['ssn']], [], [], [], 'csn', 'val');
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

    //tad_form_fill 編輯表單
    public static function create($ofsn = '', $ssn = '', $code = '', $mode = '')
    {
        global $xoopsTpl;
        $form_other = ['can_fill', 'col'];
        if ($ssn) {
            $form_other['ssn'] = $ssn;
        }

        $where_arr['ofsn'] = $ofsn;
        if (!$_SESSION['tad_form_adm'] && !$_SESSION['tad_form_manager']) {
            $where_arr['enable'] = 1;
        }
        $form = Tad_form_main::get($where_arr, $form_other);

        $xoopsTpl->assign('form', $form);

        $where_tad_form_fill = [];
        if ($ssn) {
            $where_tad_form_fill['ssn'] = $ssn;
        }

        if ($_SESSION['now_user']['uid']) {
            $where_tad_form_fill['ssn'] = $ssn;
        } else {
            if ($code) {
                $where_tad_form_fill['code'] = $code;
            }
        }

        $tad_form_fill = self::get($ofsn, ['ssn' => $ssn]);

        //抓取預設值
        // if (!empty($ssn) && $uid) {
        //     $tad_form_fill = self::get($ofsn, ['ssn' => $ssn]);
        // } elseif (!empty($code)) {
        //     $tad_form_fill = self::get($ofsn, ['code' => $code]);
        // } elseif (!empty($ofsn) and !$form['multi_sign']) {
        //     $tad_form_fill = self::get($ofsn, ['ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid']]);
        // } else {
        //     $tad_form_fill = [];
        // }

        if (!$form['can_fill']) {
            if ($mode == 'return') {
                return sprintf(_MD_TAD_FORM_CANT_SIGN, $form['title']);
            } else {
                redirect_header($_SERVER['PHP_SELF'], 3, sprintf(_MD_TAD_FORM_CANT_SIGN, $form['title']));
            }

        }

        //預設值設定
        $def['ofsn'] = $ofsn;

        if (empty($tad_form_fill)) {
            $tad_form_fill = $def;
        }

        foreach ($tad_form_fill as $key => $value) {
            $value = Tools::filter($key, $value, 'edit', self::$filter_arr);
            $$key = isset($tad_form_fill[$key]) ? $tad_form_fill[$key] : $def[$key];
            $xoopsTpl->assign($key, $value);
        }

        $op = (!empty($ofsn)) ? "tad_form_fill_update" : "tad_form_fill_store";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        DataList::render();
        My97DatePicker::render();

        //加入Token安全機制
        Tools::token_form();

        $history_fill = [];
        if ($_SESSION['now_user'] && $form['multi_sign']) {
            $history_fill = self::get_all(['ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid']], [], [], ['fill_time' => 'desc']);
            //刪除確認的JS
            $SweetAlert = new SweetAlert();
            $SweetAlert->render('tad_form_fill_destroy_func', "{$_SERVER['PHP_SELF']}?op=tad_form_fill_destroy&ofsn={$ofsn}&ssn=", 'ssn');
        }
        $xoopsTpl->assign('history_fill', $history_fill);
        $xoopsTpl->assign('man_name', $_SESSION['now_user']['name']);
        $xoopsTpl->assign('email', $_SESSION['now_user']['email']);

    }

    //儲存資料到 Tad_form_fill::save() 中
    public static function save($ofsn, $ssn = '', $data_arr = [])
    {
        global $xoopsDB;
        //XOOPS表單安全檢查
        if (empty($data_arr)) {
            Utility::xoops_security_check(__FILE__, __LINE__);

            $data_arr = $_POST;
        }

        $form = Tad_form_main::get(['ofsn' => $ofsn]);

        if ('1' == $form['captcha']) {
            if ($_SESSION['security_code_' . $ofsn] != $data_arr['security_images_' . $ofsn] or empty($data_arr['security_images_' . $ofsn])) {
                redirect_header($_SERVER['PHP_SELF'] . "?op=tad_form_fill_create&ofsn=$ofsn", 3, _MD_TAD_FORM_CAPTCHA_ERROR);
            }

            unset($_SESSION['security_code_' . $ofsn]);
        }

        foreach ($data_arr as $key => $value) {
            $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
        }

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        // 不允許多次填寫時
        if ($form['multi_sign'] != 1) {
            // 不然訪客會重複填
            if ($_SESSION['now_user']['uid'] > 0) {
                $ssn = self::get($ofsn, ['ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid']], [], 'read', 'ssn');
            }

            if ($ssn) {
                self::update(['ssn' => $ssn], ['uid' => $_SESSION['now_user']['uid'], 'man_name' => $man_name, 'email' => $email, 'fill_time' => $now]);
            } else {
                $code = md5("{$ofsn}{$_SESSION['now_user']['uid']}{$man_name}{$email}{$now}");
                $ssn = self::store(['ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid'], 'man_name' => $man_name, 'email' => $email, 'fill_time' => $now, 'code' => $code]);
            }
        } else {
            if ($ssn) {
                self::update(['ssn' => $ssn], ['uid' => $_SESSION['now_user']['uid'], 'man_name' => $man_name, 'email' => $email, 'fill_time' => $now]);
            } else {
                $code = md5("{$ofsn}{$_SESSION['now_user']['uid']}{$man_name}{$email}{$now}");
                $ssn = self::store(['ssn' => $ssn, 'ofsn' => $ofsn, 'uid' => $_SESSION['now_user']['uid'], 'man_name' => $man_name, 'email' => $email, 'fill_time' => $now, 'code' => $code], 'REPLACE');
            }

        }

        $ssn = (int) $ssn;
        //再存填寫資料
        $data_arr = [];
        foreach ($ans as $csn => $val) {
            if ($val == "upload{$csn}") {
                $TadUpFiles = new TadUpFiles("tad_form", "/{$ofsn}/{$csn}");
                $TadUpFiles->set_col('ofsn-csn-ssn', "{$ofsn}-{$csn}-{$ssn}");
                $TadUpFiles->upload_file("upload{$csn}", 1980, 480, '', '', true);
                $files = $TadUpFiles->get_file();
                $value = "upload{$csn}";
            } else {
                $value = (is_array($val)) ? implode(';', $val) : $val;
            }

            $value = $xoopsDB->escape($value);

            $data_arr[$csn] = "('{$ssn}', '{$csn}', '{$value}')";
            unset($need_csn[$csn]);
        }

        Tad_form_value::store($data_arr, 'REPLACE', true);

        //把一些沒填的欄位也補上空值
        $data_arr2 = [];
        foreach ($need_csn as $csn) {
            $data_arr2[$csn] = "('{$ssn}', '{$csn}', '')";
        }
        Tad_form_value::store($data_arr2, 'REPLACE', true);

        $code = self::get($ofsn, ['ssn' => $ssn], [], 'read', 'code');

        return [$ssn, $code];
    }

    //新增資料到 tad_form_fill 中
    public static function store($data_arr = [], $insert = "INSERT")
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        if (empty($data_arr)) {
            Utility::xoops_security_check(__FILE__, __LINE__);

            $data_arr = $_POST;
        }

        foreach ($data_arr as $key => $value) {
            $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
        }

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

        $sql = "{$insert} INTO `" . $xoopsDB->prefix("tad_form_fill") . "` (
            `ofsn`, `uid`, `man_name`, `email`, `fill_time`, `result_col`, `code`
        ) VALUES(
            '{$ofsn}','{$_SESSION['now_user']['uid']}', '{$man_name}' , '{$email}','{$now}','{$result_col}','{$code}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        //取得最後新增資料的流水編號
        $ssn = $xoopsDB->getInsertId();
        return $ssn;
    }

    //更新tad_form_fill某一筆資料
    public static function update($where_arr = [], $data_arr = [])
    {
        global $xoopsDB;

        $and = Tools::get_and_where($where_arr);

        if (!empty($data_arr)) {

            $col_arr = [];

            foreach ($data_arr as $key => $value) {
                $value = Tools::filter($key, $value, 'write', self::$filter_arr);
                $col_arr[] = "`$key` = '{$value}'";
            }
            $update_cols = implode(', ', $col_arr);
            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_fill") . "` SET
            $update_cols WHERE 1 $and";
        } else {
            //XOOPS表單安全檢查
            Utility::xoops_security_check(__FILE__, __LINE__);

            foreach ($_POST as $key => $value) {
                $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
            }

            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_fill") . "` SET
            `ofsn` = '{$ofsn}', `uid` = '{$_SESSION['now_user']['uid']}', `man_name` = '{$man_name}', `email` = '{$email}', `fill_time` = '{$now}', `result_col` = '{$result_col}', `code` = '{$code}',
            WHERE 1 $and";
        }

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        // Tools::get_session(true);

        return $where_arr['ofsn'];
    }

    //刪除 Tad_form_fill::destroy()某筆資料資料
    public static function destroy($ofsn = '', $where_arr = [])
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            return;
        }

        $destroy_item_arr = self::get_all($where_arr);

        $and = Tools::get_and_where($where_arr);

        $sql = "DELETE FROM `" . $xoopsDB->prefix("tad_form_fill") . "`
        WHERE 1 $and";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        foreach ($destroy_item_arr as $destroy_item) {
            Tad_form_value::destroy($destroy_item['ofsn'], ['ssn' => $destroy_item['ssn']]);
        }
    }

    //寄信 Tad_form_fill::mail() 界面
    public static function mail($ofsn = '')
    {
        global $xoopsTpl;
        $form = Tad_form_main::get(['ofsn' => $ofsn], ['col', 'all_apply']);
        $xoopsTpl->assign('form', $form);

        $CkEditor = new CkEditor('tad_form', 'content', '');
        $CkEditor->setHeight(350);
        $editor = $CkEditor->render();
        $xoopsTpl->assign('editor', $editor);

    }

    // Tad_form_fill::send() 寄發信件
    public static function send($ofsn = '', $email_ssn = [], $title = '', $content = '', $send_test = 0)
    {

        global $xoopsTpl;
        $xoopsMailer = getMailer();
        $xoopsMailer->multimailer->ContentType = 'text/html';
        $xoopsMailer->addHeaders('MIME-Version: 1.0');

        $form = Tad_form_main::get(['ofsn' => $ofsn], ['col', 'all_apply']);
        $xoopsTpl->assign('form', $form);

        $CkEditor = new CkEditor('tad_form', 'content', $content);
        $CkEditor->setHeight(350);
        $editor = $CkEditor->render();
        $xoopsTpl->assign('editor', $editor);

        $email_arr = explode(';', $form['adm_email']);
        $xoopsMailer->setFromEmail($email_arr[0]);

        $i = 0;
        $mail_test = [];
        $mail_rersult = '';
        // Utility::dd($form);
        foreach ($email_ssn as $ssn => $mail) {
            $new_content = str_replace('{name}', $form['all_apply'][$ssn]['man_name'], $content);
            foreach ($form['col'] as $csn => $col) {
                $new_content = str_replace("{{$col['title']}}", $form['all_apply'][$ssn]['ans'][$csn], $new_content);
            }

            if ($send_test) {
                $mail_test[$i]['mail'] = $mail;
                $mail_test[$i]['title'] = $title;
                $mail_test[$i]['content'] = $new_content;
                $i++;
            } else {
                if ($xoopsMailer->sendMail($mail, $title, $new_content, [])) {
                    $mail_rersult .= "{$mail} " . _MD_TAD_FORM_SEND_OK . '<br>';
                } else {
                    $mail_rersult .= "{$mail} " . _MD_TAD_FORM_SEND_ERROR . '<br>';
                }
            }
        }

        if ($send_test) {
            $xoopsTpl->assign('mail_test', $mail_test);
        } else {
            redirect_header($_SERVER['PHP_SELF'], 3, $mail_rersult);
        }

    }
}
