<?php

use XoopsModules\Tad_form\Utility;

function xoops_module_update_tad_form(&$module, $old_version)
{
    global $xoopsDB;

    if (!Utility::chk_chk1()) {
        Utility::go_update1();
    }

    if (!Utility::chk_chk2()) {
        Utility::go_update2();
    }

    if (!Utility::chk_chk3()) {
        Utility::go_update3();
    }

    if (!Utility::chk_chk4()) {
        Utility::go_update4();
    }

    if (!Utility::chk_chk5()) {
        Utility::go_update5();
    }

    if (!Utility::chk_chk6()) {
        Utility::go_update6();
    }

    if (!Utility::chk_chk7()) {
        Utility::go_update7();
    }

    if (!Utility::chk_chk8()) {
        Utility::go_update8();
    }

    if (Utility::chk_uid()) {
        Utility::go_update_uid();
    }

    if (!Utility::chk_chk9()) {
        Utility::go_update9();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . "/modules/tad_form/fckeditor";
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
    }

    Utility::chk_tad_form_block();
    return true;
}

