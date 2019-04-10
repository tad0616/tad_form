<?php

use XoopsModules\Tad_form\Utility;

function xoops_module_uninstall_tad_form(&$module)
{
    global $xoopsDB;

    $date = date("Ymd");

    rename(XOOPS_ROOT_PATH . "/uploads/tad_form", XOOPS_ROOT_PATH . "/uploads/tad_form_bak_{$date}");

    return true;
}
