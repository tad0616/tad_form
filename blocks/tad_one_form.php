<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\TadUpFiles')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
use XoopsModules\Tad_form\Tad_form_fill;
if (!class_exists('XoopsModules\Tad_form\Tad_form_fill')) {
    require XOOPS_ROOT_PATH . '/modules/tad_form/preloads/autoloader.php';
}
use XoopsModules\Tad_form\Tad_form_main;
if (!class_exists('XoopsModules\Tad_form\Tad_form_main')) {
    require XOOPS_ROOT_PATH . '/modules/tad_form/preloads/autoloader.php';
}
//區塊主函式 (列指定的調查表)
function tad_one_form($options)
{
    global $xoopsUser;
    $block = $fill = [];
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    if ($options[0]) {
        $ofsn = $options[0];
        if ($xoopsUser) {
            $fill = Tad_form_fill::get($ofsn, ['uid' => $xoopsUser->uid()]);
        }

        $block['form'] = Tad_form_main::get(['ofsn' => $ofsn, 'enable' => 1, "start_date < '{$today}'", "end_date > '{$today}'"], ['fill_count', 'can_fill', 'can_view_result']);
        $block['fill'] = $options[1];
        if ($options[1] == '1') {
            Tad_form_fill::create($ofsn, $fill['ssn'], $fill['code'], 'return');
        }
        Utility::test($block, 'tad_one_form');

    }

    return $block;
}

//區塊編輯函式
function tad_one_form_edit($options)
{
    global $xoopsDB;
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'SELECT `ofsn`, `title` FROM `' . $xoopsDB->prefix('tad_form_main') . '` WHERE `enable`=1 AND `start_date` < ? AND `end_date` > ?';
    $result = Utility::query($sql, 'ss', [$today, $today]) or Utility::web_error($sql, __FILE__, __LINE__);

    $opt = '';
    while (list($ofsn, $title) = $xoopsDB->fetchRow($result)) {
        $selected = ($ofsn == $options[0]) ? 'selected' : '';
        $opt .= "<option value='{$ofsn}' $selected>$title</option>\n";
    }
    $opt .= '';

    $opt1_1 = 1 == $options[1] ? 'checked' : '';
    $opt1_0 = 1 != $options[1] ? 'checked' : '';

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TAD_FORM_ONE_FORM_T1 . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input' size=5>
                {$opt}
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TAD_FORM_ONE_FORM_T2 . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[1]' id='opt1_1' value='1' $opt1_1>" . _YES . "
                <input type='radio' name='options[1]' id='opt1_0' value='0' $opt1_0>" . _NO . '
            </div>
        </li>
    </ol>
    ';

    return $form;
}
