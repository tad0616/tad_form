<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_form_adm_result.tpl';
include_once 'header.php';
include_once '../function.php';

/*-----------function區--------------*/

//觀看結果
function view_result($ofsn = '', $isAdmin = false, $view_ssn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;

    $form = get_tad_form_main($ofsn);
    if (!$isAdmin) {
        if ('1' != $form['show_result'] or '1' != $form['enable']) {
            redirect_header('index.php', 3, _MA_TADFORM_NOT_SHOW);
        }
    }
    $myts = MyTextSanitizer::getInstance();
    $thSty = "style='width:135px;'";

    $jquery_path = get_jquery(); //一般只要此行即可

    $xoopsTpl->assign('jquery_path', $jquery_path);
    $xoopsTpl->assign('ofsn', $ofsn);

    $sql = 'select csn,title,kind,func from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn='{$ofsn}' order by sort";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $all_title = $tt = $tt = $kk = $csn_arr = [];
    $i = 0;
    while (list($csn, $title, $kind, $func) = $xoopsDB->fetchRow($result)) {
        if ('show' == $kind) {
            continue;
        }

        //$all_title.="<th $thSty>$title</th>";
        $all_title[$i]['title'] = $title;
        $i++;
        $ff[$csn] = $func;
        $tt[$csn] = $title;
        $kk[$csn] = $kind;
        $csn_arr[] = $csn;
    }

    $all_csn = implode(',', $csn_arr);

    //根據不同表單類型，提供不同的功能
    if ('application' == $form['kind']) {
        $other_fun_th = "<th $thSty>" . _MA_TADFORM_KIND1_TH . '</th>';
    } else {
        $other_fun_th = '';
    }

    $funct_title = ($isAdmin) ? "<th $thSty>" . _TAD_FUNCTION . "</th>$other_fun_th" : '';

    $xoopsTpl->assign('all_title', $all_title);
    $xoopsTpl->assign('funct_title', $funct_title);
    $xoopsTpl->assign('thSty', $thSty);

    $sql = 'select ssn,uid,man_name,email,fill_time,code,result_col from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='{$ofsn}' order by fill_time desc";

    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $col_v = $col = [];
    while (list($ssn, $uid, $man_name, $email, $fill_time, $code, $result_col) = $xoopsDB->fetchRow($result)) {
        $url = "{$_SERVER['PHP_SELF']}?op=view&code=$code";
        $all_result_col[$i]['url'] = $myts->htmlSpecialChars($url);
        $all_result_col[$i]['man_name'] = $myts->htmlSpecialChars($man_name);

        $sql2 = 'select csn,val from ' . $xoopsDB->prefix('tad_form_value') . "  where ssn='{$ssn}'";

        $result2 = $xoopsDB->query($sql2) or web_error($sql2);

        while (list($csn, $val) = $xoopsDB->fetchRow($result2)) {
            $col_v[$csn] = $myts->htmlSpecialChars($val);
        }

        $n = 0;
        foreach ($csn_arr as $csn) {
            if ('textarea' == $kk[$csn]) {
                $csn_val = nl2br($col_v[$csn]);
            } elseif ('checkbox' == $kk[$csn]) {
                $csn_val = (empty($col_v[$csn])) ? '' : '<ul><li>' . str_replace(';', '</li><li>', $col_v[$csn]) . '</li></ul>';
            } else {
                $csn_val = $col_v[$csn];
            }
            $ans_col[$n]['val'] = $csn_val;
            $n++;

            if ('sum' == $ff[$csn]) {
                $col[$csn]['sum'] += (int) $col_v[$csn];
            } elseif ('count' == $ff[$csn]) {
                $val_arr = explode(';', $col_v[$csn]);
                foreach ($val_arr as $v) {
                    $col[$csn]['count'][$v]++;
                }
            } else {
                $col[$csn]['sum'] += $col_v[$csn];
                $col[$csn]['count']++;
            }
        }

        $all_result_col[$i]['ans'] = $ans_col;

        //根據不同表單類型，提供不同的功能
        if ('application' == $form['kind']) {
            $checked = ('1' == $result_col) ? 'checked' : '';
            $other_fun = "<td nowrap>
			<input type='hidden' name='ofsn' value='$ofsn'>
			<input type='hidden' name='ssn[]' value='$ssn'>
			<input type='checkbox' name='result_col[$ssn]' value='1' $checked>" . _MA_TADFORM_KIND1_OK . '</td>';
        } else {
            $other_fun = '';
        }

        $funct = ($isAdmin) ? "<td><a href=\"javascript:delete_tad_form_ans($ssn);\"><img src='" . XOOPS_URL . "/modules/tad_form/images/001_05.gif' alt='" . _TAD_DEL . "'></a></td>
		$other_fun" : '';

        $all_result_col[$i]['funct'] = $funct;

        $fill_time = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($fill_time)));
        $all_result_col[$i]['fill_time'] = $fill_time;

        $i++;
    }

    $xoopsTpl->assign('result_col', $all_result_col);

    $submit = ('application' == $form['kind'] and $isAdmin) ? "
	<p align='right'><input type='hidden' name='op' value='update_result'>
	<input type='submit' value='" . _MA_TADFORM_UPDATE_RESULT . "'></p>" : '';
    $xoopsTpl->assign('submit', $submit);

    $analysis = [];
    $i = 0;
    $allval = '';
    foreach ($ff as $csn => $func) {
        if (empty($func)) {
            continue;
        }

        $analysis[$i]['title'] = $tt[$csn];
        $allval = '';
        if ('sum' == $func) {
            $analysis[$i]['func'] = _MA_TADFORM_COL_SUM;
            $analysis[$i]['val'] = $col[$csn]['sum'];
        } elseif ('count' == $func) {
            $analysis[$i]['func'] = _MA_TADFORM_COL_COUNT;
            $val = '';
            if (isset($col[$csn]['count']) and is_array($col[$csn]['count'])) {
                foreach ($col[$csn]['count'] as $val => $count) {
                    $allval .= "$val : $count<br>";
                }
            }
            $analysis[$i]['val'] = $allval;
        } elseif ('avg' == $func) {
            $avg = round($col[$csn]['sum'] / $col[$csn]['count'], 2);
            $analysis[$i]['func'] = _MA_TADFORM_COL_AVG;
            $analysis[$i]['val'] = $avg;
        } else {
            $analysis[$i]['func'] = '';
            $analysis[$i]['val'] = '';
        }
        $i++;
    }
    // exit;

    $xoopsTpl->assign('form_title', $form['title']);
    $xoopsTpl->assign('analysis', $analysis);
    $xoopsTpl->assign('view_ssn', $view_ssn);

    if ($view_ssn) {
        $sql = 'select code from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='{$ofsn}' and ssn='{$view_ssn}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($code) = $xoopsDB->fetchRow($result);
        view($code);
    }
}

//更新結果
function update_result($ssn_arr = [], $result_col = [])
{
    global $xoopsDB;
    foreach ($ssn_arr as $ssn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_form_fill') . " set result_col='{$result_col[$ssn]}'  where ssn='$ssn'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$ofsn = system_CleanVars($_REQUEST, 'ofsn', 0, 'int');
$ssn = system_CleanVars($_REQUEST, 'ssn', 0, 'int');

switch ($op) {
    //更新結果
    case 'update_result':
        update_result($_POST['ssn'], $_POST['result_col']);
        header("location: {$_SERVER['PHP_SELF']}?ofsn={$ofsn}");
        exit;

    case 'view':
        view_result($ofsn, true, $ssn);
        break;
    //刪除資料
    case 'delete_tad_form_ans':
        delete_tad_form_ans($ssn);
        header("location: {$_SERVER['PHP_SELF']}?ofsn={$ofsn}");
        exit;

    //預設動作
    default:
        view_result($ofsn, true);
        break;
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';
