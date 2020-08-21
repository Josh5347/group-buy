<?php

header('content-Type: text/html; charset=utf-8');  //強符集utf-8

session_start(); 
$font_size       = 23;
//$font_path       = URL::asset('image/Allstar4.ttf');
$path            = __DIR__;
//$font_path       = $path.'\Allstar4.ttf';
$font_path      = dirname(__FILE__) .'\img\Allstar4.ttf';
$angle           = 0;
$imageSize = [
  'x' => 100,
  'y' => 50
];

//$im = imagecreatetruecolor(50,25); 加大圖片
$im = imagecreatetruecolor($imageSize['x'], $imageSize['y']);

$randcolor = imagecolorallocate($im,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));

$gray = imagecolorallocate($im,220,220,220);

imagefill($im,0,0,$gray);
imagesetthickness($im, 3); // 設定線條寬度

// 設定線條顏色
$linecolor1 = imagecolorallocate($im,mt_rand(150,180),mt_rand(150,180),mt_rand(150,255));
$linecolor2 = imagecolorallocate($im,mt_rand(150,200),mt_rand(150,255),mt_rand(150,255));
$linecolor3 = imagecolorallocate($im,mt_rand(100,200),mt_rand(110,255),mt_rand(150,255));

//imageline($im,0,mt_rand(0,25),50,mt_rand(0,25),$linecolor1);
//imageline($im,0,mt_rand(0,25),50,mt_rand(0,25),$linecolor2);
//imageline($im,0,mt_rand(0,25),50,mt_rand(0,25),$linecolor3);
imageline($im,0,mt_rand(0,25),$imageSize['x'],mt_rand(0,$imageSize['y']),$linecolor1);
imageline($im,0,mt_rand(0,25),$imageSize['x'],mt_rand(0,$imageSize['y']),$linecolor2);
imageline($im,0,mt_rand(0,25),$imageSize['x'],mt_rand(0,$imageSize['y']),$linecolor3);

//$_SESSION['validation_image_number'] = substr(str_shuffle('ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'),0,4); 刪除小寫
$_SESSION['validation_image_number'] = substr(str_shuffle('ABCDEFGHIJKMNPQRSTUVWXYZ23456789'),0,4);

//$validation_image_number = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz23456789'),0,4);

//session(['validation_image_number' => $validation_image_number]);

// 設定驗證文字
//imagestring($im,5,6,3,$_SESSION['validation_image_number'],$randcolor); //此產生的字形太小
imagettftext($im, $font_size, $angle, 10, 35, $randcolor, $font_path, $_SESSION['validation_image_number']);

/* ob_start();
imagepng($im);
$buffer = ob_get_contents();
ob_end_clean();
imagedestroy($im); */
header('content-type:image/png');
imagepng($im);
imagedestroy($im);