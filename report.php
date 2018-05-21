<?php
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_form_report.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";
if (!can_view_report((int)$_REQUEST['ofsn'])) {
    redirect_header("index.php", 3, _MD_TADFORM_ONLY_MEM);
}

/*-----------function區--------------*/

function view_user_result($ofsn)
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $interface_menu, $isAdmin;

    $form = get_tad_form_main($ofsn);

    if ($form['show_result'] != '1') {
        redirect_header("index.php", 3, _MD_TADFORM_HIDE_RESULT);
    }

    $myts = MyTextSanitizer::getInstance();

    $thSty = "style='width:135px;'";

    $xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
    $xoopsTpl->assign('ofsn', $ofsn);

    $sql = "select csn,title,kind,func from " . $xoopsDB->prefix("tad_form_col") . " where ofsn='{$ofsn}' and public='1' order by sort";
    //die($sql);
    $result    = $xoopsDB->query($sql) or web_error($sql);
    $all_title = array();
    $i         = 0;
    $csn_arr   = $ff   = $tt   = $kk   = array();
    while (list($csn, $title, $kind, $func) = $xoopsDB->fetchRow($result)) {
        if ($kind == "show") {
            continue;
        }

        //$all_title.="<th $thSty>$title</th>";
        $all_title[$i]['title'] = $title;
        $i++;
        $ff[$csn]  = $func;
        $tt[$csn]  = $title;
        $kk[$csn]  = $kind;
        $csn_arr[] = $csn;
    }

    if ($csn_arr) {
        $all_csn = implode(",", $csn_arr);
    } else {
        $all_csn = "";
    }

    $funct_title = ($form['kind'] == "application") ? "<th $thSty>" . _MD_TADFORM_KIND1_TH . "</th>" : "<th $thSty>" . _TAD_FUNCTION . "</th>";
    //die(var_export($all_title));
    $xoopsTpl->assign('all_title', $all_title);
    $xoopsTpl->assign('funct_title', $funct_title);
    $xoopsTpl->assign('thSty', $thSty);

    $sql = "select ssn,uid,man_name,email,fill_time,code,result_col from " . $xoopsDB->prefix("tad_form_fill") . " where ofsn='{$ofsn}' order by fill_time desc";

    $result         = $xoopsDB->query($sql) or web_error($sql);
    $i              = 0;
    $all_result_col = array();
    while (list($ssn, $uid, $man_name, $email, $fill_time, $code, $result_col) = $xoopsDB->fetchRow($result)) {
        $fill_time  = date("Y-m-d H:i:s", xoops_getUserTimestamp(strtotime($fill_time)));
        $email_data = explode("@", $email);

        //$url=!empty($uid)?"".XOOPS_URL."/userinfo.php?uid=$uid":"#";
        $url = ($isAdmin) ? "{$_SERVER['PHP_SELF']}?op=view&code=$code" : "#";
        //$main.="<tr><td><a href='$url'>$man_name</a></td>";
        $all_result_col[$i]['url']       = $myts->htmlSpecialChars($url);
        $all_result_col[$i]['man_name']  = ($isAdmin) ? $myts->htmlSpecialChars($man_name) : $email_data[0];
        $all_result_col[$i]['fill_time'] = $fill_time;

        $sql2 = "select csn,val from " . $xoopsDB->prefix("tad_form_value") . "  where ssn='{$ssn}'";

        $result2 = $xoopsDB->query($sql2) or web_error($sql2);
        //$all="";

        $col_v = array();
        while (list($csn, $val) = $xoopsDB->fetchRow($result2)) {
            $col_v[$csn] = $myts->htmlSpecialChars($val);
        }

        $n = 0;
        foreach ($csn_arr as $csn) {
            if ($kk[$csn] == 'textarea') {
                $csn_val = nl2br($col_v[$csn]);
            } elseif ($kk[$csn] == 'checkbox') {
                $csn_val = (empty($col_v[$csn])) ? "" : "<ul><li>" . str_replace(";", "</li><li>", $col_v[$csn]) . "</li></ul>";
            } else {
                $csn_val = $col_v[$csn];
            }
            $ans_col[$n]['val'] = $csn_val;
            $n++;
        }

        $all_result_col[$i]['ans'] = $ans_col;

        //根據不同表單類型，提供不同的功能
        if ($form['kind'] == "application") {
            $result_col_pic = ($result_col == '1') ? "001_06.gif" : "001_05.gif";
            $other_fun      = "<img src='images/{$result_col_pic}' alt='{$result_col_pic}' title='{$result_col_pic}'>";
        } else {
            $other_fun = "";
        }

        $fill_time                       = date("Y-m-d H:i:s", xoops_getUserTimestamp(strtotime($fill_time)));
        $all_result_col[$i]['fill_time'] = $fill_time;
        $all_result_col[$i]['other_fun'] = $other_fun;

        $i++;
    }

    $xoopsTpl->assign('result_col', $all_result_col);

}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op   = system_CleanVars($_REQUEST, 'op', '', 'string');
$ofsn = system_CleanVars($_REQUEST, 'ofsn', 0, 'int');
$ssn  = system_CleanVars($_REQUEST, 'ssn', 0, 'int');

switch ($op) {

    //預設動作
    default:
        view_user_result($ofsn);
        break;

}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
