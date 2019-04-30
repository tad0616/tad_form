<?php
xoops_loadLanguage('main', 'tadtools');
include_once 'global.php';
define('_JS_EMAIL_CHK', 'Please check the E-mail address you offered again!');
define('_JS_SIGN_CHK', '"%s" is required!');
define('_JS_CHK_TOO_SHORT', 'Length of "%s" must be more than %s, please reset "%s"! Thank you!');
define('_JS_CHK_NOT_EQUAL', 'Length of "%s" must be %s, please reset "%s"! Thank you!');
define('_JS_CHK_TOO_LONG', 'Length of "%s" must be less than %s, please reset "%s"! Thank you!');
define('_TAD_NEED_TADTOOLS', 'This module needs TadTools module. You can download TadTools from <a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS EasyGO</a>.');

define('_MD_TADFORM_SUBMIT_FORM', 'Submit');
define('_MD_TADFORM_TITLE', 'Subject');
define('_MD_TADFORM_START_DATE', 'Start Date');
define('_MD_TADFORM_END_DATE', 'End Date');
define('_MD_TADFORM_EDIT_FORM', 'Edit \'%s\'');

define('_MD_TADFORM_OFSN', 'Serial Number');
define('_MD_TADFORM_UID', 'UID');
define('_MD_TADFORM_MAN_NAME', 'Name');
define('_MD_TADFORM_EMAIL', 'E-mail');
define('_MD_TADFORM_FILL_TIME', 'Time');
define('_MD_TADFORM_NEED_SIGN', 'Required');
define('_MD_TADFORM_IS_NEED_SIGN', ' means Required');

define('_MD_TADFORM_SIGN_DATE', 'Date： <span class=\'start_date\'>%s</span> to <span class=\'end_date\'>%s</span>');
define('_MD_TADFORM_ONLY_MEM', 'You do NOT belong to authorized group(s)!!<br>Please login first.<br>If you can\'t still take this form with login. Just give up!');
define('_MD_TADFORM_KIND1_TH', 'Done?');
define('_MD_TADFORM_EMPTY', 'There are currently no forms to be filled!');

define('_MD_TADFORM_SIGN_NOW', 'Click Here for "%s"（Total: %s ）');
define('_MD_TADFORM_SIGNNOW', 'Click Here for "%s"');
define('_MD_TADFORM_MAIL_CONTENT', '%s (%s) filled out the form "%s", check followings：<br>%s<p align=\'right\'><a href="%s">Click Here for Complete Results</a></p>');
define('_MD_TADFORM_MAIL_TITLE', '"%s" Form Nitification： %s (%s)');
define('_MD_TADFORM_CAPTCHA_ERROR', 'Did not pass validation, can not be saved.');
define('_MD_TADFORM_SAVE_OK', 'Saved successfully');
define('_MD_TADFORM_COL_WHO', 'Name');
define('_MD_TADFORM_MULTI_SIGN', 'Allow multiple fill?');
define('_MD_TADFORM_EDIT_ALL', 'Edit all questions');
define('_MD_TADFORM_BACK_TO_FORM', 'Back to form');
define('_MD_TADFORM_HISTORY', 'History');
define('_MD_TADFORM_HIDE_RESULT', 'The result is not shown publicly.');
define('_MD_TADFORM_OK_LIST', 'Registration success list');
