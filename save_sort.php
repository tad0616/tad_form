<?php
use XoopsModules\Tad_form\Tad_form_col;

require __DIR__ . '/header.php';
error_reporting(0);
$xoopsLogger->activated = false;

$updateRecordsArray = $_POST['tr'];
$ofsn = (int) $_GET['ofsn'];
$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    Tad_form_col::update($ofsn, ['csn' => $recordIDValue], ['sort' => $sort]);
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
