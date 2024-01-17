<?php
use XoopsModules\Tad_form\Tools;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

Tools::get_session();

$interface_menu[_TAD_TO_MOD] = 'index.php';
if (Tools::chk_is_adm('tad_form_manager', 1, __FILE__, __LINE__, 'return')) {
    $interface_menu[_MD_TAD_FORM_MANAGER] = 'manager.php';
    $interface_menu[_MD_TAD_FORM_ADD] = 'manager.php?op=tad_form_main_create';
    $interface_menu[_TAD_TO_ADMIN] = 'admin/index.php';
}
