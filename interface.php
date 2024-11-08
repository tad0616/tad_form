<?php
use XoopsModules\Tad_form\Tools;
if (!class_exists('XoopsModules\Tad_form\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tad_form/preloads/autoloader.php';
}

Tools::get_session();

$interface_menu[_MD_TAD_FORM_LIST] = 'index.php';
$interface_icon[_MD_TAD_FORM_LIST] = "fa-check-square-o";

if (Tools::chk_is_adm('tad_form_manager', 1, __FILE__, __LINE__, 'return') or $_SERVER['PHP_SELF'] == '/admin.php') {
    $interface_menu[_MD_TAD_FORM_MANAGER] = 'manager.php';
    $interface_icon[_MD_TAD_FORM_MANAGER] = "fa-pencil-square";

    $interface_menu[_MD_TAD_FORM_ADD] = 'manager.php?op=tad_form_main_create';
    $interface_icon[_MD_TAD_FORM_ADD] = "fa-plus-square";
}
