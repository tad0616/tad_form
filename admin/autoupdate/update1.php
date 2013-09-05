<?php
include_once "../../../../mainfile.php";

if($_POST['op']=="GO"){
  start_update1();
}

$ver="1.1 -> 1.2";
$title=_MA_TADFORM_AUTOUPDATE1;
$ok=update_chk1();


function update_chk1(){
	global $xoopsDB;
	$sql="select count(`kind`) from ".$xoopsDB->prefix("tad_form_main");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}


function start_update1(){
	global $xoopsDB;
	$sql="ALTER TABLE ".$xoopsDB->prefix("tad_form_main")." ADD `kind` varchar(255) NOT NULL";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());

	header("location:{$_SERVER["HTTP_REFERER"]}");
	exit;
}
?>
