<?php
namespace StefanoImage\Adapter;

use StefanoImage\Adapter\AdapterInterface;
use StefanoImage\Exception\InvalidArgumentException;
use StefanoImage\Exception\RuntimeException;

class Gd
    implements AdapterInterface
{
    private $canvas;
    
    public function __construct() {
        $this->checkDependencies();
    }
    
    public function __destruct(){
        if (is_resource($this->canvas)) {
            imagedestroy($this->canvas);
        }
    }
    
    public function createCanvas($width, $height) {
        $this->canvas = imagecreatetruecolor((int) $width, (int) $height);
        
        return $this;
    }
    
    public function backgroundColor($red, $green, $blue) {
        $bgColour = imagecolorallocate($this->getCanvas(), 
                (int) $red, (int) $green, (int) $blue);
        imagefill($this->getCanvas(), 0, 0, $bgColour);
        
        return $this;
    }
    
    public function drawImage($imagePath, $x, $y, $width, $height) {
        if(!file_exists($imagePath)) {
            throw new InvalidArgumentException('File "' . $imagePath . '" does not exist');
        }
        
        $imageinfo = getimagesize($imagePath);        
        if(false == $imageinfo) {
            throw new InvalidArgumentException('Given file "' . $imagePath 
                    . '" is not image file');
        }
        
        $mimeType = $imageinfo['mime'];
        
        if('image/jpeg' == $mimeType || 'image/pjpeg' == $mimeType) {
            $sourceImage = imagecreatefromjpeg($imagePath);
        } elseif('image/png' == $mimeType) {
            $sourceImage = imagecreatefrompng($imagePath);
        } elseif('image/gif' == $mimeType) {
            $sourceImage = imagecreatefromgif($imagePath);
        } else {
            throw new InvalidArgumentException('Given file "' . $imagePath 
                    . '" has unsupported mime type');
        }
        
        imagecopyresampled(
                $this->getCanvas(), $sourceImage, (int) $x, (int) $y,
                0, 0, (int) $width, (int) $height, $imageinfo[0], $imageinfo[1]);
        
        imagedestroy($sourceImage);
        return $this;
    }

    public function drawWatermark($watermarkPath, $x, $y, $width, $height) {
        
    }

    public function saveAsJpeg($path, $name, $quality = 75) {
        $quality = (int) $quality;
        if(0 > $quality) {
            $quality = 1;
        } elseif(100 < $quality) {
            $quality = 100;
        }
        
        $path = $path . '/' . $name . '.jpeg';        
        imagejpeg($this->getCanvas(), $path, $quality);
                
        return $this;
    }

    public function saveAsPng($path, $name, $quality = 75) {
        $compression = (($quality / 10) - 10) * -1;
        if(0 > $compression) {
            $compression = 0;
        } elseif(9 < $compression) {
            $compression = 9;
        }
        
        $path = $path . '/' . $name . '.png';        
        imagepng($this->getCanvas(), $path, $compression);
                
        return $this;
    }

    public function saveAsGif($path, $name) {
        $path = $path . '/' . $name . '.gif';        
        imagegif($this->getCanvas(), $path);
                
        return $this;
    }  
            
    private function getCanvas() {
        return $this->canvas;
    }
    
    private function checkDependencies() {
        if(!extension_loaded('gd')) {
            throw new RuntimeException('Required PHP extension "GD" was not loaded');
        }
    }
}