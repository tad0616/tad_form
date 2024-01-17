<?php
xoops_loadLanguage('modinfo_common', 'tadtools');

define('_MI_TAD_FORM_NAME', '萬用表單');
define('_MI_TAD_FORM_AUTHOR', 'Tad');
define('_MI_TAD_FORM_CREDITS', 'tad');
define('_MI_TAD_FORM_DESC', '簡易的線上表單模組');
define('_MI_TAD_FORM_ADMENU1', '權限設定');
define('_MI_TAD_FORM_BNAME1', '最新線上表單');
define('_MI_TAD_FORM_BDESC1', '列出目前執行中的線上表單');
define('_MI_TAD_FORM_BNAME2', '線上表單');
define('_MI_TAD_FORM_BDESC2', '列出指定的線上表單');

define('_MI_TAD_FORM_SHOW_AMOUNT', '前台是否顯示已有幾人填寫');
define('_MI_TAD_FORM_SHOW_AMOUNT_DESC', '前台的填報按鈕上是否顯示已有幾人填寫？');

define('_MI_TAD_FORM_CAN_SEND_MAIL', '是否寄發通知信？');
define('_MI_TAD_FORM_CAN_SEND_MAIL_DESC', '若系統無法寄信請關閉');

define('_MI_TAD_FORM_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TAD_FORM_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_TAD_FORM_BACK_2_ADMIN', '管理');

//help
define('_MI_TAD_FORM_HELP_OVERVIEW', '概要');
