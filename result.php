<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
/*-----------執行動作判斷區----------*/
$op = Request::getString('op', 'tad_form_fill_show');
$ofsn = Request::getInt('ofsn');
$ssn = Request::getInt('ssn');
$files_sn = Request::getInt('files_sn');

header("location: index.php?op=$op&ofsn=$ofsn&ssn=$ssn&files_sn=$files_sn");
exit;
