<?php

use Xmf\Request;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_form\Tad_form_col;
use XoopsModules\Tad_form\Tad_form_fill;
use XoopsModules\Tad_form\Tad_form_main;
use XoopsModules\Tad_form\Tools;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_form_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ofsn = Request::getInt('ofsn');
$csn = Request::getInt('csn');
$files_sn = Request::getInt('files_sn');
$edit_option = Request::getInt('edit_option');
$stop_tad_col_create = Request::getInt('stop_tad_col_create');
$enable = Request::getInt('enable');
$mode = Request::getInt('mode');
$public = Request::getInt('public');
$chk = Request::getInt('chk');
$email_ssn = Request::getArray('email_ssn');
$title = Request::getString('title');
$content = Request::getText('content');
$send_test = Request::getInt('send_test');
$result_col = Request::getArray('result_col');
$admitted = Request::getInt('admitted');
$ssn = Request::getInt('ssn');

if ($ofsn) {
    Tools::chk_is_adm('my_form', $ofsn, __FILE__, __LINE__);
} else {
    Tools::chk_is_adm('tad_form_manager', '', __FILE__, __LINE__);
}

switch ($op) {
    //下載檔案
    case "tufdl":
        $TadUpFiles = new TadUpFiles("tad_form");
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    //預設動作
    case 'tad_form_main_create':
    case 'tad_form_main_form':
        // tad_form_main_form($ofsn);
        Tad_form_main::create($ofsn);
        $op = 'tad_form_main_create';
        break;

    //更新資料
    case 'tad_form_main_store':
        $ofsn = Tad_form_main::store();
        if ('1' == $edit_option) {
            header("location: manager.php?op=tad_form_col_create&ofsn=$ofsn");
            exit;
        }
        header('location: manager.php');
        exit;

    //更新資料
    case 'tad_form_main_update':
        $ofsn = Tad_form_main::update(['ofsn' => $ofsn]);
        if ('1' == $edit_option) {
            header("location: manager.php?op=tad_form_col_create&ofsn=$ofsn");
            exit;
        }
        header('location: manager.php');
        exit;

    //變更狀態資料
    case 'tad_form_main_update_enable':
        Tad_form_main::update(['ofsn' => $ofsn], ['enable' => $enable]);
        header("location: manager.php");
        exit;

    //複製表單
    case 'copy':
        Tad_form_main::copy($ofsn);
        header("location: manager.php");
        exit;

    //刪除資料
    case 'tad_form_main_destroy':
        $ofsn = Tad_form_main::destroy($ofsn, ['ofsn' => $ofsn]);
        header('location: manager.php');
        exit;

    //編輯題目
    case 'tad_form_col_create':
    case 'edit_opt':
        Tad_form_col::create($ofsn, $csn);
        break;

    //編輯所有題目
    case 'tad_form_col_index':
    case 'edit_all_opt':
        Tad_form_col::index($ofsn, ['ofsn' => $ofsn], [], [], ['sort' => 'asc']);
        break;

    //儲存題目
    case 'tad_form_col_store':
        $csn = Tad_form_col::store($ofsn);
        if ('1' == $stop_tad_col_create) {
            Tad_form_main::update(['ofsn' => $ofsn], ['enable' => 1]);
            header("location: manager.php?ofsn={$ofsn}");
            exit;
        }
        header("location: manager.php?op=tad_form_col_create&ofsn={$ofsn}");
        exit;

    //更新題目
    case 'tad_form_col_update':
        $csn = Tad_form_col::update($ofsn, ['csn' => $csn]);
        header("location: manager.php?op=tad_form_col_index&ofsn=$ofsn");
        exit;

    //更新欄位是否公開
    case 'change_public':
        Tad_form_col::update($ofsn, ['csn' => $csn], ['public' => $public]);
        header("location: manager.php?op=tad_form_col_index&ofsn=$ofsn");
        exit;

    //更新欄位是否檢查
    case 'change_chk':
        Tad_form_col::update($ofsn, ['csn' => $csn], ['chk' => $chk]);
        header("location: manager.php?op=tad_form_col_index&ofsn=$ofsn");
        exit;

    //刪除題目
    case 'tad_form_col_destroy':
        $csn = Tad_form_col::destroy($ofsn, ['csn' => $csn]);
        header("location: manager.php?op=tad_form_col_index&ofsn=$ofsn");
        exit;

    //觀看所有結果
    case 'tad_form_fill_index':
        Tad_form_fill::index($ofsn, ['ofsn' => $ofsn], ['ans', 'analysis', 'form'], [], ['fill_time' => 'desc'], 'ssn');
        $BootstrapTable = BootstrapTable::render();
        break;

    //寄信給填報者
    case 'tad_form_fill_mail':
        Tad_form_fill::mail($ofsn);
        break;

    //寄信給填報者
    case 'tad_form_fill_send':
        Tad_form_fill::send($ofsn, $email_ssn, $title, $content, $send_test);
        break;

    //更新結果
    case 'update_result':

        if (Tad_form_fill::update(['ssn' => $ssn], ['result_col' => $admitted])) {
            echo 'ok';
        }
        // foreach ($result_col as $ssn => $value) {
        //     Tad_form_fill::update(['ssn' => $ssn], ['result_col' => $value]);
        // }
        // header("location: manager.php?op=tad_form_fill_index&ofsn={$ofsn}");
        exit;

    //預設動作
    default:
        if ($ofsn) {
            $op = 'tad_form_fill_create';
            Tad_form_fill::create($ofsn);
            $op = 'tad_form_fill_create';
            break;
        } else {

            $op = 'tad_form_main_manager';
            $where_arr = [];
            if ($_SESSION['tad_form_adm']) {
            } elseif ($_SESSION['tad_form_manager']) {
                $where_arr['uid'] = $_SESSION['now_user']['uid'];
            }
            Tad_form_main::index($where_arr, ['fill_count', 'fill_count', 'col_count', 'sign_group_title', 'view_result_group_title'], [], ['post_date' => 'desc'], 'ofsn', 20);
        }

        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tad_form/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
