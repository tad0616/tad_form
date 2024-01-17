<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_form\Tad_form_fill;
use XoopsModules\Tad_form\Tad_form_main;

//區塊主函式 (列指定的調查表)
function tad_one_form($options)
{
    $block = [];
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    if ($options[0]) {
        $ofsn = $options[0];

        $block['form'] = Tad_form_main::get(['ofsn' => $ofsn, 'enable' => 1, "start_date < '{$today}'", "end_date > '{$today}'"], ['fill_count', 'can_fill', 'can_view_result']);

        if ($options[1] == '1') {
            $block['fill'] = Tad_form_fill::create($ofsn, $ssn);
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
    $sql = 'select * from ' . $xoopsDB->prefix('tad_form_main') . " where enable='1' and start_date < '{$today}' and end_date > '{$today}'";
    $result = $xoopsDB->query($sql);

    $opt = '';
    while (list($ofsn, $title, $start_date, $end_date, $content, $uid, $post_date, $enable) = $xoopsDB->fetchRow($result)) {
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
