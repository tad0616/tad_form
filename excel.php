<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
xoops_loadLanguage('admin', 'tad_form');

if (!Utility::power_chk('tad_form_post', 1) and !$_SESSION['tad_form_adm']) {
    redirect_header('index.php', 3, _TAD_PERMISSION_DENIED);
}

require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //引入 PHPExcel 物件庫
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //引入 PHPExcel_IOFactory 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel

$ofsn = isset($_REQUEST['ofsn']) ? (int) $_REQUEST['ofsn'] : 0;
$sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_form_main') . '` WHERE `ofsn`=?';
$result = Utility::query($sql, 'i', [$ofsn]) or Utility::web_error($sql, __FILE__, __LINE__, true);

$form_main = $xoopsDB->fetchArray($result);
$form_title = str_replace(['[', ']', ' '], '', $form_main['title']);
$ff = sprintf(_MD_TAD_FORM_EXCEL_TITLE, $form_title) . '.xlsx';
// $dl_name = (_CHARSET === 'UTF-8') ? iconv('UTF-8', 'Big5', $ff) : $ff;
// $dl_name = (false !== mb_strpos('MSIE', $_SERVER['HTTP_USER_AGENT'])) ? urlencode($dl_name) : $dl_name;

$objPHPExcel->setActiveSheetIndex(0); //設定預設顯示的工作表
$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
$objActSheet->setTitle('data'); //設定標題
$objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改

$objActSheet->setCellValue("A1", _MD_TAD_FORM_COL_WHO);
$objActSheet->setCellValue("B1", strip_tags(sprintf(_MD_TAD_FORM_FORMAT_SIGN_DATE, $form_main['start_date'], $form_main['end_date'])));

$sql = 'SELECT `csn`, `title`, `kind`, `func` FROM `' . $xoopsDB->prefix('tad_form_col') . '` WHERE `ofsn`=? ORDER BY `sort`';
$result = Utility::query($sql, 'i', [$ofsn]) or Utility::web_error($sql, __FILE__, __LINE__, true);

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
$sql = 'SELECT `ssn`, `uid`, `man_name`, `email`, `fill_time` FROM `' . $xoopsDB->prefix('tad_form_fill') . '` WHERE `ofsn` =? ORDER BY `fill_time` DESC';
$result = Utility::query($sql, 'i', [$ofsn]) or Utility::web_error($sql, __FILE__, __LINE__, true);

while (list($ssn, $uid, $man_name, $email, $fill_time) = $xoopsDB->fetchRow($result)) {
    $fill_time = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($fill_time)));
    $objActSheet->setCellValueByColumnAndRow(0, $n, $man_name);
    $objActSheet->setCellValueByColumnAndRow(1, $n, $fill_time);

    $sql2 = 'SELECT a.`csn`, a.`val` FROM `' . $xoopsDB->prefix('tad_form_value') . '` AS a, `' . $xoopsDB->prefix('tad_form_col') . '` AS b WHERE a.`csn` = b.`csn` AND a.`ssn` =? ORDER BY b.`sort`';
    $result2 = Utility::query($sql2, 'i', [$ssn]) or Utility::web_error($sql2);

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
// header("Content-Disposition: attachment;filename={$ff}");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(false);
// $objWriter->save('php://output');

$objWriter->save(XOOPS_ROOT_PATH . "/uploads/tad_form/{$ff}");
header("location:" . XOOPS_URL . "/uploads/tad_form/{$ff}");
exit;

function num2alpha($n)
{
    for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
        $r = chr($n % 26 + 0x41) . $r;
    }

    return $r;
}
