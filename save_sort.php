<?php
use XoopsModules\Tad_form\Tad_form_col;
require_once "../../mainfile.php";
$updateRecordsArray = $_POST['tr'];

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    Tad_form_col::update($ofsn, ['csn' => $recordIDValue], ['sort' => $sort]);
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
