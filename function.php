<?php

use XoopsModules\Tadtools\Utility;

xoops_loadLanguage('main', 'tadtools');

//取得某人在某問卷的填寫結果
function get_somebody_ans($ofsn = '', $uid = '', $ssn = '')
{
    global $xoopsDB;
    if (empty($uid)) {
        return false;
    }
    $myts = MyTextSanitizer::getInstance();

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

//看某人是否可看填報結果
function can_view_report($ofsn = '')
{
    global $xoopsUser, $isAdmin;
    if ($xoopsUser) {
        if ($isAdmin) {
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

//查填報答案是否為某人或管理者
function is_mine($ssn = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser, $xoopsModule;

    $isAdmin = false;

    if ($xoopsUser) {
        $module_id = $xoopsModule->getVar('mid');
        $isAdmin = $xoopsUser->isAdmin($module_id);
        if ($isAdmin) {
            return true;
        }

        $now_uid = $xoopsUser->uid();

        $sql = 'select uid from ' . $xoopsDB->prefix('tad_form_fill') . " where ssn='$ssn'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($uid) = $xoopsDB->fetchRow($result);
        if ($now_uid == $uid) {
            return true;
        }
    }

    return false;
}

//取得某人在某問卷的填寫記錄
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
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $data[$i][$k] = $v;
        }
        $i++;
    }

    return $data;
}

//觀看填報結果
function view($code = '', $mode = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $isAdmin;

    $myts = MyTextSanitizer::getInstance();

    $sql = 'select ofsn,ssn,uid,man_name,email,fill_time from ' . $xoopsDB->prefix('tad_form_fill') . " where code='{$code}'";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($ofsn, $ssn, $uid, $man_name, $email, $fill_time) = $xoopsDB->fetchRow($result);
    if (empty($ssn)) {
        return;
    }

    $form = get_tad_form_main($ofsn);

    $tbl_set = ('mail' === $mode) ? 'border=1 ' : "class='table table-striped'";
    $td_set = ('mail' === $mode) ? 'bgcolor=#F0F0F0' : '';
    $content = ('mail' === $mode) ? '' : "<tr><td class='note' colspan=2>{$form['content']}</td></tr>";

    $sql = 'select b.csn,b.val,a.title from ' . $xoopsDB->prefix('tad_form_col') . ' as a left join ' . $xoopsDB->prefix('tad_form_value') . " as b on a.csn=b.csn where b.ssn='{$ssn}' order by a.sort";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 1;
    while (list($csn, $val, $title) = $xoopsDB->fetchRow($result)) {
        if ('mail' === $mode) {
            $all .= "
      <tr>
        <td {$td_set}>{$i}. <b>{$title}</b></td>
        <td>{$val}</td>
      </tr>";
        } else {
            $all[$i]['td_set'] = $td_set;
            $all[$i]['i'] = $i;
            $all[$i]['title'] = $myts->htmlSpecialChars($title);
            $all[$i]['val'] = $myts->htmlSpecialChars($val);
        }
        $i++;
    }

    if ('mail' === $mode) {
        $main = "
    <table {$tbl_set}>
    {$content}
    {$all}
    </table>
    <div class=\"text-center\">
    ";

        if ($show_report) {
            $main .= '<a href="' . XOOPS_URL . "/modules/tad_form/report.php?ofsn={$ofsn}\" class=\"btn btn-info\">" . _MD_TADFORM_VIEW_FORM . '</a>';
        }
        $main .= '<a href="' . XOOPS_URL . "/modules/tad_form/index.php?op=sign&ofsn={$ofsn}\" class=\"btn btn-success\">" . _MD_TADFORM_BACK_TO_FORM . '</a>
    </div>';

        return $main;
    }
    $xoopsTpl->assign('op', 'view');
    $xoopsTpl->assign('form_title', $form['title']);
    $xoopsTpl->assign('tbl_set', $tbl_set);
    $xoopsTpl->assign('content', $content);
    $xoopsTpl->assign('all', $all);
    $xoopsTpl->assign('man_name', $myts->htmlSpecialChars($man_name));
    $xoopsTpl->assign('fill_time', $fill_time);
    $xoopsTpl->assign('email', $myts->htmlSpecialChars($email));
    $xoopsTpl->assign('ofsn', $ofsn);
    $xoopsTpl->assign('ssn', $ssn);
    $xoopsTpl->assign('show_report', can_view_report($ofsn));
}

//刪除某人的填寫資料
function delete_tad_form_ans($ssn = '')
{
    global $xoopsDB, $isAdmin;

    if (is_mine($ssn)) {
        $sql = 'delete from ' . $xoopsDB->prefix('tad_form_fill') . " where ssn='$ssn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        //die($sql);
        $sql = 'delete from ' . $xoopsDB->prefix('tad_form_value') . " where ssn='$ssn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
}

//以流水號取得某筆tad_form_main資料
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

//變更狀態
function set_form_status($ofsn = '', $enable = '0')
{
    global $xoopsDB;
    if (empty($ofsn)) {
        return;
    }

    $sql = 'update ' . $xoopsDB->prefix('tad_form_main') . " set enable='{$enable}' where ofsn='$ofsn'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//檢查Email的JS
function chk_emeil_js($email_col = 'email', $form_name = 'myForm')
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

/*
//產生必填覽位的檢查JS碼
function needfill_js_new($needfill=array(),$form_name="myForm"){
$needfill_js="";
foreach($needfill as $colname=>$col){
if($col['type']=="radio" or $col['type']=="checkbox"){
$needfill_js.="
var chk{$colname} = 'false';
for (var i=0; i<{$col['len']}; i++){
if (document.getElementById('tf{$colname}_'+i).checked){
chk{$colname} = 'true';
break;
}
}";
$col_val="chk{$colname} == 'false'";
$focus="tf{$colname}_0";
}else{
$col_val="document.getElementById('tf{$colname}').value == ''";
$focus="tf{$colname}";
}

if(!empty($col['col'])){
$needfill_js.="
if($col_val){
alert('".sprintf(_JS_SIGN_CHK,$col['col'])."');
document.getElementById('{$focus}').focus();
return false;
}";
}

}
return $needfill_js;
}
 */

/********************* 預設函數 *********************/
