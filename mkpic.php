<?php
require __DIR__ . '/header.php';
session_start();
$sn = (int) $_GET['ofsn'];
$num1 = rand(0, 9);
$num2 = rand(0, 9);
$num3 = rand(0, 9);
$num = $num1 . $num2 . $num3;
// 關閉除錯訊息
$xoopsLogger->activated = false;
$_SESSION['security_code_' . $sn] = $num;

header("Content-type: image/png");
$im = @imagecreatetruecolor(40, 20);
$text_color = imagecolorallocate($im, 255, 255, 255);
imagestring($im, 5, 5, 2, $num, $text_color);
imagepng($im);
imagedestroy($im);
