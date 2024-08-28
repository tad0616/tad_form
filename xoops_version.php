<?php

global $xoopsConfig;

$modversion = [];

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
$modversion['paypal'] = [];
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = 'tad_form_col';
$modversion['tables'][] = 'tad_form_fill';
$modversion['tables'][] = 'tad_form_main';
$modversion['tables'][] = 'tad_form_value';
$modversion['tables'][] = 'tad_form_data_center';
$modversion['tables'][] = 'tad_form_files_center';

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
$modversion['templates'] = [];
$i = 1;
$modversion['templates'][$i]['file'] = 'tad_form_index.tpl';
$modversion['templates'][$i]['description'] = 'tad_form_index.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_form_admin.tpl';
$modversion['templates'][$i]['description'] = 'tad_form_admin.tpl';

//---區塊設定---//
$modversion['blocks'] = [];
$modversion['blocks'][1]['file'] = 'tad_form.php';
$modversion['blocks'][1]['name'] = _MI_TAD_FORM_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TAD_FORM_BDESC1;
$modversion['blocks'][1]['show_func'] = 'tad_form';
$modversion['blocks'][1]['template'] = 'tad_form.tpl';

$modversion['blocks'][2]['file'] = 'tad_one_form.php';
$modversion['blocks'][2]['name'] = _MI_TAD_FORM_BNAME2;
$modversion['blocks'][2]['description'] = _MI_TAD_FORM_BDESC2;
$modversion['blocks'][2]['show_func'] = 'tad_one_form';
$modversion['blocks'][2]['template'] = 'tad_one_form.tpl';
$modversion['blocks'][2]['edit_func'] = 'tad_one_form_edit';
$modversion['blocks'][2]['options'] = '|1';

//---偏好設定---//
$modversion['config'][0]['name'] = 'show_amount';
$modversion['config'][0]['title'] = '_MI_TAD_FORM_SHOW_AMOUNT';
$modversion['config'][0]['description'] = '_MI_TAD_FORM_SHOW_AMOUNT_DESC';
$modversion['config'][0]['formtype'] = 'yesno';
$modversion['config'][0]['valuetype'] = 'int';
$modversion['config'][0]['default'] = 1;

$modversion['config'][1]['name'] = 'can_send_mail';
$modversion['config'][1]['title'] = '_MI_TAD_FORM_CAN_SEND_MAIL';
$modversion['config'][1]['description'] = '_MI_TAD_FORM_CAN_SEND_MAIL_DESC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 1;
