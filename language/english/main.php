<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';
define('_JS_EMAIL_CHK', 'Please check the E-mail address you offered again!');
define('_JS_SIGN_CHK', '"%s" is required!');
define('_JS_CHK_TOO_SHORT', 'Length of "%s" must be more than %s, please reset "%s"! Thank you!');
define('_JS_CHK_NOT_EQUAL', 'Length of "%s" must be %s, please reset "%s"! Thank you!');
define('_JS_CHK_TOO_LONG', 'Length of "%s" must be less than %s, please reset "%s"! Thank you!');

define('_MD_TADFORM_SUBMIT_FORM', 'Submit');
define('_MD_TADFORM_TITLE', 'Subject');
define('_MD_TADFORM_START_DATE', 'Start Date');
define('_MD_TADFORM_END_DATE', 'End Date');

define('_MD_TADFORM_FILL_TIME', 'Time');

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

define('_MD_TADFORM_ADD', 'Add Form');
define('_MD_TADFORM_MANAGER', 'Form Manager');

define('_MD_TADFORM_RESULT', 'Analysis');

define('_MD_INPUT_FORM', 'Form Designer');

define('_MD_TADFORM_OFSN', 'Number');
define('_MD_TADFORM_TITLE', 'Subject');
define('_MD_TADFORM_START_DATE', 'Start Date');
define('_MD_TADFORM_END_DATE', 'End Date');
define('_MD_TADFORM_CONTENT', 'Description');
define('_MD_TADFORM_UID', 'Author');
define('_MD_TADFORM_POST_DATE', 'Post Date');
define('_MD_TADFORM_ENABLE', 'Activate');
define('_MD_TADFORM_ADM_EMAIL', 'Admin Email');
define('_MD_TADFORM_EDIT_OPTION', 'Continue to add options');

define('_MD_INPUT_COL_FORM', 'Question');
define('_MD_TADFORM_CSN', 'Serial Number');
define('_MD_TADFORM_COL_TITLE', 'Title');
define('_MD_TADFORM_COL_DESCRIPT', 'Description');
define('_MD_TADFORM_COL_KIND', 'Type');
define('_MD_TADFORM_COL_SIZE', 'Length（Options）');
define('_MD_TADFORM_COL_VAL', 'Default');
define('_MD_TADFORM_COL_CHK', 'Required');
define('_MD_TADFORM_COL_FUNC', 'Statistics');
define('_MD_TADFORM_COL_SORT', 'Sort');
define('_MD_TADFORM_COL_TEXT', 'Text');
define('_MD_TADFORM_COL_RADIO', 'Mulitiple choice');
define('_MD_TADFORM_COL_CHECKBOX', 'Multiple choices');
define('_MD_TADFORM_COL_SELECT', 'Select Menu');
define('_MD_TADFORM_COL_TEXTAREA', 'Text area');
define('_MD_TADFORM_COL_DATE', 'Date');
define('_MD_TADFORM_COL_DATETIME', 'DateTime');
define('_MD_TADFORM_COL_SHOW', 'Note Only');
define('_MD_TADFORM_COL_SHOW_NOTE', 'Using the "Note Only", to fill note in the "Description"');

define('_MD_TADFORM_COL_NO_FUN', 'None');
define('_MD_TADFORM_COL_SUM', 'Sum');
define('_MD_TADFORM_COL_AVG', 'Average');
define('_MD_TADFORM_COL_COUNT', 'Count');

define('_MD_TADFORM_COL_END', 'Save with no more options');
define('_MD_TADFORM_COL_ACTIVE', 'Activate');
define('_MD_TADFORM_COL_ENABLE', 'Close');
define('_MD_TADFORM_COL_WHO', 'Name');
define('_MD_TADFORM_ANALYSIS', 'Statistics');
define('_MD_TADFORM_ANALYSIS_RESULT', 'Result');
define('_MD_TADFORM_COL_PUBLIC', 'Make item Public ');
define('_MD_TADFORM_COL_PUBLIC1', 'Public');
define('_MD_TADFORM_COL_PUBLIC0', 'Private');

define('_MD_TADFORM_COL_NOTE', 'Insert \';\' between each option.');
define('_MD_TADFORM_SIGN_GROUP', 'Available Groups');
define('_MD_TADFORM_VIEW_RESULT_GROUP', 'Who can view results');
define('_MD_TADFORM_MULTI_SIGN', 'Allow multiple fill?');
define('_MD_TADFORM_ANONYMOUS', 'Anonymous');
define('_MD_TADFORM_SIGN_MEMS', 'Total: %s');
define('_MD_TADFORM_SIGN_DATE', 'Time');
define('_MD_TADFORM_OPTIONS', 'Question(s)');
define('_MD_TADFORM_COPY_FORM', 'Copy');
define('_MD_TADFORM_COL_NUM', 'Number');
define('_MD_TADFORM_ADD_COL', 'Add');

define('_MD_TADFORM_FONT1', 'Arial');
define('_MD_TADFORM_LINK1', 'Link');
define('_MD_TADFORM_EXCEL_TITLE', 'Analysis of "%s"');
define('_MD_TADFORM_KIND', 'Purpose');
define('_MD_TADFORM_KIND0', 'Questionnaire');
define('_MD_TADFORM_KIND1', 'Application');
define('_MD_TADFORM_KIND2', 'Quiz');
define('_MD_TADFORM_KIND1_TH', 'Done?');
define('_MD_TADFORM_KIND1_OK', 'Recruited');
define('_MD_TADFORM_UPDATE_RESULT', 'Save');

define('_MD_TADFORM_USE_CAPTCHA', 'Use CAPTCHA?');
define('_MD_TADFORM_SHOW_RESULT', 'Show results?');
define('_MD_TADFORM_NOT_SHOW', 'Do not show results of this questionnaire.');

//update.php
define('_MD_TADFORM_AUTOUPDATE', 'Upgrade');
define('_MD_TADFORM_AUTOUPDATE_VER', 'Version');
define('_MD_TADFORM_AUTOUPDATE_DESC', 'Operation');
define('_MD_TADFORM_AUTOUPDATE_STATUS', 'Status');
define('_MD_TADFORM_AUTOUPDATE_GO', 'Upgrade');
define('_MD_TADFORM_AUTOUPDATE1', 'Add column \'kin\' in table \'tad_form_main\'');
define('_MD_TADFORM_AUTOUPDATE2', 'Add column \'result_col\' in table \'tad_form_fill\'');

//mail.php
define('_MD_TADFORM_SEND', 'Send');
define('_MD_TADFORM_MAIL_TITLE', 'Subject');
define('_MD_TADFORM_MAIL_TITLE_VAL', '"%s" Notification');
define('_MD_TADFORM_SEND_OK', 'Sent!');
define('_MD_TADFORM_SEND_ERROR', 'Failed to send!');
define('_MD_TADFORM_MAIL_TEST', 'Only test (does not send online display)');
define('_MD_TADFORM_SEND_TAG', 'Available tags (displays the user\'s answer)');
