<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_form_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//列出所有tad_form_main資料
function list_tad_form_main()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $xoopsModule;
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    if ($xoopsUser) {
        $User_Groups = $xoopsUser->getGroups();
    } else {
        $User_Groups = [3];
    }
    $sql = 'select * from ' . $xoopsDB->prefix('tad_form_main') . " where enable='1' and start_date < '{$today}'  and end_date > '{$today}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $all = [];
    while (false !== ($data = $xoopsDB->fetchArray($result))) {
        foreach ($data as $k => $v) {
            $$k = $v;
        }

        $sql2 = 'select count(*) from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='{$ofsn}'";
        $result2 = $xoopsDB->query($sql2);
        list($counter) = $xoopsDB->fetchRow($result2);

        $start_date = date('Y-m-d', xoops_getUserTimestamp(strtotime($start_date)));
        $end_date = date('Y-m-d', xoops_getUserTimestamp(strtotime($end_date)));

        $multi_sign_pic = ('1' == $multi_sign) ? "<img src='images/report_check.png' align='absmiddle' hspace=6 alt='" . _MD_TADFORM_MULTI_SIGN . "' title='" . _MD_TADFORM_MULTI_SIGN . "'><span class='badge badge-success'>" . _MD_TADFORM_MULTI_SIGN . '</span> ' : '';

        $sign_group_arr = (empty($sign_group)) ? '' : explode(',', $sign_group);
        $sign_ok = false;
        if (!empty($sign_group_arr)) {
            foreach ($sign_group_arr as $group) {
                if (in_array($group, $User_Groups)) {
                    $sign_ok = true;
                    break;
                }
            }
        }
        $view_result_group_arr = (empty($view_result_group)) ? '' : explode(',', $view_result_group);
        $view_ok = false;
        if (!empty($view_result_group_arr)) {
            foreach ($view_result_group_arr as $group) {
                if (in_array($group, $User_Groups)) {
                    $view_ok = true;
                    break;
                }
            }
        }

        $all[$i]['sign_ok'] = $sign_ok;
        $all[$i]['view_ok'] = $view_ok;
        $all[$i]['ofsn'] = $ofsn;
        $all[$i]['title'] = $title;
        $all[$i]['counter'] = $counter;
        $all[$i]['start_date'] = $start_date;
        $all[$i]['end_date'] = $end_date;
        $all[$i]['content'] = $content;
        $all[$i]['uid'] = $uid;
        $all[$i]['post_date'] = $post_date;
        $all[$i]['enable'] = $enable;
        $all[$i]['multi_sign'] = $multi_sign_pic;
        $all[$i]['button'] = $xoopsModuleConfig['show_amount'] == 1 ? sprintf(_MD_TADFORM_SIGN_NOW, $title, $counter) : sprintf(_MD_TADFORM_SIGNNOW, $title);
        $all[$i]['date'] = sprintf(_MD_TADFORM_SIGN_DATE, $start_date, $end_date);
        $i++;
    }

    if (empty($all)) {
        $xoopsTpl->assign('op', 'error');
        $xoopsTpl->assign('title', '');
        $xoopsTpl->assign('msg', _MD_TADFORM_EMPTY);
    } else {
        $xoopsTpl->assign('jquery', Utility::get_jquery(true));
        $xoopsTpl->assign('all', $all);
    }
}

//儲存問卷
function save_val($ofsn = '', $ans = [])
{
    global $xoopsDB, $xoopsUser;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;

    $myts = \MyTextSanitizer::getInstance();
    $form = get_tad_form_main($ofsn);

    // if ('1' == $form['captcha']) {
    //     if ($_SESSION['security_code_' . $ofsn] != $_POST['security_images_' . $ofsn] or empty($_POST['security_images_' . $ofsn])) {
    //         redirect_header($_SERVER['PHP_SELF'] . "?op=sign&ofsn=$ofsn", 3, $_SESSION['security_code_' . $ofsn] . '!=' . $_POST['security_images_' . $ofsn] . _MD_TADFORM_CAPTCHA_ERROR);
    //     }

    //     unset($_SESSION['security_code_' . $ofsn]);
    // }

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $ssn = (int) $_POST['ssn'];
    $ofsn = (int) $_POST['ofsn'];
    $man_name = $myts->addSlashes($_POST['man_name']);
    $email = $myts->addSlashes($_POST['email']);

    // 不允許多次填寫時
    if ($form['multi_sign'] != 1) {
        $sql = 'select ssn from ' . $xoopsDB->prefix('tad_form_fill') . "  where `ofsn`='{$ofsn}' and `uid`='{$uid}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($ssn) = $xoopsDB->fetchRow($result);
        if ($ssn) {
            $sql = 'update ' . $xoopsDB->prefix('tad_form_fill') . " set `uid`='{$uid}',`man_name`='{$man_name}',`email`='{$email}',`fill_time`='{$now}' where `ssn`='{$ssn}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        } else {
            $sql = 'insert into ' . $xoopsDB->prefix('tad_form_fill') . " (`ofsn`,`uid`,`man_name`,`email`,`fill_time`,`result_col`,`code`) values('{$ofsn}','{$uid}','{$man_name}','{$email}', '{$now}','',md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, '$now')))";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $ssn = $xoopsDB->getInsertId();
        }
    } else {
        //先存基本資料
        $sql = 'replace into ' . $xoopsDB->prefix('tad_form_fill') . " (`ssn`,`ofsn`,`uid`,`man_name`,`email`,`fill_time`,`result_col`,`code`) values('{$ssn}','{$ofsn}','{$uid}','{$man_name}','{$email}', '{$now}','',md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, '$now')))";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $ssn = $xoopsDB->getInsertId();

    }

    $need_csn_arr = $_POST['need_csn'];

    //再存填寫資料
    foreach ($ans as $csn => $val) {
        $value = (is_array($val)) ? implode(';', $val) : $val;
        $value = $myts->addSlashes($value);
        $ssn = (int) $ssn;
        $sql = 'replace into ' . $xoopsDB->prefix('tad_form_value') . " (`ssn`,`csn`,`val`) values('{$ssn}','{$csn}','{$value}')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        unset($need_csn_arr[$csn]);
    }

    //把一些沒填的欄位也補上空值
    foreach ($need_csn_arr as $csn) {
        $ssn = (int) $ssn;
        $sql = 'replace into ' . $xoopsDB->prefix('tad_form_value') . " (`ssn`,`csn`,`val`) values('{$ssn}','{$csn}','')";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);
    }

    //產生code
    // $sql = 'update ' . $xoopsDB->prefix('tad_form_fill') . " set `code`=md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, `fill_time`)) where ssn='{$ssn}'";
    // $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'select `code` from ' . $xoopsDB->prefix('tad_form_fill') . " where ssn='{$ssn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($code) = $xoopsDB->fetchRow($result);

    return $code;
}

//立即寄出
function send_now($code = '')
{
    global $xoopsConfig, $xoopsDB;

    $xoopsMailer = &getMailer();
    $xoopsMailer->multimailer->ContentType = 'text/html';

    $sql = 'select a.`ofsn`,a.`man_name`,a.`email`, a.`fill_time`,a.`code`,b.`title`,b.`adm_email`  from ' . $xoopsDB->prefix('tad_form_fill') . ' as a left join ' . $xoopsDB->prefix('tad_form_main') . " as b on a.ofsn=b.ofsn where a.code='$code'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($ofsn, $man_name, $email, $fill_time, $code, $title, $adm_email) = $xoopsDB->fetchRow($result);

    $xoopsMailer->addHeaders('MIME-Version: 1.0');

    $all = view($code, 'mail');

    $fill_time = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($fill_time)));
    $content = sprintf(_MD_TADFORM_MAIL_CONTENT, $man_name, $fill_time, $title, $all, XOOPS_URL . "/modules/tad_form/index.php?op=view&code={$code}");
    $subject = sprintf(_MD_TADFORM_MAIL_TITLE, $title, $man_name, $fill_time);

    $sCharset = 'UTF-8';
    $sHeaders = "MIME-Version: 1.0\r\n" .
        "Content-type: text/html; charset=$sCharset\r\n";

    $email_arr = explode(';', $adm_email);
    $xoopsMailer->setFromEmail($email_arr[0]);

    if (!empty($email)) {
        if (!$xoopsMailer->sendMail($email, $subject, $content, $headers)) {
            mail($email, $subject, $content, $sHeaders);
        }
    }

    foreach ($email_arr as $email) {
        //$email=trim($email);
        if (!empty($email)) {
            if (!$xoopsMailer->sendMail($email, $subject, $content, $headers)) {
                mail($email, $subject, $content, $sHeaders);
            }
        }
    }
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$ofsn = system_CleanVars($_REQUEST, 'ofsn', 0, 'int');
$ssn = system_CleanVars($_REQUEST, 'ssn', 0, 'int');
$ans = system_CleanVars($_REQUEST, 'ans', '', 'array');
$code = system_CleanVars($_REQUEST, 'code', '', 'string');

switch ($op) {
    case 'sign':
        sign_form($ofsn, $ssn);
        // if ($isAdmin) {
        //     echo "<pre>";
        //     var_export($_SESSION);
        //     echo "</pre>";
        // }
        break;
    case 'delete_fill':
        delete_tad_form_ans($ssn);
        header("location:index.php?op=sign&ofsn={$ofsn}");
        exit;

    case 'save_val':
        $code = save_val($ofsn, $ans);
        send_now($code);
        redirect_header("index.php?op=view&code={$code}", 3, _MD_TADFORM_SAVE_OK);
        break;

    case 'view':
        view($code);
        break;

    default:
        list_tad_form_main();
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_form/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
