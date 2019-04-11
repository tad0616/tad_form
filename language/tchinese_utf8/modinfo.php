<?php
include_once XOOPS_ROOT_PATH . '/modules/tadtools/language/' . $xoopsConfig['language'] . '/modinfo_common.php';

define('_MI_TADFORM_NAME', '萬用表單');
define('_MI_TADFORM_AUTHOR', 'Tad');
define('_MI_TADFORM_CREDITS', 'tad');
define('_MI_TADFORM_DESC', '簡易的線上表單模組');
define('_MI_TADFORM_ADMENU1', '線上表單列表');
define('_MI_TADFORM_ADMENU2', '新增線上表單');
define('_MI_TADFORM_ADMENU3', '資料庫架構更新');
define('_MI_TADFORM_BNAME1', '最新線上表單');
define('_MI_TADFORM_BDESC1', '列出目前執行中的線上表單');
define('_MI_TADFORM_BNAME2', '線上表單');
define('_MI_TADFORM_BDESC2', '列出指定的線上表單');

define('_MI_TADFORM_SHOW_AMOUNT', '前台是否顯示已有幾人填寫');
define('_MI_TADFORM_SHOW_AMOUNT_DESC', '前台的填報按鈕上是否顯示已有幾人填寫？');

define('_MI_TADFORM_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TADFORM_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_TADFORM_BACK_2_ADMIN', '管理');

//help
define('_MI_TADFORM_HELP_OVERVIEW', '概要');
