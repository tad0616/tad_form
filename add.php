<?php
use Xmf\Request;
/*-----------執行動作判斷區----------*/
require __DIR__ . '/header.php';
$xoopsLogger->activated = false;

$ofsn = Request::getInt('ofsn');
$csn = Request::getInt('csn');
$mode = Request::getString('mode');
$op = Request::getString('op', 'tad_form_main_create');
header("location: manager.php?ofsn=$ofsn&op=$op&csn=$csn&mode=$mode");
exit;
