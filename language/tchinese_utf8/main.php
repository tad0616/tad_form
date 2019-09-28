<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';
define('_TAD_NEED_TADTOOLS', ' 需要 modules/tadtools，可至<a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS輕鬆架</a>下載。');

define('_JS_EMAIL_CHK', 'Email不正確，請重新填寫');
define('_JS_SIGN_CHK', '請完整填寫『%s』欄位，謝謝！');
define('_JS_CHK_TOO_SHORT', '「%s」長度太短，不足 %s 個字，請重新設定『%s』欄位，謝謝！');
define('_JS_CHK_NOT_EQUAL', '「%s」長度不正確，不等於 %s 個字，請重新設定『%s』欄位，謝謝！');
define('_JS_CHK_TOO_LONG', '「%s」長度太長，超過 %s 個字，請重新設定『%s』欄位，謝謝！');

define('_MD_TADFORM_SUBMIT_FORM', '送出問卷');
define('_MD_TADFORM_TITLE', '問卷標題');
define('_MD_TADFORM_START_DATE', '開始日期');
define('_MD_TADFORM_END_DATE', '結束日期');

define('_MD_TADFORM_FILL_TIME', '填報時間');

define('_MD_TADFORM_SIGN_DATE', '填報日期：從 <span class="start_date">%s</span> 至<span class="end_date">%s</span>  止');
define('_MD_TADFORM_ONLY_MEM', '您並不屬於本填報允許的群組喔！！<br>若您尚未登入者請先登入。<br>已登入者就放棄吧！');
define('_MD_TADFORM_KIND1_TH', '報名完成？');
define('_MD_TADFORM_EMPTY', '目前沒有任何表單可以填寫！');

define('_MD_TADFORM_SIGN_NOW', '立即填報「%s」（目前已有 %s 人填寫）');
define('_MD_TADFORM_SIGNNOW', '立即填報「%s」');
define('_MD_TADFORM_MAIL_CONTENT', '%s 於 %s 填寫了「%s」，其內容如下：<br>%s<p align="right"><a href="%s">點選這裡可以觀看完整填寫結果</a></p>');
define('_MD_TADFORM_MAIL_TITLE', '「%s」填寫通知： %s (%s)');
define('_MD_TADFORM_CAPTCHA_ERROR', '未通過驗證，無法儲存。');
define('_MD_TADFORM_SAVE_OK', '您已填寫完畢！');
define('_MD_TADFORM_COL_WHO', '填報者');
define('_MD_TADFORM_MULTI_SIGN', '允許多次填寫');
define('_MD_TADFORM_EDIT_ALL', '編輯所有題目');
define('_MD_TADFORM_BACK_TO_FORM', '回原填報表');
define('_MD_TADFORM_HISTORY', '歷史填報紀錄');
define('_MD_TADFORM_HIDE_RESULT', '本填報不開放結果查詢。');
define('_MD_TADFORM_OK_LIST', '報名成功列表');
