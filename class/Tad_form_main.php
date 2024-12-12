<?php
namespace XoopsModules\Tad_form;

use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\DataList;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\My97DatePicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
use XoopsModules\Tad_form\Tad_form_col;
use XoopsModules\Tad_form\Tad_form_fill;
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

class Tad_form_main
{
    // 過濾用變數的設定
    public static $filter_arr = [
        'int' => ['ofsn', 'uid', 'enable', 'captcha', 'show_result', 'multi_sign'], //數字類的欄位
        'html' => ['content'], //含網頁語法的欄位（所見即所得的內容）
        'text' => [], //純大量文字欄位
        'json' => [], //內容為 json 格式的欄位
        'pass' => [], //不予過濾的欄位
        'explode' => [], //用分號隔開的欄位
    ];

    //列出所有 Tad_form_main::index() 資料
    public static function index($where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = '', $amount = '')
    {
        global $xoopsTpl;

        if ($amount) {
            list($all_tad_form_main, $total, $bar) = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name, null, 'read', $amount);
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('total', $total);
        } else {
            $all_tad_form_main = self::get_all($where_arr, $other_arr, $view_cols, $order_arr, $key_name);
        }

        Utility::test($all_tad_form_main, 'all_tad_form_main', 'dd');
        $xoopsTpl->assign('all_tad_form_main', $all_tad_form_main);

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('tad_form_main_destroy_func', "manager.php?op=tad_form_main_destroy&ofsn=", 'ofsn');
    }

    //取得tad_form_main所有資料陣列
    public static function get_all($where_arr = [], $other_arr = [], $view_cols = [], $order_arr = [], $key_name = false, $get_value = '', $filter = 'read', $amount = '')
    {
        global $xoopsDB, $xoopsUser;

        if (in_array('fill_count', $other_arr) || in_array('all', $other_arr)) {
            $fill_count = Tad_form_fill::get_all([], [], '`ofsn`, count(*) as fill_count', ['before order' => 'GROUP BY `ofsn`'], 'ofsn', 'fill_count');
        }

        if (in_array('col_count', $other_arr) || in_array('all', $other_arr)) {
            $col_count = Tad_form_col::get_all('', [], [], '`ofsn`, count(*) as col_count', ['before order' => 'GROUP BY `ofsn`'], 'ofsn', 'col_count');
        }

        if (in_array('can_fill', $other_arr) || in_array('can_view_result', $other_arr)) {
            $user_groups = $xoopsUser ? $xoopsUser->getGroups() : [3];
        }
        if (in_array('sign_group_title', $other_arr) || in_array('view_result_group_title', $other_arr) || in_array('all', $other_arr)) {
            $group_title = Utility::get_all_groups();
        }

        $and_sql = Tools::get_and_where($where_arr);
        $view_col = Tools::get_view_col($view_cols);
        $order_sql = Tools::get_order($order_arr);
        $order = $amount ? '' : $order_sql;
        $sql = "SELECT {$view_col} FROM `" . $xoopsDB->prefix("tad_form_main") . "` WHERE 1 {$and_sql} {$order}";

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

            $data['sign_group'] = $data['sign_group'] ? explode(',', $data['sign_group']) : [1, 2];
            $data['view_result_group'] = $data['view_result_group'] ? explode(',', $data['view_result_group']) : [1];
            $data['ofsn'] = isset($data['ofsn']) ? $data['ofsn'] : 0;

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

            if (in_array('sign_group_title', $other_arr) || in_array('all', $other_arr)) {
                foreach ($data['sign_group'] as $group_id) {
                    $data['sign_group_title'][$group_id] = $group_title[$group_id];
                }
            }

            if (in_array('view_result_group_title', $other_arr) || in_array('all', $other_arr)) {
                foreach ($data['view_result_group'] as $group_id) {
                    $data['view_result_group_title'][$group_id] = $group_title[$group_id];
                }
            }

            if (in_array('col_count', $other_arr) || in_array('all', $other_arr)) {
                $data['col_count'] = $col_count[$data['ofsn']];
            }

            if (in_array('fill_count', $other_arr) || in_array('all', $other_arr)) {
                $data['fill_count'] = isset($fill_count[$data['ofsn']]) ? $fill_count[$data['ofsn']] : 0;
            }

            if (in_array('can_fill', $other_arr) || in_array('all', $other_arr)) {
                $data['can_fill'] = array_intersect($user_groups, $data['sign_group']);
            }

            if (in_array('can_view_result', $other_arr) || in_array('all', $other_arr)) {
                $data['can_view_result'] = array_intersect($user_groups, $data['view_result_group']);
            }

            if (!empty($data) && (in_array('files', $other_arr) || in_array('all', $other_arr))) {
                $TadUpFiles = new TadUpFiles("tad_form");
                $TadUpFiles->set_dir('subdir', '/' . $data['ofsn']);
                $TadUpFiles->set_col("ofsn", $data['ofsn']);
                $data['files'] = $TadUpFiles->show_files('ofsn', true, 'filename', true, false);
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

    //以流水號秀出某筆 tad_form_main 資料內容
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
        $SweetAlert->render('tad_form_main_destroy_func', "manager.php??op=tad_form_main_destroy&ofsn=", 'ofsn');

        if ($mode == "return") {
            return $all;
        } elseif ($mode == "assign_all") {
            $xoopsTpl->assign('tad_form_main', $all);
        } else {
            foreach ($all as $key => $value) {
                $xoopsTpl->assign($key, $value);
            }
        }
    }

    //以流水號取得某筆 Tad_form_main::get() 資料
    public static function get($where_arr = [], $other_arr = [], $filter = 'read', $only_key = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($where_arr)) {
            redirect_header('index.php', 3, _MD_TAD_FORM_NO_CONDITIONS . __FILE__ . __LINE__);
        }

        $and_sql = Tools::get_and_where($where_arr);

        $sql = "SELECT * FROM `" . $xoopsDB->prefix("tad_form_main") . "` WHERE 1 $and_sql";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);
        $data = $xoopsDB->fetchArray($result);
        $data = Tools::filter_all_data($filter, $data, self::$filter_arr);

        if (in_array('sign_group_title', $other_arr) || in_array('view_result_group_title', $other_arr) || in_array('all', $other_arr)) {
            $group_title = Utility::get_all_groups();
        }

        $data['sign_group'] = $data['sign_group'] ? explode(',', $data['sign_group']) : [1, 2];
        $data['view_result_group'] = $data['view_result_group'] ? explode(',', $data['view_result_group']) : [1];

        if (in_array('sign_group_title', $other_arr) || in_array('all', $other_arr)) {
            foreach ($data['sign_group'] as $group_id) {
                $data['sign_group_title'][$group_id] = $group_title[$group_id];
            }
        }

        if (in_array('view_result_group_title', $other_arr) || in_array('all', $other_arr)) {
            foreach ($data['view_result_group'] as $group_id) {
                $data['view_result_group_title'][$group_id] = $group_title[$group_id];
            }
        }

        if ($data['kind'] == 'application') {
            $data['all_apply'] = Tad_form_fill::get_all(['ofsn' => $data['ofsn'], 'result_col' => 1], ['email_id'], ['email', 'fill_time']);
        }

        if (in_array('all_apply', $other_arr)) {
            $data['all_apply'] = Tad_form_fill::get_all(['ofsn' => $data['ofsn']], ['ans'], [], [], 'ssn');
        }

        if (in_array('col', $other_arr) || in_array('all', $other_arr)) {
            $tad_form_col_other_arr = !empty($other_arr['ssn']) ? ['ssn' => $other_arr['ssn']] : [];
            $data['col'] = Tad_form_col::get_all($data['ofsn'], ['ofsn' => $data['ofsn']], $tad_form_col_other_arr, [], ['sort' => 'asc'], 'csn');
        }

        if (in_array('fill_count', $other_arr) || in_array('all', $other_arr)) {
            $fill_count = Tad_form_fill::get_all(['ofsn' => $data['ofsn']], [], 'count(*) as fill_count', [], '', 'fill_count');
            $data['fill_count'] = (int) $fill_count[0];
        }

        $user_groups = $xoopsUser ? $xoopsUser->getGroups() : [3];
        if (in_array('can_view_result', $other_arr) || in_array('all', $other_arr)) {
            $data['can_view_result'] = array_intersect($user_groups, $data['view_result_group']);
        }

        if (in_array('can_fill', $other_arr) || in_array('all', $other_arr)) {
            $data['can_fill'] = array_intersect($user_groups, $data['sign_group']);
        }

        if (!empty($data) && (in_array('files', $other_arr) || in_array('all', $other_arr))) {
            $TadUpFiles = new TadUpFiles("tad_form");
            $TadUpFiles->set_dir('subdir', '/' . $data['ofsn']);
            $TadUpFiles->set_col("ofsn", $data['ofsn']);
            $data['files'] = $TadUpFiles->show_files('ofsn', true, 'filename', true, false);
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

    //tad_form_main 編輯表單
    public static function create($ofsn = '')
    {
        global $xoopsTpl;
        if ($ofsn) {
            Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);
        } else {
            Tools::chk_is_adm('tad_form_manager', '', __FILE__, __LINE__);
        }

        //抓取預設值
        $tad_form_main = (!empty($ofsn)) ? self::get(['ofsn' => $ofsn], ['form']) : [];

        //預設值設定
        $def['ofsn'] = $ofsn;
        $def['enable'] = '1';
        $def['start_date'] = date('Y-m-d H:i:00');
        $def['end_date'] = date('Y-m-d H:i:00', strtotime('+7 days'));
        $def['sign_group'] = [1, 2];
        $def['enable'] = '1';
        $def['adm_email'] = $_SESSION['now_user']['email'];
        $def['show_result'] = '0';
        $def['view_result_group'] = [1];
        $def['multi_sign'] = '0';

        if (empty($tad_form_main)) {
            $tad_form_main = $def;
        }

        $content = '';
        foreach ($tad_form_main as $key => $value) {
            $value = Tools::filter($key, $value, 'edit', self::$filter_arr);
            $$key = isset($tad_form_main[$key]) ? $tad_form_main[$key] : $def[$key];
            $xoopsTpl->assign($key, $value);
        }

        $op = (!empty($ofsn)) ? "tad_form_main_update" : "tad_form_main_store";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        DataList::render();
        My97DatePicker::render();

        $ck = new CkEditor("tad_form", "content", $content);
        $ck->setHeight(200);
        $editor = $ck->render();
        $xoopsTpl->assign('content_editor', $editor);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $SelectGroup_name = new \XoopsFormSelectGroup('', 'sign_group', true, $sign_group, 3, true);
        $SelectGroup_name->setExtra("class='form-control'");
        $sign_group_form = $SelectGroup_name->render();
        $xoopsTpl->assign('sign_group_form', $sign_group_form);

        $SelectGroup_name2 = new \XoopsFormSelectGroup('', 'view_result_group', true, $view_result_group, 3, true);
        $SelectGroup_name2->setExtra("class='form-control'");
        $view_result_group_form = $SelectGroup_name2->render();
        $xoopsTpl->assign('view_result_group_form', $view_result_group_form);

        //加入Token安全機制
        Utility::token_form();

        //上傳表單
        $TadUpFiles = new TadUpFiles("tad_form");
        $TadUpFiles->set_dir('subdir', '/' . $ofsn);
        $TadUpFiles->set_var("show_tip", false);
        $TadUpFiles->set_col("ofsn", $ofsn);
        $tad_form_upform = $TadUpFiles->upform(true, "ofsn", "", true, '.pdf,.jpg,.png,.tiff,.gif,.doc,.docx,.odt', false);
        $xoopsTpl->assign('tad_form_upform', $tad_form_upform);
        // $rule = $TadUpFiles->show_files("rule", false, "filename", false, false, null, null, true);

        // $rule = $TadUpFiles->list_del_file('list', true, [], true, false);

        // $xoopsTpl->assign('rule', $rule);
    }

    //新增資料到 tad_form_main 中
    public static function store($data_arr = [])
    {
        global $xoopsDB, $xoopsUser;
        Tools::chk_is_adm('tad_form_manager', '', __FILE__, __LINE__);

        //XOOPS表單安全檢查
        if (empty($data_arr)) {
            Utility::xoops_security_check(__FILE__, __LINE__);

            $data_arr = $_POST;
        }

        foreach ($data_arr as $key => $value) {
            $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
        }
        $uid = $xoopsUser->uid();
        if (in_array(3, $sign_group)) {
            $multi_sign = 0;
        }
        if (isset($sign_group) && \is_array($sign_group)) {
            $sign_group = implode(',', $sign_group);
        }
        if (isset($view_result_group) && \is_array($view_result_group)) {
            $view_result_group = implode(',', $view_result_group);
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_form_main') . '` (
            `title`, `start_date`, `end_date`, `content`, `uid`, `post_date`, `enable`, `sign_group`, `kind`, `adm_email`, `captcha`, `show_result`, `view_result_group`, `multi_sign`
        ) VALUES (
            ?, ?, ?, ?, ?, now(), ?, ?, ?, ?, ?, ?, ?, ?
        )';
        Utility::query($sql, 'ssssissssssss', [$title, $start_date, $end_date, $content, $uid, $enable, $sign_group, $kind, $adm_email, $captcha, $show_result, $view_result_group, $multi_sign]) or Utility::web_error($sql, __FILE__, __LINE__, true);

        //取得最後新增資料的流水編號
        $ofsn = $xoopsDB->getInsertId();

        $TadUpFiles = new TadUpFiles("tad_form");
        $TadUpFiles->set_dir('subdir', '/' . $ofsn);
        $TadUpFiles->set_col("ofsn", $ofsn);
        $TadUpFiles->upload_file('ofsn', '', '', '', '', true);

        Tools::get_session(true);
        return $ofsn;
    }

    //更新tad_form_main某一筆資料
    public static function update($where_arr = [], $data_arr = [])
    {
        global $xoopsDB;

        $and = Tools::get_and_where($where_arr);

        if (!empty($data_arr)) {
            Tools::chk_is_adm('my_form', $where_arr['ofsn'], __FILE__, __LINE__);

            $col_arr = [];

            foreach ($data_arr as $key => $value) {
                $value = Tools::filter($key, $value, 'write', self::$filter_arr);
                $col_arr[] = "`$key` = '{$value}'";
            }
            $update_cols = implode(', ', $col_arr);
            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_main") . "` SET
            $update_cols WHERE 1 $and";
        } else {
            //XOOPS表單安全檢查
            Utility::xoops_security_check(__FILE__, __LINE__);

            Tools::chk_is_adm('my_form', $where_arr['ofsn'], __FILE__, __LINE__);

            foreach ($_POST as $key => $value) {
                $$key = Tools::filter($key, $value, 'write', self::$filter_arr);
            }

            if (in_array(3, $sign_group)) {
                $multi_sign = 0;
            }

            if (isset($sign_group) && \is_array($sign_group)) {
                $sign_group = implode(',', $sign_group);
            }
            if (isset($view_result_group) && \is_array($view_result_group)) {
                $view_result_group = implode(',', $view_result_group);
            }

            $sql = "UPDATE `" . $xoopsDB->prefix("tad_form_main") . "` SET
            `title` = '{$title}', `start_date` = '{$start_date}', `end_date` = '{$end_date}', `content` = '{$content}', `post_date` = now(), `enable` = '{$enable}', `sign_group` = '{$sign_group}', `kind` = '{$kind}',`adm_email` = '{$adm_email}',`show_result` = '{$show_result}',`captcha` = '{$captcha}',`view_result_group` = '{$view_result_group}',`multi_sign` = '{$multi_sign}'
            WHERE 1 $and";
        }

        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__, true);

        $TadUpFiles = new TadUpFiles("tad_form");
        $TadUpFiles->set_dir('subdir', '/' . $where_arr['ofsn']);
        $TadUpFiles->set_col("ofsn", $where_arr['ofsn']);
        $TadUpFiles->upload_file('ofsn', '', '', '', '', true);

        Tools::get_session(true);

        return $where_arr['ofsn'];
    }

    //刪除 Tad_form_main::destroy() 某筆資料資料
    public static function destroy($ofsn, $where_arr = [])
    {
        global $xoopsDB;

        if (empty($where_arr)) {
            return;
        }

        $destroy_item_arr = self::get_all($where_arr);

        foreach ($destroy_item_arr as $destroy_item) {
            if (Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__, 'return')) {
                $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_form_main') . '` WHERE `ofsn`=?';
                Utility::query($sql, 'i', [$destroy_item['ofsn']]) or Utility::web_error($sql, __FILE__, __LINE__, true);

                Tad_form_fill::destroy($destroy_item['ofsn'], ['ofsn' => $destroy_item['ofsn']]);
                Tad_form_col::destroy($destroy_item['ofsn'], ['ofsn' => $destroy_item['ofsn']]);
            }
        }
    }

    //複製表單
    public static function copy($ofsn = '')
    {
        //讀出原有資料
        $form = self::get(['ofsn' => $ofsn]);
        $form['post_date'] = date('Y-m-d H:i:s');
        $form['title'] = "copy_{$form['title']}";
        $form['enable'] = 0;
        $form['start_date'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        $form['end_date'] = date('Y-m-d H:i:s', strtotime('+1 week'));
        $form['content'] = Wcag::amend($form['content']);
        $new_ofsn = self::store($form);

        $cols = Tad_form_col::get_all($ofsn, ['ofsn' => $ofsn], [], [], ['sort' => 'asc']);
        foreach ($cols as $key => $col) {
            $col['ofsn'] = $new_ofsn;
            Tad_form_col::store($new_ofsn, $col, false);
        }
    }
}
