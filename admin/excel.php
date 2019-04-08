<?php
//include_once "header.php";
include_once "../../../mainfile.php";
include_once "../language/{$xoopsConfig['language']}/admin.php";
include_once "../function.php";

include '../../tadtools/PHPExcel.php'; //引入 PHPExcel 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel

$ofsn       = isset($_REQUEST['ofsn']) ? (int)$_REQUEST['ofsn'] : 0;
$form_main  = get_tad_form_main($ofsn);
$form_title = str_replace("[", "", $form_main['title']);
$form_title = str_replace("]", "", $form_title);
$form_title = str_replace(" ", "_", $form_title);
$ff         = sprintf(_MA_TADFORM_EXCEL_TITLE, $form_title) . ".xls";
$dl_name    = (_CHARSET == 'UTF-8') ? iconv("UTF-8", "Big5", $ff) : $ff;
$dl_name    = (strpos("MSIE", $_SERVER["HTTP_USER_AGENT"]) !== false) ? urlencode($dl_name) : $dl_name;

$objPHPExcel->setActiveSheetIndex(0); //設定預設顯示的工作表
$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
$objActSheet->setTitle($form_title); //設定標題
$objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改

$objActSheet->setCellValueByColumnAndRow(0, 1, _MA_TADFORM_COL_WHO);
$objActSheet->setCellValueByColumnAndRow(1, 1, _MA_TADFORM_SIGN_DATE);

$sql    = "select csn,title,kind,func from " . $xoopsDB->prefix("tad_form_col") . " where ofsn='{$ofsn}' order by sort";
$result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
$col    = 2;
while (list($csn, $title, $kind, $func) = $xoopsDB->fetchRow($result)) {
    if ($kind == 'show') {
        continue;
    }

    $objActSheet->setCellValueByColumnAndRow($col, 1, $title);
    $col++;
    $kk[$csn] = $kind;
}

$n      = 2;
$sql    = "select ssn,uid,man_name,email,fill_time from " . $xoopsDB->prefix("tad_form_fill") . " where ofsn='{$ofsn}' order by fill_time desc";
$result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
while (list($ssn, $uid, $man_name, $email, $fill_time) = $xoopsDB->fetchRow($result)) {

    $fill_time = date("Y-m-d H:i:s", xoops_getUserTimestamp(strtotime($fill_time)));
    $objActSheet->setCellValueByColumnAndRow(0, $n, $man_name);
    $objActSheet->setCellValueByColumnAndRow(1, $n, $fill_time);

    $sql2    = "select a.csn,a.val from " . $xoopsDB->prefix("tad_form_value") . " as a," . $xoopsDB->prefix("tad_form_col") . " as b where a.csn=b.csn and a.ssn='{$ssn}'  order by b.sort";
    $result2 = $xoopsDB->query($sql2) or web_error($sql2);

    $m = 2;
    while (list($csn, $val) = $xoopsDB->fetchRow($result2)) {

        if ($kk[$csn] == 'fck') {
            $val = strip_tags($val);
        }
        $objActSheet->setCellValueByColumnAndRow($m, $n, $val);
        $m++;
    }

    $n++;
}

//----------內容-----------//
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $dl_name . '"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
