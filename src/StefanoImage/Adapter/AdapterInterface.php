<?php
namespace StefanoImage\Adapter;

interface AdapterInterface
{
    /**
     * @param int $width
     * @param int $height
     * @return self
     */
    public function createCanvas($width, $height);
    
    /**
     * Call this method after createCanvas
     * 
     * @param int $red from 0 to 255
     * @param int $green from 0 to 255
     * @param int $blue from 0 to 255
     * @return self
     */
    public function backgroundColor($red, $green, $blue);
            
    /**
     * 
     * @param string $imagePath
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @param int $opacity from 1 to 100
     * @return self
     */
    public function drawImage($imagePath, $x, $y, $width, $height, $opacity = 100);
    
    /**
     * @param string $path
     * @param string $name
     * @param int $quality form 0 to 100(best quality)
     * @return self
     */
    public function saveAsPng($path, $name, $quality = 75);
    
    /**
     * @param string $path
     * @param string $name
     * @param int $quality form 0 to 100(best quality)
     * @return self
     */
    public function saveAsJpeg($path, $name, $quality = 75);
    
    /**
     * @param string $path
     * @param string $name
     * @return self
     */
    public function saveAsGif($path, $name);
}