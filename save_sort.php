<?php
use Xmf\Request;
use XoopsModules\Tad_form\Tad_form_col;

require __DIR__ . '/header.php';
// 關閉除錯訊息
$xoopsLogger->activated = false;

$updateRecordsArray = Request::getVar('tr', [], null, 'array', 4);

$ofsn = (int) $_GET['ofsn'];
$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    Tad_form_col::update($ofsn, ['csn' => $recordIDValue], ['sort' => $sort]);
    $sort++;
}

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
