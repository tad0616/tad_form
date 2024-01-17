<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$op = Request::getString('op');
$ofsn = Request::getInt('ofsn');

header("location: manager.php?ofsn=$ofsn&op=tad_form_fill_mail");
exit;
