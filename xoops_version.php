<?php

global $xoopsConfig;

$modversion = [];
global $xoopsConfig;

//---模組基本資訊---//
$modversion['name'] = _MI_TAD_FORM_NAME;
// $modversion['version'] = 3.7;
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '4.0.0-Stable' : '4.0';
$modversion['description'] = _MI_TAD_FORM_DESC;
$modversion['author'] = _MI_TAD_FORM_AUTHOR;
$modversion['credits'] = _MI_TAD_FORM_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2024-02-05';

$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5';

//---paypal資訊---//
$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

//---啟動後台管理界面選單---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
    'tad_form_col',
    'tad_form_fill',
    'tad_form_main',
    'tad_form_value',
    'tad_form_data_center',
    'tad_form_files_center',
];

//---資料表架構---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---安裝設定---//
$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---樣板設定---//
$modversion['templates'] = [
    ['file' => 'tad_form_index.tpl', 'description' => 'tad_form_index.tpl'],
    ['file' => 'tad_form_admin.tpl', 'description' => 'tad_form_admin.tpl'],
];

//---區塊設定---//
$modversion['blocks'] = [
    [
        'file' => 'tad_form.php',
        'name' => _MI_TAD_FORM_BNAME1,
        'description' => _MI_TAD_FORM_BDESC1,
        'show_func' => 'tad_form',
        'template' => 'tad_form.tpl',
    ],
    [
        'file' => 'tad_one_form.php',
        'name' => _MI_TAD_FORM_BNAME2,
        'description' => _MI_TAD_FORM_BDESC2,
        'show_func' => 'tad_one_form',
        'template' => 'tad_one_form.tpl',
        'edit_func' => 'tad_one_form_edit',
        'options' => '|1',
    ],
];

//---偏好設定---//
$modversion['config'] = [
    [
        'name' => 'show_amount',
        'title' => '_MI_TAD_FORM_SHOW_AMOUNT',
        'description' => '_MI_TAD_FORM_SHOW_AMOUNT_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => 1,
    ],
    [
        'name' => 'can_send_mail',
        'title' => '_MI_TAD_FORM_CAN_SEND_MAIL',
        'description' => '_MI_TAD_FORM_CAN_SEND_MAIL_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => 1,
    ],
];
