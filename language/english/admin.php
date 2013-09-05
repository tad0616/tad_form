<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-06-25
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //
include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";
define("_TAD_NEED_TADTOOLS","Need modules/tadtools. You can download tadtools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");

define("_MA_TADFORM_RESULT","Analysis");

define("_MA_INPUT_FORM","Form Designer");

define("_MA_TADFORM_OFSN","Number");
define("_MA_TADFORM_TITLE","Subject");
define("_MA_TADFORM_START_DATE","Start Date");
define("_MA_TADFORM_END_DATE","End Date");
define("_MA_TADFORM_CONTENT","Description");
define("_MA_TADFORM_UID","Author");
define("_MA_TADFORM_POST_DATE","Post Date");
define("_MA_TADFORM_ENABLE","Activate");
define("_MA_TADFORM_ADM_EMAIL","Admin Email");
define("_MA_TADFORM_EDIT_OPTION","Continue to add options");

define("_MA_INPUT_COL_FORM","Question");
define("_MA_TADFORM_CSN","Serial Number");
define("_MA_TADFORM_COL_TITLE","Title");
define("_MA_TADFORM_COL_DESCRIPT","Description");
define("_MA_TADFORM_COL_KIND","Type");
define("_MA_TADFORM_COL_SIZE","Length（Options）");
define("_MA_TADFORM_COL_VAL","Default");
define("_MA_TADFORM_COL_CHK","Required");
define("_MA_TADFORM_COL_FUNC","Statistics");
define("_MA_TADFORM_COL_SORT","Sort");
define("_MA_TADFORM_COL_TEXT","Text");
define("_MA_TADFORM_COL_RADIO","Mulitiple choice");
define("_MA_TADFORM_COL_CHECKBOX","Multiple choices");
define("_MA_TADFORM_COL_SELECT","Select Menu");
define("_MA_TADFORM_COL_TEXTAREA","Text area");
define("_MA_TADFORM_COL_FCK","FCK Editor");
define("_MA_TADFORM_COL_SHOW","Note Only");
define("_MA_TADFORM_COL_SHOW_NOTE","Using the \"Note Only\", to fill note in the \"Description\"");

define("_MA_TADFORM_COL_NO_FUN","None");
define("_MA_TADFORM_COL_SUM","Sum");
define("_MA_TADFORM_COL_AVG","Average");
define("_MA_TADFORM_COL_COUNT","Count");

define("_MA_TADFORM_COL_END","Save with no more options");
define("_MA_TADFORM_COL_ACTIVE","Activate");
define("_MA_TADFORM_COL_ENABLE","Close");
define("_MA_TADFORM_COL_WHO","Name");
define("_MA_TADFORM_ANALYSIS","Statistics");
define("_MA_TADFORM_ANALYSIS_RESULT","Result");
define("_MA_TADFORM_COL_PUBLIC","Public this item");

define("_MA_TADFORM_COL_NOTE","Insert 「;」 between each option.");
define("_MA_TADFORM_SIGN_GROUP","Available Group");
define("_MA_TADFORM_VIEW_RESULT_GROUP","Who can view results");
define("_MA_TADFORM_MULTI_SIGN","Allow multiple fill?");
define("_MA_TADFORM_ANONYMOUS","Anonymous");
define("_MA_TADFORM_SIGN_MEMS","Total: %s");
define("_MA_TADFORM_SIGN_DATE","Time");
define("_MA_TADFORM_OPTIONS","Question(s)");
define("_MA_TADFORM_COPY_FORM","Copy");
define("_MA_TADFORM_COL_NUM","Number");
define("_MA_TADFORM_ADD_COL","Add");

define("_MA_TADFORM_FONT1","Arial");
define("_MA_TADFORM_LINK1","Link");
define("_MA_TADFORM_EXCEL_TITLE","Analysis of 「%s」");
define("_MA_TADFORM_KIND","Purpose");
define("_MA_TADFORM_KIND0","Questionnaire");
define("_MA_TADFORM_KIND1","Application");
define("_MA_TADFORM_KIND2","Quiz");
define("_MA_TADFORM_KIND1_TH","Done?");
define("_MA_TADFORM_KIND1_OK","Recruited");
define("_MA_TADFORM_UPDATE_RESULT","Save");
define("_MA_TREETABLE_MOVE_PIC","Drag to the destination category.");
define("_MA_TREETABLE_SORT_PIC","Drag up or down to set the sort of categories");
define("_MA_TREETABLE_MOVE_ERROR1","Move fail! Source and destination category can not be the same.");
define("_MA_TREETABLE_MOVE_ERROR2","Move fail! Destination category can not be under the source category.");
define("_MA_TADFORM_USE_CAPTCHA","Use CAPTCHA?");
define("_MA_TADFORM_SHOW_RESULT","To display the results?");
define("_MA_TADFORM_NOT_SHOW","Not published the results of this questionnaire.");

//update.php
define("_MA_TADFORM_AUTOUPDATE","Upgrade");
define("_MA_TADFORM_AUTOUPDATE_VER","Version");
define("_MA_TADFORM_AUTOUPDATE_DESC","Operation");
define("_MA_TADFORM_AUTOUPDATE_STATUS","Status");
define("_MA_TADFORM_AUTOUPDATE_GO","Upgrade");
define("_MA_TADFORM_AUTOUPDATE1","add column 'kin' in table 'tad_form_main'");
define("_MA_TADFORM_AUTOUPDATE2","add column 'result_col' in table 'tad_form_fill'");

//mail.php
define("_MA_TADFORM_SEND","Send");
define("_MA_TADFORM_MAIL_TITLE","Subject");
define("_MA_TADFORM_MAIL_TITLE_VAL","「%s」 Notification");
define("_MA_TADFORM_SEND_OK","sent!");
define("_MA_TADFORM_SEND_ERROR","failed to send!");
define("_MA_TADFORM_MAIL_TEST","Only test (does not send online display)");
define("_MA_TADFORM_SEND_TAG","Available tags (displays the user's answer)");
?>