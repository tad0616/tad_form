<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_form_manager.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
if (!Utility::power_chk('tad_form_post', 1) and !$isAdmin) {
    redirect_header('index.php', 3, _TAD_PERMISSION_DENIED);
} else {
    $xoopsTpl->assign('now_uid', $xoopsUser->uid());
}

/*-----------function區--------------*/
//列出所有tad_form_main資料
function list_tad_form_main()
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_form_main') . ' ORDER BY post_date DESC';

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 20, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sign_mems = get_form_count();
    $cols_num = get_form_col_count();
    $i = 0;
    $form = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $pic = ('1' == $enable) ? '001_06.gif' : '001_05.gif';
        $enable_tool = ('1' == $enable) ? "<a href='manager.php?op=set_form_status&ofsn=$ofsn&enable=0'><img src='" . XOOPS_URL . "/modules/tad_form/images/$pic' align='absmiddle' hspace=6 alt='" . _MD_TADFORM_COL_ENABLE . "' title='" . _MD_TADFORM_COL_ENABLE . "'></a>" : "<a href='manager.php?op=set_form_status&ofsn=$ofsn&enable=1'><img src='" . XOOPS_URL . "/modules/tad_form/images/$pic' align='absmiddle' hspace=6 alt='" . _MD_TADFORM_COL_ACTIVE . "' title='" . _MD_TADFORM_COL_ACTIVE . "'></a>";
        $multi_sign_pic = ('1' == $multi_sign) ? "<img src='" . XOOPS_URL . "/modules/tad_form/images/report_check.png' align='absmiddle' hspace=6 alt='" . _MD_TADFORM_MULTI_SIGN . "' title='" . _MD_TADFORM_MULTI_SIGN . "'>" : '';
        $show_result_pic = ('1' == $show_result) ? "<img src='" . XOOPS_URL . "/modules/tad_form/images/report.png' align='absmiddle' hspace=6 alt='" . _MD_TADFORM_SHOW_RESULT . "' title='" . _MD_TADFORM_SHOW_RESULT . "'>" : '';

        $start_date = date('Y-m-d', xoops_getUserTimestamp(strtotime($start_date)));
        $end_date = date('Y-m-d', xoops_getUserTimestamp(strtotime($end_date)));

        $start_date = mb_substr($start_date, 0, 10);
        $end_date = mb_substr($end_date, 0, 10);

        $counter = (empty($sign_mems[$ofsn])) ? '0' : $sign_mems[$ofsn];

        $form[$i]['ofsn'] = $ofsn;
        $form[$i]['enable_tool'] = $enable_tool;
        $form[$i]['title'] = $title;
        $form[$i]['counter'] = sprintf(_MD_TADFORM_SIGN_MEMS, $counter);
        $form[$i]['start_date'] = $start_date;
        $form[$i]['end_date'] = $end_date;
        $form[$i]['cols_num'] = $cols_num[$ofsn];
        $form[$i]['multi_sign_pic'] = $multi_sign_pic;
        $form[$i]['show_result_pic'] = $show_result_pic;
        $form[$i]['uid'] = $uid;
        $i++;
    }
    if (empty($form)) {
        header('location:add.php');
    }

    $xoopsTpl->assign('form', $form);
    $xoopsTpl->assign('bar', $bar);
}

//檢查填報人數
function get_form_count()
{
    global $xoopsDB;
    $sql = 'SELECT ofsn,count(*) FROM ' . $xoopsDB->prefix('tad_form_fill') . ' GROUP BY ofsn';
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $counter = [];
    while (list($ofsn, $count) = $xoopsDB->fetchRow($result)) {
        $counter[$ofsn] = $count;
    }

    return $counter;
}

//檢查填報題數
function get_form_col_count()
{
    global $xoopsDB;
    $sql = 'SELECT ofsn,count(*) FROM ' . $xoopsDB->prefix('tad_form_col') . ' GROUP BY ofsn';
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $counter = [];
    while (list($ofsn, $count) = $xoopsDB->fetchRow($result)) {
        $counter[$ofsn] = $count;
    }

    return $counter;
}

//刪除tad_form_main某筆資料資料
function delete_tad_form_main($ofsn = '')
{
    global $xoopsDB, $xoopsUser, $isAdmin;

    $form = get_tad_form_main($ofsn);

    if (!$isAdmin and $form['uid'] != $xoopsUser->uid()) {
        redirect_header('index.php', 3, _TAD_PERMISSION_DENIED);
    }

    //先找出有哪些人填了
    $sql = 'select ssn from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='$ofsn'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($ssn) = $xoopsDB->fetchRow($result)) {
        //刪了填報內容
        $sql = 'delete from ' . $xoopsDB->prefix('tad_form_value') . " where ssn='$ssn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //刪了填寫人資料
        $sql = 'delete from ' . $xoopsDB->prefix('tad_form_fill') . " where ssn='$ssn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //刪掉欄位
    $sql = 'delete from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn='$ofsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //最後刪掉問卷
    $sql = 'delete from ' . $xoopsDB->prefix('tad_form_main') . " where ofsn='$ofsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//複製問卷
function copy_form($ofsn = '')
{
    global $xoopsDB;

    //讀出原有資料
    $sql = 'select `title`, `start_date`, `end_date`, `content`, `uid`, `post_date`, `enable`, `sign_group`, `kind`, `adm_email`, `captcha`, `show_result`, `view_result_group`, `multi_sign` from ' . $xoopsDB->prefix('tad_form_main') . " where ofsn='$ofsn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($title, $start_date, $end_date, $content, $uid, $post_date, $enable, $sign_group, $kind, $adm_email, $captcha, $show_result, $view_result_group, $multi_sign) = $xoopsDB->fetchRow($result);

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    //寫入新問卷
    $sql = 'insert into ' . $xoopsDB->prefix('tad_form_main') . " (`title`,`start_date`,`end_date`,`content`,`uid`,`post_date`,`enable`,`sign_group`, `kind`, `adm_email`, `captcha`, `show_result`, `view_result_group`, `multi_sign`) values('copy_{$title}','{$start_date}','{$end_date}','{$content}','{$uid}','{$now}','0','{$sign_group}','{$kind}','{$adm_email}','{$captcha}','{$show_result}','{$view_result_group}','{$multi_sign}')";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $new_ofsn = $xoopsDB->getInsertId();

    //讀出選項
    $sql = 'select `title`, `descript`, `kind`, `size`, `val`, `chk`, `func`, `sort` ,`public` from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn='$ofsn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($title, $descript, $kind, $size, $val, $chk, $func, $sort, $public) = $xoopsDB->fetchRow($result)) {
        //寫入選項
        $sql = 'insert into ' . $xoopsDB->prefix('tad_form_col') . " (`ofsn`,`title`,`descript`,`kind`,`size`,`val`,`chk`,`func`,`sort`,`public`) values('{$new_ofsn}','{$title}','{$descript}','{$kind}','{$size}','{$val}','{$chk}','{$func}','{$sort}','{$public}')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ofsn = Request::getInt('ofsn');

switch ($op) {
    case 'excel':
        download_excel($ofsn);
        exit;

    //刪除資料
    case 'delete_tad_form_main':
        delete_tad_form_main($ofsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //變更狀態資料
    case 'set_form_status':
        set_form_status($ofsn, $_GET['enable']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //複製問卷
    case 'copy':
        copy_form($ofsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        list_tad_form_main();
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('isAdmin', $isAdmin);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_form/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
