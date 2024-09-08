<?php
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_form_admin.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/*-----------function區--------------*/
$module_id = $xoopsModule->mid();

$perm_desc = '';

$formi = new \XoopsGroupPermForm('', $module_id, 'tad_form_post', $perm_desc);
$formi->addItem('1', _MA_TAD_FORM_PUBLISH_PERMISSIONS);

$permission_content = $formi->render();
$xoopsTpl->assign('permission_content', $permission_content);

/*-----------秀出結果區--------------*/

require_once __DIR__ . '/footer.php';
