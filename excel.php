<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once "../../mainfile.php";
xoops_loadLanguage('admin', 'tad_form');

require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //引入 PHPExcel 物件庫
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //引入 PHPExcel_IOFactory 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel

$ofsn = isset($_REQUEST['ofsn']) ? (int) $_REQUEST['ofsn'] : 0;
$sql = 'select * from ' . $xoopsDB->prefix('tad_form_main') . " where ofsn='$ofsn'";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
$form_main = $xoopsDB->fetchArray($result);
$form_title = str_replace(['[', ']', ' '], '', $form_main['title']);
$ff = sprintf(_MD_TADFORM_EXCEL_TITLE, $form_title) . '.xlsx';
$dl_name = (_CHARSET === 'UTF-8') ? iconv('UTF-8', 'Big5', $ff) : $ff;
$dl_name = (false !== mb_strpos('MSIE', $_SERVER['HTTP_USER_AGENT'])) ? urlencode($dl_name) : $dl_name;

$objPHPExcel->setActiveSheetIndex(0); //設定預設顯示的工作表
$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
$objActSheet->setTitle('data'); //設定標題
$objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改

$objActSheet->setCellValue("A1", _MD_TADFORM_COL_WHO);
$objActSheet->setCellValue("B1", _MD_TADFORM_SIGN_DATE);

$sql = 'select csn,title,kind,func from ' . $xoopsDB->prefix('tad_form_col') . " where ofsn='{$ofsn}' order by sort";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
$n = 2;
while (list($csn, $title, $kind, $func) = $xoopsDB->fetchRow($result)) {
    if ('show' === $kind) {
        continue;
    }
    $col = num2alpha($n);
    $objActSheet->setCellValue("{$col}1", $title);
    $n++;
    $kk[$csn] = $kind;
}

$n = 2;
$sql = 'select ssn,uid,man_name,email,fill_time from ' . $xoopsDB->prefix('tad_form_fill') . " where ofsn='{$ofsn}' order by fill_time desc";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while (list($ssn, $uid, $man_name, $email, $fill_time) = $xoopsDB->fetchRow($result)) {
    $fill_time = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($fill_time)));
    $objActSheet->setCellValueByColumnAndRow(0, $n, $man_name);
    $objActSheet->setCellValueByColumnAndRow(1, $n, $fill_time);

    $sql2 = 'select a.csn,a.val from ' . $xoopsDB->prefix('tad_form_value') . ' as a,' . $xoopsDB->prefix('tad_form_col') . " as b where a.csn=b.csn and a.ssn='{$ssn}'  order by b.sort";
    $result2 = $xoopsDB->query($sql2) or Utility::web_error($sql2);

    $m = 2;
    while (list($csn, $val) = $xoopsDB->fetchRow($result2)) {
        if ('fck' === $kk[$csn]) {
            $val = strip_tags($val);
        }
        $objActSheet->setCellValueByColumnAndRow($m, $n, $val);
        $m++;
    }

    $n++;
}

//----------內容-----------//
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$dl_name}");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(false);
$objWriter->save('php://output');
exit;

function num2alpha($n)
{
    for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
        $r = chr($n % 26 + 0x41) . $r;
    }

    return $r;
}
