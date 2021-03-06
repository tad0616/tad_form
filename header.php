<?php
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

xoops_loadLanguage('global', basename(__DIR__));

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->mid();
    $isAdmin = $xoopsUser->isAdmin($module_id);
}

$interface_menu[_TAD_TO_MOD] = 'index.php';
if (Utility::power_chk('tad_form_post', 1) or $isAdmin) {
    $interface_menu[_MD_TADFORM_MANAGER] = 'manager.php';
    $interface_menu[_MD_TADFORM_ADD] = 'add.php';

    $interface_menu[_TAD_TO_ADMIN] = 'admin/index.php';
}
