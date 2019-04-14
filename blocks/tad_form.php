<?php
//區塊主函式 (列出目前執行中的線上調查表)
function tad_form($options)
{
    global $xoopsDB, $xoTheme;
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');

    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'select * from ' . $xoopsDB->prefix('tad_form_main') . " where enable='1' and start_date < '{$today}' and end_date > '{$today}'";
    $result = $xoopsDB->query($sql) or die($sql);
    $i = 0;
    $block = [];
    while (list($ofsn, $title, $start_date, $end_date, $content, $uid, $post_date, $enable) = $xoopsDB->fetchRow($result)) {
        //$block.="<a href='".XOOPS_URL."/modules/tad_form/index.php?op=sign&ofsn=$ofsn' >$title</a>";
        $block['form'][$i]['ofsn'] = $ofsn;
        $block['form'][$i]['title'] = $title;
        $i++;
    }

    return $block;
}
