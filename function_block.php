<?php

use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Utility;
//填寫表單
if (!function_exists('sign_form')) {
    function sign_form($ofsn = '', $ssn = '', $mode = '')
    {
        global $xoopsDB, $xoopsUser, $xoopsTpl, $xoTheme;

        $xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_form/css/module.css');
        $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $form = get_tad_form_main($ofsn, $ssn);
        $ofsn = (int) $form['ofsn'];

        $modhandler = xoops_gethandler('module');
        $xoopsModule = $modhandler->getByDirname("tad_form");

        $sign_group = (empty($form['sign_group'])) ? '' : explode(',', $form['sign_group']);

        if ($xoopsUser) {
            if (!isset($_SESSION['tad_form_adm'])) {
                $module_id = $xoopsModule->mid();
                $_SESSION['tad_form_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin($module_id) : false;
            }

            $email = $xoopsUser->email();

            $User_Groups = $xoopsUser->getGroups();
            $ugroup = implode(',', $User_Groups);

            if (!empty($sign_group) and !in_array(1, $User_Groups)) {
                $ok = false;
                foreach ($sign_group as $group) {
                    if (in_array($group, $User_Groups)) {
                        $ok = true;
                    }
                }
                if (!$ok) {
                    if ('return' === $mode) {
                        $msg['op'] = 'error';
                        $msg['title'] = $form['title'];
                        $msg['msg'] = _MD_TADFORM_ONLY_MEM;
                        return $msg;
                    } else {
                        $xoopsTpl->assign('op', 'error');
                        $xoopsTpl->assign('title', $form['title']);
                        $xoopsTpl->assign('msg', _MD_TADFORM_ONLY_MEM);
                        return;
                    }

                }
            }

            $uid = $xoopsUser->uid();
            $uid_name = $xoopsUser->name();
            if (empty($uid_name)) {
                $uid_name = $xoopsUser->uname();
            }

            if (empty($uid_name)) {
                $uid_name = $xoopsUser->uname();
            }

            if ($ssn) {
                $db_ans = get_somebody_ans($ofsn, $uid, $ssn);
            } else {
                $db_ans = ('1' == $form['multi_sign']) ? [] : get_somebody_ans($ofsn, $uid, $ssn);
            }
            $history = ('1' == $form['multi_sign']) ? get_history($ofsn, $uid) : '';
        } else {
            $uid_name = '';
            $email = $history = '';
            $_SESSION['tad_form_adm'] = false;
            $db_ans = [];
            if (!empty($sign_group) and !in_array('3', $sign_group)) {

                if ('return' === $mode) {
                    $msg['op'] = 'error';
                    $msg['title'] = $form['title'];
                    $msg['msg'] = _MD_TADFORM_ONLY_MEM;
                    return $msg;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', _MD_TADFORM_ONLY_MEM);
                    return;
                }

            }
        }

        if (!$_SESSION['tad_form_adm']) {
            if ('1' != $form['enable']) {

                if ('return' === $mode) {
                    $msg['op'] = 'error';
                    $msg['title'] = $form['title'];
                    $msg['msg'] = sprintf(_TADFORM_UNABLE, $form['title']);
                    return $msg;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_TADFORM_UNABLE, $form['title']));
                    return;
                }
            }

            $form['start_date'] = date('Y-m-d H:i', xoops_getUserTimestamp(strtotime($form['start_date'])));
            if ($today < $form['start_date']) {

                if ('return' === $mode) {
                    $msg['op'] = 'error';
                    $msg['title'] = $form['title'];
                    $msg['msg'] = sprintf(_TADFORM_NOT_START, $form['title'], $form['start_date']);
                    return $msg;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_TADFORM_NOT_START, $form['title'], $form['start_date']));
                    return;
                }
            }

            $form['end_date'] = date('Y-m-d H:i', xoops_getUserTimestamp(strtotime($form['end_date'])));
            if ($today > $form['end_date']) {

                if ('return' === $mode) {
                    $msg['op'] = 'error';
                    $msg['title'] = $form['title'];
                    $msg['msg'] = sprintf(_MD_TADFORM_OVERDUE, $form['title'], $form['end_date']);
                    return $msg;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_MD_TADFORM_OVERDUE, $form['title'], $form['end_date']));
                    return;
                }
            }
        }

        //若是用來報名的
        if ('application' === $form['kind']) {
            $man_name_list = '<table><caption>' . _MD_TADFORM_OK_LIST . '</caption>';
            $sql = 'select email,fill_time from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='{$ofsn}' and result_col='1'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $n = $i = 3;
            while (list($email, $fill_time) = $xoopsDB->fetchRow($result)) {
                $fill_time = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($fill_time)));
                $email_data = explode('@', $email);
                $man_name_list .= (0 == $n % $i) ? '<tr>' : '';
                $man_name_list .= "<td>{$email_data[0]}@{$fill_time}</td> ";
                $man_name_list .= ($n % $i == $i - 1) ? '</tr>' : '';
                $n++;
            }
            $man_name_list .= '</table>';

            $apply_ok = "<tr><td><a href='" . XOOPS_URL . "/modules/tad_form/report.php?ofsn=$ofsn' class='btn btn-info'>" . _TADFORM_VIEW_FORM . '</a></td></tr>';
            $apply_ok .= "<tr><td>{$man_name_list}</td></tr>";
        } elseif ($form['show_result'] and can_view_report($ofsn)) {
            $apply_ok = "<tr><td><a href='" . XOOPS_URL . "/modules/tad_form/report.php?ofsn=$ofsn' class='btn btn-info'>" . _TADFORM_VIEW_FORM . '</a></td></tr>';
        } else {
            $apply_ok = '';
        }

        $main_form = '';

        $sql = 'select * from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn='{$ofsn}' order by sort";

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 1;
        while (false !== ($data = $xoopsDB->fetchArray($result))) {
            foreach ($data as $k => $v) {
                $$k = $v;
            }

            $edit_btn = ($_SESSION['tad_form_adm']) ? "<a href='" . XOOPS_URL . "/modules/tad_form/add.php?op=edit_opt&ofsn=$ofsn&csn=$csn&mode=update' class='btn btn-xs btn-warning pull-right float-right pull-end'>" . _TAD_EDIT . '</a>' : '';
            $db_ans_csn = isset($db_ans[$csn]) ? $db_ans[$csn] : '';
            $col_form = col_form($csn, $kind, $size, $val, $db_ans_csn, $chk);

            $chk_txt = ('1' == $chk) ? "<img src='" . XOOPS_URL . "/modules/tad_form/images/star.png' alt='" . _TADFORM_NEED_SIGN . "' hspace=3 align=absmiddle>" : '';
            $note = (empty($descript)) ? '' : "<span class='note'>({$descript})</span>";
            if ('show' === $kind) {
                $show_title = $descript;
                $show_col = '';
            } else {
                $show_title = "
                $edit_btn
                <span class='question'>{$i}. $chk_txt<b>$title</b></span>
                $note";
                $show_col = "<tr><td class='show_col'>$col_form</td></tr>";
            }
            $main_form .= "
                <tr>
                    <td class='q_col'>
                    $show_title
                    </td>
                </tr>
                $show_col
                ";

            if ('show' !== $kind) {
                $i++;
            }
        }

        $jquery = Utility::get_jquery(true);

        $tool = '';
        if ($_SESSION['tad_form_adm']) {
            $tool = "
            <a href='" . XOOPS_URL . "/modules/tad_form/add.php?op=tad_form_main_form&ofsn={$ofsn}' class='btn btn-warning'>" . sprintf(_TADFORM_EDIT_FORM, $form['title']) . "</a>
            <a href='" . XOOPS_URL . "/modules/tad_form/add.php?op=edit_all_opt&ofsn={$ofsn}' class='btn btn-warning'>" . _TADFORM_EDIT_ALL . "</a>
            <a href='" . XOOPS_URL . "/modules/tad_form/result.php?ofsn={$ofsn}' class='btn btn-primary'>" . _TADFORM_VIEW_FORM . '</a>';
        }

        $db_ans_ssn = isset($db_ans['ssn']) ? $db_ans['ssn'] : '';

        if ('return' === $mode) {
            $msg['op'] = 'sign';
            $msg['jquery'] = $jquery;
            $msg['form_title'] = $form['title'];
            $msg['form_content'] = $form['content'];
            $msg['apply_ok'] = $apply_ok;
            $msg['main_form'] = $main_form;
            $msg['db_ans_ssn'] = $db_ans_ssn;
            $msg['ofsn'] = $ofsn;
            $msg['uid_name'] = $uid_name;
            $msg['email'] = $email;
            $msg['tool'] = $tool;
            $msg['history'] = $history;
            $msg['Captcha'] = $form['captcha'];
            return $msg;

        } else {
            $xoopsTpl->assign('op', 'sign');
            $xoopsTpl->assign('jquery', $jquery);
            $xoopsTpl->assign('form_title', $form['title']);
            $xoopsTpl->assign('form_content', $form['content']);
            $xoopsTpl->assign('apply_ok', $apply_ok);
            $xoopsTpl->assign('main_form', $main_form);
            $xoopsTpl->assign('db_ans_ssn', $db_ans_ssn);
            $xoopsTpl->assign('ofsn', $ofsn);
            $xoopsTpl->assign('uid_name', $uid_name);
            $xoopsTpl->assign('email', $email);
            $xoopsTpl->assign('tool', $tool);
            $xoopsTpl->assign('history', $history);
            $xoopsTpl->assign('Captcha', $form['captcha']);

        }

        //表單驗證
        $FormValidator = new FormValidator('#myForm');
        $FormValidator->render();
    }
}

//以流水號取得某筆tad_form_main資料
if (!function_exists('get_tad_form_main')) {
    function get_tad_form_main($ofsn = '', $ssn = '')
    {
        global $xoopsDB;
        if (empty($ofsn) and empty($ssn)) {
            return;
        }

        if ($ssn) {
            $sql = 'select ofsn from ' . $xoopsDB->prefix('tad_form_fill') . " where ssn='$ssn'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($ofsn) = $xoopsDB->fetchRow($result);
        }
        $sql = 'select * from ' . $xoopsDB->prefix('tad_form_main') . " where ofsn='$ofsn'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        return $data;
    }
}

//取得某人在某問卷的填寫結果
if (!function_exists('get_somebody_ans')) {
    function get_somebody_ans($ofsn = '', $uid = '', $ssn = '')
    {
        global $xoopsDB;
        if (empty($uid)) {
            return false;
        }
        $myts = \MyTextSanitizer::getInstance();

        if ($ssn) {
            $sql = 'select b.ssn,b.csn,b.val from ' . $xoopsDB->prefix('tad_form_fill') . ' as a left join  ' . $xoopsDB->prefix('tad_form_value') . " as b on a.ssn=b.ssn where a.ssn='$ssn' and a.uid='$uid'";
        } else {
            $sql = 'select b.ssn,b.csn,b.val from ' . $xoopsDB->prefix('tad_form_fill') . ' as a left join  ' . $xoopsDB->prefix('tad_form_value') . " as b on a.ssn=b.ssn where a.ofsn='$ofsn' and a.uid='$uid'";
        }
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $ans = [];
        while (list($ssn, $csn, $val) = $xoopsDB->fetchRow($result)) {
            $ans[$csn] = $myts->htmlSpecialChars($val);
            $ans['ssn'] = $ssn;
        }

        return $ans;
    }
}

//製作表單
if (!function_exists('col_form')) {
    function col_form($csn = '', $kind = '', $size = '', $default_val = '', $db_ans = [], $chk = '')
    {
        switch ($kind) {
            case 'text':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt = ($chk) ? ' validate[required]' : '';
                $span = empty($size) ? 6 : round($size / 10, 0);
                $main = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}' value='{$default_val}'><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'></div>";
                break;
            case 'radio':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $opt = explode(';', $size);
                $i = 1;
                $main = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                $class4 = ($_SESSION['bootstrap'] == 4) ? 'form-check-input' : '';
                foreach ($opt as $val) {
                    $checked = ($default_val == $val) ? 'checked' : '';
                    $chktxt = ($chk) ? "class='{$class4} validate[required] radio'" : '';
                    if ($_SESSION['bootstrap'] == 4) {
                        $main .= "
                        <div class='form-check form-check-inline'>
                            <input type='radio' name='ans[$csn]' id='inlineRadio{$i}' value='{$val}' $checked $chktxt>
                            <label class='form-check-label' for='inlineRadio{$i}'>{$val}</label>
                        </div>";
                    } else {
                        $main .= "
                        <label class='radio-inline'>
                            <input type='radio' name='ans[$csn]' value='{$val}' $checked $chktxt>{$val}
                        </label>";
                    }
                    $i++;
                }
                break;
            case 'checkbox':
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $db = explode(';', $default_val);

                $opt = explode(';', $size);
                $i = 1;
                $main = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                $class4 = ($_SESSION['bootstrap'] == 4) ? 'form-check-input' : '';
                foreach ($opt as $val) {
                    $checked = (in_array($val, $db)) ? "checked='checked'" : '';
                    $chktxt = ($chk) ? "class='{$class4} validate[required] checkbox'" : '';
                    if ($_SESSION['bootstrap'] == 4) {
                        $main .= "
                        <div class='form-check form-check-inline'>
                            <input type='checkbox' name='ans[$csn][]' id='inlineCheckbox{$i}' value='{$val}' $checked $chktxt>
                            <label class='form-check-label' for='inlineCheckbox{$i}'>{$val}</label>
                        </div>";
                    } else {
                        $main .= "
                        <label class='checkbox-inline'>
                            <input type='checkbox' name='ans[$csn][]' value='{$val}' $checked $chktxt>{$val}
                        </label>";
                    }
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
}

//取得某人在某問卷的填寫記錄
if (!function_exists('get_history')) {
    function get_history($ofsn = '', $uid = '')
    {
        global $xoopsDB;
        if (empty($uid)) {
            return false;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='$ofsn' and uid='$uid'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        //`ssn`, `ofsn`, `uid`, `man_name`, `email`, `fill_time`, `result_col`
        $i = 0;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $data[$i][$k] = $v;
            }
            $i++;
        }

        return $data;
    }
}

//看某人是否可看填報結果
if (!function_exists('can_view_report')) {
    function can_view_report($ofsn = '')
    {
        global $xoopsUser;
        if ($xoopsUser) {
            if ($_SESSION['tad_form_adm']) {
                return true;
            }

            $User_Groups = $xoopsUser->getGroups();
        } else {
            $User_Groups = [3];
        }

        $form = get_tad_form_main($ofsn);
        if ('1' != $form['show_result']) {
            return false;
        }

        $view_result_array = explode(',', $form['view_result_group']);
        if (!empty($view_result_array)) {
            foreach ($view_result_array as $group) {
                if (in_array($group, $User_Groups)) {
                    return true;
                }
            }
        }

        return false;
    }
}
