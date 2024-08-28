<?php
use XoopsModules\Tad_form\Tad_form_main;
if (!class_exists('XoopsModules\Tad_form\Tad_form_main')) {
    require XOOPS_ROOT_PATH . '/modules/tad_form/preloads/autoloader.php';
}

//區塊主函式 (列出目前執行中的線上調查表)
function tad_form($options)
{
    global $xoTheme;
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');

    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $block = Tad_form_main::get_all(['enable' => 1, "start_date < '{$today}'", "end_date > '{$today}'"], ['fill_count', 'can_fill', 'can_view_result'], [], ['post_date' => 'desc'], 'ofsn');

    return $block;
}
