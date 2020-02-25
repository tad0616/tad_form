<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';

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

define('_MD_TADFORM_ADD', '新增表單');
define('_MD_TADFORM_MANAGER', '表單管理');

define('_MD_TADFORM_RESULT', '觀看結果');
define('_MD_INPUT_FORM', '設計問卷表單');

define('_MD_TADFORM_OFSN', '編號');
define('_MD_TADFORM_TITLE', '問卷標題');
define('_MD_TADFORM_START_DATE', '開始日期');
define('_MD_TADFORM_END_DATE', '結束日期');
define('_MD_TADFORM_CONTENT', '問卷說明');
define('_MD_TADFORM_UID', '發佈者');
define('_MD_TADFORM_POST_DATE', '發佈日期');
define('_MD_TADFORM_ENABLE', '啟用狀態');
define('_MD_TADFORM_ADM_EMAIL', '問卷管理Email');
define('_MD_TADFORM_EDIT_OPTION', '接著編輯選項');

define('_MD_INPUT_COL_FORM', '題目設定');
define('_MD_TADFORM_CSN', '流水號');
define('_MD_TADFORM_COL_TITLE', '問題');
define('_MD_TADFORM_COL_DESCRIPT', '問題描述');
define('_MD_TADFORM_COL_KIND', '種類');
define('_MD_TADFORM_COL_SIZE', '欄位長度（或選項）');
define('_MD_TADFORM_COL_VAL', '預設值');
define('_MD_TADFORM_COL_CHK', '必填？');
define('_MD_TADFORM_COL_FUNC', '統計');
define('_MD_TADFORM_COL_SORT', '排序');
define('_MD_TADFORM_COL_TEXT', '文字框');
define('_MD_TADFORM_COL_RADIO', '圓形單選鈕');
define('_MD_TADFORM_COL_CHECKBOX', '方形複選鈕');
define('_MD_TADFORM_COL_SELECT', '下拉選單');
define('_MD_TADFORM_COL_TEXTAREA', '大量文字框');
define('_MD_TADFORM_COL_DATE', '日期輸入框');
define('_MD_TADFORM_COL_DATETIME', '日期＋時間輸入框');
define('_MD_TADFORM_COL_SHOW', '僅顯示純文字');
define('_MD_TADFORM_COL_SHOW_NOTE', '選用「僅顯示純文字」時，請將欲顯示文字填寫在「問題描述」即可。');

define('_MD_TADFORM_COL_NO_FUN', '不用');
define('_MD_TADFORM_COL_SUM', '加總');
define('_MD_TADFORM_COL_AVG', '平均');
define('_MD_TADFORM_COL_COUNT', '計數');

define('_MD_TADFORM_COL_END', '儲存後不再新增其他題目');
define('_MD_TADFORM_COL_ACTIVE', '啟用');
define('_MD_TADFORM_COL_ENABLE', '關閉');
define('_MD_TADFORM_COL_WHO', '填報者');
define('_MD_TADFORM_ANALYSIS', '統計分析');
define('_MD_TADFORM_ANALYSIS_RESULT', '分析結果');
define('_MD_TADFORM_COL_PUBLIC', '結果');
define('_MD_TADFORM_COL_PUBLIC1', '顯示');
define('_MD_TADFORM_COL_PUBLIC0', '不顯示');

define('_MD_TADFORM_COL_NOTE', '選項請用「;」隔開。');
define('_MD_TADFORM_SIGN_GROUP', '可填寫的群組');
define('_MD_TADFORM_VIEW_RESULT_GROUP', '可看結果的群組');
define('_MD_TADFORM_MULTI_SIGN', '允許多次填寫');
define('_MD_TADFORM_ANONYMOUS', '匿名');
define('_MD_TADFORM_SIGN_MEMS', '%s 人填寫');
define('_MD_TADFORM_SIGN_DATE', '填寫日期');
define('_MD_TADFORM_OPTIONS', '問卷題目');
define('_MD_TADFORM_COPY_FORM', '複製');
define('_MD_TADFORM_COL_NUM', '題數');
define('_MD_TADFORM_ADD_COL', '新增題目');

define('_MD_TADFORM_FONT1', '新細明體');
define('_MD_TADFORM_LINK1', '超連結');
define('_MD_TADFORM_EXCEL_TITLE', '「%s」表單結果');
define('_MD_TADFORM_KIND', '表單用途');
define('_MD_TADFORM_KIND0', '一般調查表單');
define('_MD_TADFORM_KIND1', '報名表');
define('_MD_TADFORM_KIND2', '線上測驗');
define('_MD_TADFORM_KIND1_TH', '報名完成？');
define('_MD_TADFORM_KIND1_OK', '錄取');
define('_MD_TADFORM_UPDATE_RESULT', '儲存');

define('_MD_TADFORM_USE_CAPTCHA', '是否使用驗證碼？');
define('_MD_TADFORM_SHOW_RESULT', '顯示結果？');
define('_MD_TADFORM_NOT_SHOW', '本填報並無公佈其結果。');

//update.php
define('_MD_TADFORM_AUTOUPDATE', '模組升級');
define('_MD_TADFORM_AUTOUPDATE_VER', '版本');
define('_MD_TADFORM_AUTOUPDATE_DESC', '作用');
define('_MD_TADFORM_AUTOUPDATE_STATUS', '更新狀況');
define('_MD_TADFORM_AUTOUPDATE_GO', '立即更新');
define('_MD_TADFORM_AUTOUPDATE1', '在 tad_form_main 資料表新增 kind 欄位');
define('_MD_TADFORM_AUTOUPDATE2', '在 tad_form_fill 資料表新增 result_col 欄位');

//mail.php
define('_MD_TADFORM_SEND', '寄出');
define('_MD_TADFORM_MAIL_TITLE', '信件標題');
define('_MD_TADFORM_MAIL_TITLE_VAL', '「%s」通知信');
define('_MD_TADFORM_SEND_OK', '發送成功！');
define('_MD_TADFORM_SEND_ERROR', '發送失敗！');
define('_MD_TADFORM_MAIL_TEST', '僅測試（線上顯示不寄送）');
define('_MD_TADFORM_SEND_TAG', '標籤（會秀出該使用者填的答案）');
