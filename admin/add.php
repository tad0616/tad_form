<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_form_adm_add.html";
include_once "header.php";
include_once "../function.php";
/*-----------function區--------------*/
//tad_form_main編輯表單
function tad_form_main_form($ofsn = "")
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    //抓取預設值
    if (!empty($ofsn)) {
        $DBV = get_tad_form_main($ofsn);
    } else {
        $DBV = array();
    }

    //預設值設定

    $ofsn              = (!isset($DBV['ofsn'])) ? "" : $DBV['ofsn'];
    $title             = (!isset($DBV['title'])) ? "" : $DBV['title'];
    $start_date        = (!isset($DBV['start_date'])) ? date("Y-m-d H:i:00") : $DBV['start_date'];
    $end_date          = (!isset($DBV['end_date'])) ? date("Y-m-d H:i:00", strtotime("+7 days")) : $DBV['end_date'];
    $content           = (!isset($DBV['content'])) ? "" : $DBV['content'];
    $uid               = (!isset($DBV['uid'])) ? "" : $DBV['uid'];
    $post_date         = (!isset($DBV['post_date'])) ? "" : $DBV['post_date'];
    $enable            = (!isset($DBV['enable'])) ? "" : $DBV['enable'];
    $sign_group        = (!isset($DBV['sign_group'])) ? array(1, 2) : explode(",", $DBV['sign_group']);
    $kind              = (!isset($DBV['kind'])) ? "" : $DBV['kind'];
    $adm_email         = (empty($DBV['adm_email'])) ? $xoopsUser->email() : $DBV['adm_email'];
    $captcha           = (!isset($DBV['captcha'])) ? "0" : $DBV['captcha'];
    $show_result       = (!isset($DBV['show_result'])) ? "0" : $DBV['show_result'];
    $view_result_group = (!isset($DBV['view_result_group'])) ? array(1) : explode(",", $DBV['view_result_group']);
    $multi_sign        = (!isset($DBV['multi_sign'])) ? "0" : $DBV['multi_sign'];

    $SelectGroup_name = new XoopsFormSelectGroup("", "sign_group", true, $sign_group, 5, true);
    $SelectGroup_name->setExtra("class='span12 form-control'");
    $sign_group = $SelectGroup_name->render();

    $SelectGroup_name2 = new XoopsFormSelectGroup("", "view_result_group", true, $view_result_group, 5, true);
    $SelectGroup_name2->setExtra("class='span12 form-control'");
    $view_result_group = $SelectGroup_name2->render();

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ck.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";
    $fck    = new CKEditor("tad_form", "content", $content);
    $editor = $fck->render();

    $op = (empty($ofsn)) ? "insert_tad_form_main" : "update_tad_form_main";

    $next = (empty($ofsn)) ? "<label class='checkbox inline'><input type='checkbox' name='edit_option' value='1' checked>" . _MA_TADFORM_EDIT_OPTION . "</label>" : "";

    $kind_menu = kind_menu($kind);

    //表單驗證
    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator      = new formValidator("#myForm");
    $formValidator_code = $formValidator->render();

    //$op="replace_tad_form_main";

    $xoopsTpl->assign("formValidator_code", $formValidator_code);
    $xoopsTpl->assign("title", $title);
    $xoopsTpl->assign("sign_group", $sign_group);
    $xoopsTpl->assign("kind_menu", $kind_menu);
    $xoopsTpl->assign("show_result1", chk($show_result, "1"));
    $xoopsTpl->assign("show_result0", chk($show_result, "0"));
    $xoopsTpl->assign("start_date", $start_date);
    $xoopsTpl->assign("end_date", $end_date);
    $xoopsTpl->assign("adm_email", $adm_email);
    $xoopsTpl->assign("captcha1", chk($captcha, "1"));
    $xoopsTpl->assign("captcha0", chk($captcha, "0"));
    $xoopsTpl->assign("editor", $editor);
    $xoopsTpl->assign("enable", $enable);
    $xoopsTpl->assign("ofsn", $ofsn);
    $xoopsTpl->assign("op", $op);
    $xoopsTpl->assign("next", $next);
    $xoopsTpl->assign("view_result_group", $view_result_group);
    $xoopsTpl->assign("multi_sign", $multi_sign);
    $xoopsTpl->assign("multi_sign1", chk($multi_sign, "1"));
    $xoopsTpl->assign("multi_sign0", chk($multi_sign, "0"));

}

//表單用途
function kind_menu($db_kind = "")
{
    $kind_array['application'] = _MA_TADFORM_KIND1;
    //$kind_array['examination']=_MA_TADFORM_KIND2;
    $opt = "<option value=''>" . _MA_TADFORM_KIND0 . "</option>";
    foreach ($kind_array as $kind => $kind_txt) {
        $selected = ($db_kind == $kind) ? "selected" : "";
        $opt .= "<option value='$kind' $selected>$kind_txt</option>";
    }
    return $opt;
}

//新增資料到tad_form_main中
function insert_tad_form_main()
{
    global $xoopsDB, $xoopsUser;
    $uid               = $xoopsUser->getVar('uid');
    $sign_group        = (in_array("", $_POST['sign_group'])) ? "" : implode(",", $_POST['sign_group']);
    $view_result_group = (in_array("", $_POST['view_result_group'])) ? "" : implode(",", $_POST['view_result_group']);
    $now               = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));

    $_POST['enable'] = empty($_POST['enable']) ? 0 : 1;

    $sql = "insert into " . $xoopsDB->prefix("tad_form_main") . " (`title`,`start_date`,`end_date`,`content`,`uid`,`post_date`,`enable`,`sign_group`,`kind`,`adm_email`,`captcha`,`show_result`,`view_result_group`,`multi_sign`) values('{$_POST['title']}','{$_POST['start_date']}','{$_POST['end_date']}','{$_POST['content']}','{$uid}', '{$now}' , '{$_POST['enable']}','{$sign_group}','{$_POST['kind']}','{$_POST['adm_email']}','{$_POST['captcha']}','{$_POST['show_result']}','{$view_result_group}','{$_POST['multi_sign']}')";
    $xoopsDB->query($sql) or web_error($sql);
    //取得最後新增資料的流水編號
    $ofsn = $xoopsDB->getInsertId();
    return $ofsn;
}

//更新tad_form_main某一筆資料
function update_tad_form_main($ofsn = "")
{
    global $xoopsDB;
    $sign_group        = (in_array("", $_POST['sign_group'])) ? "" : implode(",", $_POST['sign_group']);
    $view_result_group = (in_array("", $_POST['view_result_group'])) ? "" : implode(",", $_POST['view_result_group']);
    $now               = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));

    $_POST['enable'] = empty($_POST['enable']) ? 0 : 1;
    $sql             = "update " . $xoopsDB->prefix("tad_form_main") . " set  `title` = '{$_POST['title']}', `start_date` = '{$_POST['start_date']}', `end_date` = '{$_POST['end_date']}', `content` = '{$_POST['content']}', `post_date` = '{$now}', `enable` = '{$_POST['enable']}', `sign_group` = '{$sign_group}', `kind` = '{$_POST['kind']}',`adm_email` = '{$_POST['adm_email']}',`show_result` = '{$_POST['show_result']}',`captcha` = '{$_POST['captcha']}',`view_result_group` = '{$view_result_group}',`multi_sign` = '{$_POST['multi_sign']}' where ofsn='$ofsn'";
    $xoopsDB->queryF($sql) or web_error($sql);
    return $ofsn;
}

/*************************************欄位部份****************************************/

//tad_form_col編輯表單
function tad_form_col_form($the_ofsn = "", $csn = "", $mode = "")
{
    global $xoopsDB, $xoopsTpl;
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    //抓取預設值
    if (!empty($csn)) {
        $DBV = get_tad_form_col($csn);
    } else {
        $DBV = array();
    }

    $form = get_tad_form_main($the_ofsn);

    //預設值設定

    $csn      = (!isset($DBV['csn'])) ? "" : $DBV['csn'];
    $ofsn     = (!isset($DBV['ofsn'])) ? $the_ofsn : $DBV['ofsn'];
    $title    = (!isset($DBV['title'])) ? "" : $DBV['title'];
    $descript = (!isset($DBV['descript'])) ? "" : $DBV['descript'];
    $kind     = (!isset($DBV['kind'])) ? "" : $DBV['kind'];
    $size     = (!isset($DBV['size'])) ? "" : $DBV['size'];
    $val      = (!isset($DBV['val'])) ? "" : $DBV['val'];
    $chk      = (!isset($DBV['chk'])) ? "" : $DBV['chk'];
    $func     = (!isset($DBV['func'])) ? "" : $DBV['func'];
    $sort     = (!isset($DBV['sort'])) ? get_max_sort($the_ofsn) : $DBV['sort'];
    $public   = (!isset($DBV['public'])) ? "0" : $DBV['public'];

    $op = (empty($csn)) ? "insert_tad_form_col" : "update_tad_form_col";
    //$op="replace_tad_form_col";

    $end_txt = (!empty($mode)) ? "<input type='hidden' name='mode' value='$mode'>" : "<label class='checkbox inline'><input type='checkbox' name='end' value='1'>" . _MA_TADFORM_COL_END . "</label>";

    $xoopsTpl->assign('op', 'edit_opt');
    $xoopsTpl->assign('csn', $csn);
    $xoopsTpl->assign('ofsn', $ofsn);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('descript', $descript);
    $xoopsTpl->assign('kind', $kind);
    $xoopsTpl->assign('size', $size);
    $xoopsTpl->assign('val', $val);
    $xoopsTpl->assign('chk', $chk);
    $xoopsTpl->assign('func', $func);
    $xoopsTpl->assign('sort', $sort);
    $xoopsTpl->assign('end_txt', $end_txt);
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('kind', $kind);
    $xoopsTpl->assign('public', $public);
}

//自動取得排序
function get_max_sort($ofsn = "")
{
    global $xoopsDB;
    $sql        = "select max(sort) from " . $xoopsDB->prefix("tad_form_col") . " where ofsn={$ofsn}";
    $result     = $xoopsDB->query($sql) or web_error($sql);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;

}

//新增資料到tad_form_col中
function insert_tad_form_col()
{
    global $xoopsDB;
    $sql = "insert into " . $xoopsDB->prefix("tad_form_col") . " (`ofsn`,`title`,`descript`,`kind`,`size`,`val`,`chk`,`func`,`sort`,`public`) values('{$_POST['ofsn']}','{$_POST['title']}','{$_POST['descript']}','{$_POST['kind']}','{$_POST['size']}','{$_POST['val']}','{$_POST['chk']}','{$_POST['func']}','{$_POST['sort']}','{$_POST['public']}')";
    $xoopsDB->query($sql) or web_error($sql);
    //取得最後新增資料的流水編號
    $csn = $xoopsDB->getInsertId();
    return $csn;
}

//以流水號取得某筆tad_form_col資料
function get_tad_form_col($csn = "")
{
    global $xoopsDB;
    if (empty($csn)) {
        return;
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_form_col") . " where csn='$csn'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//更新tad_form_col某一筆資料
function update_tad_form_col($csn = "")
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_form_col") . " set  `ofsn` = '{$_POST['ofsn']}', `title` = '{$_POST['title']}', `descript` = '{$_POST['descript']}', `kind` = '{$_POST['kind']}', `size` = '{$_POST['size']}', `val` = '{$_POST['val']}', `chk` = '{$_POST['chk']}', `func` = '{$_POST['func']}', `sort` = '{$_POST['sort']}', `public` = '{$_POST['public']}' where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql);
    return $csn;
}

//刪除tad_form_col某筆資料資料
function delete_tad_form_col($csn = "")
{
    global $xoopsDB;
    $sql = "delete from " . $xoopsDB->prefix("tad_form_col") . " where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql);
}

//編輯題目
function edit_all_opt($ofsn = "")
{
    global $xoopsDB, $xoopsTpl;

    $col_type['text']     = _MA_TADFORM_COL_TEXT;
    $col_type['radio']    = _MA_TADFORM_COL_RADIO;
    $col_type['checkbox'] = _MA_TADFORM_COL_CHECKBOX;
    $col_type['select']   = _MA_TADFORM_COL_SELECT;
    $col_type['textarea'] = _MA_TADFORM_COL_TEXTAREA;
    $col_type['date']     = _MA_TADFORM_COL_DATE;
    $col_type['datetime'] = _MA_TADFORM_COL_DATETIME;
    $col_type['show']     = _MA_TADFORM_COL_SHOW;

    $jquery = get_jquery(true);
    $sql    = "select csn,title,descript,kind,size,val,chk,func,sort,public from " . $xoopsDB->prefix("tad_form_col") . " where ofsn='{$ofsn}' order by sort";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $i      = 1;

    while (list($csn, $title, $descript, $kind, $size, $val, $chk, $func, $sort, $public) = $xoopsDB->fetchRow($result)) {
        $descript = (empty($descript)) ? "" : "({$descript})";

        $public     = ($public == '1') ? "001_06" : "001_05";
        $chk        = ($chk == '1') ? "001_06" : "001_05";
        $new_public = ($public == '1') ? 0 : 1;
        $new_chk    = ($chk == '1') ? 0 : 1;

        $question[$i]['csn']      = $csn;
        $question[$i]['title']    = $title;
        $question[$i]['descript'] = $descript;
        $question[$i]['col_type'] = $col_type[$kind];
        $question[$i]['size']     = str_replace(";", "<br>", $size);
        $question[$i]['val']      = $val;
        $question[$i]['chk']      = "<a href='add.php?op=change_chk&chk={$new_chk}&csn={$csn}&ofsn={$ofsn}'><img src='../images/{$chk}.gif'></a>";
        $question[$i]['func']     = $func;
        $question[$i]['public']   = "<a href='add.php?op=change_public&public={$new_public}&csn={$csn}&ofsn={$ofsn}'><img src='../images/{$public}.gif'></a>";
        $i++;

    }

    $xoopsTpl->assign('ofsn', $ofsn);
    $xoopsTpl->assign('question', $question);
    $xoopsTpl->assign('op', 'edit_all_opt');
    $xoopsTpl->assign('jquery', $jquery);

}

//更新欄位是否公開
function change_public($csn = "", $public = "0")
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_form_col") . " set  `public` = '{$public}' where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql);
}

//更新欄位是否檢查
function change_chk($csn = "", $chk = "0")
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_form_col") . " set  `chk` = '{$chk}' where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql);
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op   = system_CleanVars($_REQUEST, 'op', '', 'string');
$ofsn = system_CleanVars($_REQUEST, 'ofsn', 0, 'int');
$csn  = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$mode = system_CleanVars($_REQUEST, 'mode', '', 'string');

switch ($op) {

    //更新欄位是否公開
    case "change_public";
        change_public($csn, $_GET['public']);
        header("location: {$_SERVER['PHP_SELF']}?op=edit_all_opt&ofsn=$ofsn");
        exit;
        break;

    //更新欄位是否檢查
    case "change_chk";
        change_chk($csn, $_GET['chk']);
        header("location: {$_SERVER['PHP_SELF']}?op=edit_all_opt&ofsn=$ofsn");
        exit;
        break;

    //新增資料
    case "insert_tad_form_main":
        $ofsn = insert_tad_form_main();
        header("location: {$_SERVER['PHP_SELF']}?op=edit_opt&ofsn=$ofsn");
        exit;
        break;

    //更新資料
    case "update_tad_form_main";
        $ofsn = update_tad_form_main($ofsn);
        if ($_POST['edit_option'] == '1') {
            header("location: {$_SERVER['PHP_SELF']}?op=edit_opt&ofsn=$ofsn");
            exit;
        } else {
            header("location: main.php");
            exit;
        }
        break;

    //輸入表格
    case "edit_opt";
        tad_form_col_form($ofsn, $csn, $mode);
        break;

    //刪除欄位
    case "delete_tad_form_col";
        delete_tad_form_col($csn);
        header("location: {$_SERVER['PHP_SELF']}?op=edit_all_opt&ofsn={$ofsn}");
        exit;
        break;

    //編輯所有題目
    case "edit_all_opt";
        edit_all_opt($ofsn);
        break;

    //更新資料
    case "update_tad_form_col";
        update_tad_form_col($csn);
        if ($_POST['end'] == '1') {
            header("location: main.php");
            exit;
        } elseif ($_POST['mode'] == 'update') {
            header("location: ../index.php?op=sign&ofsn={$ofsn}");
            exit;
        } elseif ($_POST['mode'] == 'modify') {
            header("location: add.php?op=edit_all_opt&ofsn={$ofsn}");
            exit;
        } else {
            header("location: {$_SERVER['PHP_SELF']}?op=edit_opt&ofsn={$ofsn}");
            exit;
        }
        break;

    //新增欄位資料
    case "insert_tad_form_col":
        insert_tad_form_col();
        if ($_POST['end'] == '1') {
            set_form_status($ofsn, 1);
            header("location: main.php");
            exit;
        } else {
            header("location: {$_SERVER['PHP_SELF']}?op=edit_opt&ofsn={$ofsn}");
            exit;
        }
        break;

    //預設動作
    default:
        tad_form_main_form($ofsn);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
