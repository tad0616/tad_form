<?php

use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_form\Update;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tad_form\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_tad_form(&$module, $old_version)
{
    Update::go_update_size();
    Update::chk_add_files_center();
    Update::chk_add_data_center();
    if (!Update::chk_chk1()) {
        Update::go_update1();
    }

    if (!Update::chk_chk2()) {
        Update::go_update2();
    }

    if (!Update::chk_chk3()) {
        Update::go_update3();
    }

    if (!Update::chk_chk4()) {
        Update::go_update4();
    }

    if (!Update::chk_chk5()) {
        Update::go_update5();
    }

    if (!Update::chk_chk6()) {
        Update::go_update6();
    }

    if (!Update::chk_chk7()) {
        Update::go_update7();
    }

    if (!Update::chk_chk8()) {
        Update::go_update8();
    }

    if (Update::chk_uid()) {
        Update::go_update_uid();
    }

    if (!Update::chk_chk9()) {
        Update::go_update9();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . '/modules/tad_form/fckeditor';
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
    }

    Update::chk_tad_form_block();

    //修正區塊索引
    if (Update::chk_chk10()) {
        Update::go_update10();
    }
    return true;
}
