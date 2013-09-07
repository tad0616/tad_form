<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-06-25
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //
include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";

define("_TAD_NEED_TADTOOLS"," 需要 modules/tadtools，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");

define("_MA_TADFORM_RESULT","觀看結果");

define("_MA_INPUT_FORM","設計問卷表單");

define("_MA_TADFORM_OFSN","編號");
define("_MA_TADFORM_TITLE","問卷標題");
define("_MA_TADFORM_START_DATE","開始日期");
define("_MA_TADFORM_END_DATE","結束日期");
define("_MA_TADFORM_CONTENT","問卷說明");
define("_MA_TADFORM_UID","發佈者");
define("_MA_TADFORM_POST_DATE","發佈日期");
define("_MA_TADFORM_ENABLE","啟用狀態");
define("_MA_TADFORM_ADM_EMAIL","問卷管理Email");
define("_MA_TADFORM_EDIT_OPTION","接著編輯選項");

define("_MA_INPUT_COL_FORM","題目設定");
define("_MA_TADFORM_CSN","流水號");
define("_MA_TADFORM_COL_TITLE","問題");
define("_MA_TADFORM_COL_DESCRIPT","問題描述");
define("_MA_TADFORM_COL_KIND","呈現欄位種類");
define("_MA_TADFORM_COL_SIZE","欄位長度（或選項）");
define("_MA_TADFORM_COL_VAL","預設值");
define("_MA_TADFORM_COL_CHK","是否必填");
define("_MA_TADFORM_COL_FUNC","統計功能");
define("_MA_TADFORM_COL_SORT","排序");
define("_MA_TADFORM_COL_TEXT","文字框");
define("_MA_TADFORM_COL_RADIO","圓形單選鈕");
define("_MA_TADFORM_COL_CHECKBOX","方形複選鈕");
define("_MA_TADFORM_COL_SELECT","下拉選單");
define("_MA_TADFORM_COL_TEXTAREA","大量文字框");
define("_MA_TADFORM_COL_FCK","所見即所得文字框");
define("_MA_TADFORM_COL_SHOW","僅顯示純文字");
define("_MA_TADFORM_COL_SHOW_NOTE","選用「僅顯示純文字」時，請將欲顯示文字填寫在「問題描述」即可。");

define("_MA_TADFORM_COL_NO_FUN","不用");
define("_MA_TADFORM_COL_SUM","加總");
define("_MA_TADFORM_COL_AVG","平均");
define("_MA_TADFORM_COL_COUNT","計數");

define("_MA_TADFORM_COL_END","儲存後不再新增其他題目");
define("_MA_TADFORM_COL_ACTIVE","啟用");
define("_MA_TADFORM_COL_ENABLE","關閉");
define("_MA_TADFORM_COL_WHO","填報者");
define("_MA_TADFORM_ANALYSIS","統計分析");
define("_MA_TADFORM_ANALYSIS_RESULT","分析結果");
define("_MA_TADFORM_COL_PUBLIC","顯示結果時秀出此欄位");

define("_MA_TADFORM_COL_NOTE","選項請用「;」隔開。");
define("_MA_TADFORM_SIGN_GROUP","可填寫的群組");
define("_MA_TADFORM_VIEW_RESULT_GROUP","可看結果的群組");
define("_MA_TADFORM_MULTI_SIGN","允許多次填寫");
define("_MA_TADFORM_ANONYMOUS","匿名");
define("_MA_TADFORM_SIGN_MEMS","%s 人填寫");
define("_MA_TADFORM_SIGN_DATE","填寫日期");
define("_MA_TADFORM_OPTIONS","問卷題目");
define("_MA_TADFORM_COPY_FORM","複製");
define("_MA_TADFORM_COL_NUM","題數");
define("_MA_TADFORM_ADD_COL","新增題目");

define("_MA_TADFORM_FONT1","新細明體");
define("_MA_TADFORM_LINK1","超連結");
define("_MA_TADFORM_EXCEL_TITLE","「%s」表單結果");
define("_MA_TADFORM_KIND","表單用途");
define("_MA_TADFORM_KIND0","一般調查表單");
define("_MA_TADFORM_KIND1","報名表");
define("_MA_TADFORM_KIND2","線上測驗");
define("_MA_TADFORM_KIND1_TH","報名完成？");
define("_MA_TADFORM_KIND1_OK","錄取");
define("_MA_TADFORM_UPDATE_RESULT","儲存");

define("_MA_TADFORM_USE_CAPTCHA","是否使用驗證碼？");
define("_MA_TADFORM_SHOW_RESULT","顯示結果？");
define("_MA_TADFORM_NOT_SHOW","本填報並無公佈其結果。");

//update.php
define("_MA_TADFORM_AUTOUPDATE","模組升級");
define("_MA_TADFORM_AUTOUPDATE_VER","版本");
define("_MA_TADFORM_AUTOUPDATE_DESC","作用");
define("_MA_TADFORM_AUTOUPDATE_STATUS","更新狀況");
define("_MA_TADFORM_AUTOUPDATE_GO","立即更新");
define("_MA_TADFORM_AUTOUPDATE1","在 tad_form_main 資料表新增 kind 欄位");
define("_MA_TADFORM_AUTOUPDATE2","在 tad_form_fill 資料表新增 result_col 欄位");

//mail.php
define("_MA_TADFORM_SEND","寄出");
define("_MA_TADFORM_MAIL_TITLE","信件標題");
define("_MA_TADFORM_MAIL_TITLE_VAL","「%s」通知信");
define("_MA_TADFORM_SEND_OK","發送成功！");
define("_MA_TADFORM_SEND_ERROR","發送失敗！");
define("_MA_TADFORM_MAIL_TEST","僅測試（線上顯示不寄送）");
define("_MA_TADFORM_SEND_TAG","標籤（會秀出該使用者填的答案）");
?>