<?php
namespace StefanoImage\Adapter;

interface AdapterInterface
{
    public function createCanvas($width, $height);
    public function drawImage($imagePath, $x, $y, $width, $height);
    public function drawWatermark($watermarkPath, $x, $y, $width, $height);
    public function saveAsPng($path, $name, $quality = 75);
    public function saveAsJpeg($path, $name, $quality = 75);
    public function saveAsGif($path, $name);
}