<?php

class MyImage {
    private $height;
    private $width;
    // private $textNum = [];
    // private $text;

    public function __construct() {
        $img = imagecreatetruecolor(600, 600);
        $bg = imagecolorallocatealpha($img, 0, 0, 0, 127);
        // imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $bg);
        imagesavealpha($img, true);
        imagepng($img, FCPATH . 'public/' . md5(time()) . '.png');
        imagedestroy($img);
    }
}