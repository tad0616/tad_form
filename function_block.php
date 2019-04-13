<?php

//填寫表單
if (!function_exists('sign_form')) {
    function sign_form($ofsn = "", $ssn = "", $mode = "")
    {
        global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsTpl;

        $today = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
        $form  = get_tad_form_main($ofsn, $ssn);
        $ofsn  = $form['ofsn'];

        $sign_group = (empty($form['sign_group'])) ? "" : explode(",", $form['sign_group']);

        if ($xoopsUser) {
            $module_id = $xoopsModule->getVar('mid');
            $isAdmin   = $xoopsUser->isAdmin($module_id);
            $email     = $xoopsUser->getVar('email');

            $User_Groups = $xoopsUser->getGroups();
            $ugroup      = implode(",", $User_Groups);

            if (!empty($sign_group) and !in_array(1, $User_Groups)) {
                $ok = false;
                foreach ($sign_group as $group) {
                    if (in_array($group, $User_Groups)) {
                        $ok = true;
                    }
                }
                if (!$ok) {
                    if ($mode == "return") {
                        $error['title'] = $form['title'];
                        $error['error'] = _TADFORM_ONLY_MEM;
                        return $error;
                    } else {
                        $xoopsTpl->assign('op', 'error');
                        $xoopsTpl->assign('title', $form['title']);
                        $xoopsTpl->assign('msg', _TADFORM_ONLY_MEM);
                        return;
                    }
                }
            }

            $uid      = $xoopsUser->getVar('uid');
            $uid_name = $xoopsUser->getVar('name');
            if (empty($uid_name)) {
                $uid_name = $xoopsUser->getVar('uname');
            }

            if (empty($uid_name)) {
                $uid_name = $xoopsUser->getVar('loginname');
            }

            if ($ssn) {
                $db_ans = get_somebody_ans($ofsn, $uid, $ssn);
            } else {
                $db_ans = ($form['multi_sign'] == '1') ? [] : get_somebody_ans($ofsn, $uid, $ssn);
            }
            $history = ($form['multi_sign'] == '1') ? get_history($ofsn, $uid) : "";
        } else {
            $uid_name = "";
            $email    = $history    = "";
            $isAdmin  = false;
            $db_ans   = [];
            if (!empty($sign_group) and !in_array(3, $sign_group)) {
                if ($mode == "return") {
                    $error['title'] = $form['title'];
                    $error['error'] = _TADFORM_ONLY_MEM;
                    return $error;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', _TADFORM_ONLY_MEM);
                    return;
                }
            }
        }

        if (!$isAdmin) {
            if ($form['enable'] != '1') {
                if ($mode == "return") {
                    $error['title'] = $form['title'];
                    $error['error'] = sprintf(_TADFORM_UNABLE, $form['title']);
                    return $error;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_TADFORM_UNABLE, $form['title']));
                    return;
                }
            }

            $form['start_date'] = date("Y-m-d H:i", xoops_getUserTimestamp(strtotime($form['start_date'])));
            if ($today < $form['start_date']) {
                if ($mode == "return") {
                    $error['title'] = $form['title'];
                    $error['error'] = sprintf(_TADFORM_NOT_START, $form['title'], $form['start_date']);
                    return $error;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_TADFORM_NOT_START, $form['title'], $form['start_date']));
                    return;
                }
            }

            $form['end_date'] = date("Y-m-d H:i", xoops_getUserTimestamp(strtotime($form['end_date'])));
            if ($today > $form['end_date']) {
                if ($mode == "return") {
                    $error['title'] = $form['title'];
                    $error['error'] = sprintf(_TADFORM_OVERDUE, $form['title'], $form['end_date']);
                    return $error;
                } else {
                    $xoopsTpl->assign('op', 'error');
                    $xoopsTpl->assign('title', $form['title']);
                    $xoopsTpl->assign('msg', sprintf(_TADFORM_OVERDUE, $form['title'], $form['end_date']));
                    return;
                }
            }
        }

        //若是用來報名的
        if ($form['kind'] == "application") {
            $man_name_list = "<table><caption>" . _TADFORM_OK_LIST . "</caption>";
            $sql           = "select email,fill_time from " . $xoopsDB->prefix("tad_form_fill") . " where ofsn='{$ofsn}' and result_col='1'";
            $result        = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
            $n             = $i             = 3;
            while (list($email, $fill_time) = $xoopsDB->fetchRow($result)) {
                $fill_time  = date("Y-m-d H:i:s", xoops_getUserTimestamp(strtotime($fill_time)));
                $email_data = explode("@", $email);
                $man_name_list .= ($n % $i == 0) ? "<tr>" : "";
                $man_name_list .= "<td>{$email_data[0]}@{$fill_time}</td> ";
                $man_name_list .= ($n % $i == $i - 1) ? "</tr>" : "";
                $n++;
            }
            $man_name_list .= "</table>";

            $apply_ok = "<tr><td>{$man_name_list}</td></tr>";
        } elseif ($form['show_result'] and can_view_report($ofsn)) {
            $apply_ok = "<tr><td><a href='" . XOOPS_URL . "/modules/tad_form/report.php?ofsn=$ofsn' class='btn btn-info'>" . _TADFORM_VIEW_FORM . "</a></td></tr>";
        } else {
            $apply_ok = "";
        }

        $main_form = "";

        $sql = "select * from " . $xoopsDB->prefix("tad_form_col") . " where ofsn='{$ofsn}' order by sort";

        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $i      = 1;
        while ($data = $xoopsDB->fetchArray($result)) {
            foreach ($data as $k => $v) {
                $$k = $v;
            }

            $edit_btn   = ($isAdmin) ? "<a href='" . XOOPS_URL . "/modules/tad_form/admin/add.php?op=edit_opt&ofsn=$ofsn&csn=$csn&mode=update' class='btn btn-mini btn-warning pull-right'>" . _TAD_EDIT . "</a>" : "";
            $db_ans_csn = isset($db_ans[$csn]) ? $db_ans[$csn] : "";
            $col_form   = col_form($csn, $kind, $size, $val, $db_ans_csn, $chk);

            $chk_txt = ($chk == '1') ? "<img src='" . XOOPS_URL . "/modules/tad_form/images/star.png' alt='" . _TADFORM_NEED_SIGN . "' hspace=3 align=absmiddle>" : "";
            $note    = (empty($descript)) ? "" : "<span class='note'>({$descript})</span>";
            if ($kind == 'show') {
                $show_title = $descript;
                $show_col   = "";
            } else {
                $show_title = "
                <div class='q_col'>
                    $edit_btn
                    <span class='question'>{$i}. $chk_txt<b>$title</b></span>
                    $note
                </div>";
                $show_col = "<tr><td class='show_col'>$col_form</td></tr>";
            }
            $main_form .= "
            <tr>
                <td>
                $show_title
                </td>
            </tr>
            $show_col
            ";

            if ($kind != 'show') {
                $i++;
            }
        }

        $chk_emeil_js = chk_emeil_js("email", "myForm");

        get_jquery(true);

        $captcha_js  = "";
        $captcha_div = "";
        if ($form['captcha'] == '1') {
            $captcha_js = "
            <link rel='stylesheet' type='text/css' href='" . XOOPS_URL . "/modules/tad_form/class/Qaptcha_v3.0/jquery/QapTcha.jquery.css' media='screen' />
            <script type='text/javascript' src='class/Qaptcha_v3.0/jquery/jquery.ui.touch.js'></script>
            <script type='text/javascript' src='class/Qaptcha_v3.0/jquery/QapTcha.jquery.js'></script>
            <script type='text/javascript'>
                $(document).ready(function(){
                $('.QapTcha').QapTcha({disabledSubmit:true , autoRevert:true , PHPfile:'class/Qaptcha_v3.0/php/Qaptcha.jquery.php', txtLock:'" . _TADFORM_TXTLOCK . "' , txtUnlock:'" . _TADFORM_TXTUNLOCK . "'});
                });
            </script>";
            $captcha_div = "<div class='QapTcha'></div>";
        }

        $tool = "";
        if ($isAdmin) {
            $tool = "
            <a href='" . XOOPS_URL . "/modules/tad_form/admin/add.php?op=tad_form_main_form&ofsn={$ofsn}' class='btn btn-warning'>" . sprintf(_TADFORM_EDIT_FORM, $form['title']) . "</a>
            <a href='" . XOOPS_URL . "/modules/tad_form/admin/add.php?op=edit_all_opt&ofsn={$ofsn}' class='btn btn-warning'>" . _TADFORM_EDIT_ALL . "</a>
            <a href='" . XOOPS_URL . "/modules/tad_form/admin/result.php?ofsn={$ofsn}' class='btn btn-primary'>" . _TADFORM_VIEW_FORM . "</a>";
        }

        $db_ans_ssn = isset($db_ans['ssn']) ? $db_ans['ssn'] : "";

        if ($mode == "return") {
            $f['op']           = 'sign';
            $f['chk_emeil_js'] = $chk_emeil_js;
            $f['form_title']   = $form['title'];
            $f['form_content'] = $form['content'];
            $f['apply_ok']     = $apply_ok;
            $f['main_form']    = $main_form;
            $f['db_ans_ssn']   = $db_ans_ssn;
            $f['ofsn']         = $ofsn;
            $f['captcha_div']  = $captcha_div;
            $f['uid_name']     = $uid_name;
            $f['email']        = $email;
            $f['captcha_js']   = $captcha_js;
            $f['tool']         = $tool;
            $f['history']      = $history;
            return $f;
        } else {
            $xoopsTpl->assign('op', 'sign');
            $xoopsTpl->assign('chk_emeil_js', $chk_emeil_js);
            $xoopsTpl->assign('form_title', $form['title']);
            $xoopsTpl->assign('form_content', $form['content']);
            $xoopsTpl->assign('apply_ok', $apply_ok);
            $xoopsTpl->assign('main_form', $main_form);
            $xoopsTpl->assign('db_ans_ssn', $db_ans_ssn);
            $xoopsTpl->assign('ofsn', $ofsn);
            $xoopsTpl->assign('captcha_div', $captcha_div);
            $xoopsTpl->assign('uid_name', $uid_name);
            $xoopsTpl->assign('email', $email);
            $xoopsTpl->assign('captcha_js', $captcha_js);
            $xoopsTpl->assign('tool', $tool);
            $xoopsTpl->assign('history', $history);
        }

        //表單驗證
        if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
            redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
        }
        include_once TADTOOLS_PATH . "/formValidator.php";
        $formValidator = new formValidator("#myForm");
        $formValidator->render();
    }
}

//以流水號取得某筆tad_form_main資料
if (!function_exists('get_tad_form_main')) {
    function get_tad_form_main($ofsn = "", $ssn = "")
    {
        global $xoopsDB;
        if (empty($ofsn) and empty($ssn)) {
            return;
        }

        if ($ssn) {
            $sql        = "select ofsn from " . $xoopsDB->prefix("tad_form_fill") . " where ssn='$ssn'";
            $result     = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
            list($ofsn) = $xoopsDB->fetchRow($result);
        }
        $sql    = "select * from " . $xoopsDB->prefix("tad_form_main") . " where ofsn='$ofsn'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }
}

//取得某人在某問卷的填寫結果
if (!function_exists('get_somebody_ans')) {
    function get_somebody_ans($ofsn = "", $uid = "", $ssn = "")
    {
        global $xoopsDB;
        if (empty($uid)) {
            return false;
        }
        $myts = MyTextSanitizer::getInstance();

        if ($ssn) {
            $sql = "select b.ssn,b.csn,b.val from " . $xoopsDB->prefix("tad_form_fill") . " as a left join  " . $xoopsDB->prefix("tad_form_value") . " as b on a.ssn=b.ssn where a.ssn='$ssn' and a.uid='$uid'";
        } else {
            $sql = "select b.ssn,b.csn,b.val from " . $xoopsDB->prefix("tad_form_fill") . " as a left join  " . $xoopsDB->prefix("tad_form_value") . " as b on a.ssn=b.ssn where a.ofsn='$ofsn' and a.uid='$uid'";
        }
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        $ans    = [];
        while (list($ssn, $csn, $val) = $xoopsDB->fetchRow($result)) {
            $ans[$csn]  = $myts->htmlSpecialChars($val);
            $ans['ssn'] = $ssn;
        }
        return $ans;
    }
}
//檢查Email的JS
if (!function_exists('chk_emeil_js')) {
    function chk_emeil_js($email_col = "email", $form_name = "myForm")
    {
        $js = "
        var regPatten=/^.+@.+\..{2,3}$/;
        if (document.{$form_name}.elements['{$email_col}'].value.match(regPatten)==null){
            alert('" . _JS_EMAIL_CHK . "');
            return false;
        }
        ";
        return $js;
    }
}

//製作表單
if (!function_exists('col_form')) {
    function col_form($csn = "", $kind = "", $size = "", $default_val = "", $db_ans = [], $chk = "")
    {
        switch ($kind) {
            case "text":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt      = ($chk) ? " validate[required]" : "";
                $span        = empty($size) ? 6 : round($size / 10, 0);
                $main        = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}' value='{$default_val}'><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'></div>";
                break;

            case "radio":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $opt         = explode(";", $size);
                $i           = 0;
                $main        = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                foreach ($opt as $val) {
                    $checked = ($default_val == $val) ? "checked='checked'" : "";
                    $chktxt  = ($chk) ? "class='validate[required] radio'" : "";
                    $main .= "
                  <label class='radio-inline'>
                    <input type='radio' name='ans[$csn]' value='{$val}' $checked $chktxt>{$val}
                  </label>";
                    $i++;
                }
                break;

            case "checkbox":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $db          = explode(";", $default_val);

                $opt  = explode(";", $size);
                $i    = 0;
                $main = "<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                foreach ($opt as $val) {
                    $checked = (in_array($val, $db)) ? "checked='checked'" : "";
                    $chktxt  = ($chk) ? "class='validate[required] checkbox'" : "";
                    $main .= "
                  <label class='checkbox-inline'>
                    <input type='checkbox' name='ans[$csn][]' value='{$val}' $checked $chktxt>{$val}
                  </label>";
                    $i++;
                }
                break;

            case "select":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt      = ($chk) ? "validate[required]" : "";
                $opt         = explode(";", $size);
                $main        = "<label for='tf{$csn}' style='display:none;'>{$csn}</label><select name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}'>";
                foreach ($opt as $val) {
                    $selected = ($default_val == $val) ? "selected" : "";
                    $main .= "<option value='{$val}' $selected>{$val}</option>";
                }
                $main .= "</select><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;

            case "textarea":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $chktxt      = ($chk) ? "validate[required]" : "";
                if (empty($size)) {
                    $size = 60;
                }

                $main = "<label for='tf{$csn}' style='display:none;'>{$csn}</label><textarea name='ans[$csn]' id='tf{$csn}' class='form-control {$chktxt}' style='height:{$size}px;'>{$default_val}</textarea><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;

            case "date":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $span        = empty($size) ? 6 : round($size / 10, 0);
                $chktxt      = ($chk) ? "validate[required]" : "";
                $main        = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}' class='form-control {$chktxt}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})\"></div>
								                <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;

            case "datetime":
                $default_val = (empty($db_ans)) ? $default_val : $db_ans;
                $span        = empty($size) ? 6 : round($size / 10, 0);
                $chktxt      = ($chk) ? "validate[required]" : "";
                $main        = "<div class='col-sm-{$span}'><label for='tf{$csn}' style='display:none;'>{$csn}</label><input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}'  class='form-control {$chktxt}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm' , startDate:'%y-%M-%d %H:%m}'})\"></div>
								                <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
                break;

            case "show":
                $main = "";
                break;
        }
        return $main;
    }
}
