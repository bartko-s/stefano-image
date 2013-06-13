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

    public function drawImage($imagePath, $x, $y, $width, $height, $opacity = 100) {
        $sourceImage = $this->createImageResourceFromGivenFile($imagePath);
        
        $opacity = (int) $opacity;
        if(1 > $opacity) {
            $opacity = 1;
        } elseif(100 < $opacity) {
            $opacity = 100;
        }
        
        if(100 == $opacity) {
            imagecopyresampled($this->getCanvas(), $sourceImage, (int) $x, (int) $y, 0, 0,
                (int) $width, (int) $height, imagesx($sourceImage), imagesy($sourceImage));
        } else {
            $sourceImageCanvas = imagecreatetruecolor((int) $width, (int) $height);

            //keep transparency
            $color = imagecolorallocatealpha($sourceImageCanvas, 0, 0, 0, 127);
            imagecolortransparent($sourceImageCanvas, $color);
            imagealphablending($sourceImageCanvas, false);
            imagesavealpha($sourceImageCanvas, true);

            imagecopyresampled($sourceImageCanvas, $sourceImage, 0, 0, 0, 0,
                    imagesx($sourceImageCanvas), imagesy($sourceImageCanvas),
                    imagesx($sourceImage), imagesy($sourceImage));

            imagecopymerge($this->getCanvas(), $sourceImageCanvas, (int) $x, (int) $y, 0, 0,
                    imagesx($sourceImageCanvas), imagesy($sourceImageCanvas), $opacity);
            
            imagedestroy($sourceImageCanvas);
        }
        
        imagedestroy($sourceImage);
        return $this;
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
    
    /**
     * @param string $imageFilePath
     * @return resource
     * @throws InvalidArgumentException
     */
    private function createImageResourceFromGivenFile($imageFilePath) {
        if(!file_exists($imageFilePath)) {
            throw new InvalidArgumentException('File "' . $imageFilePath . '" does not exist');
        }
        
        $imageinfo = getimagesize($imageFilePath);        
        if(false == $imageinfo) {
            throw new InvalidArgumentException('Given file "' . $imageFilePath 
                    . '" is not image file');
        }
        
        $mimeType = $imageinfo['mime'];
        
        if('image/jpeg' == $mimeType || 'image/pjpeg' == $mimeType) {
            $imageResource = imagecreatefromjpeg($imageFilePath);
        } elseif('image/png' == $mimeType) {
            $imageResource = imagecreatefrompng($imageFilePath);
        } elseif('image/gif' == $mimeType) {
            $imageResource = imagecreatefromgif($imageFilePath);
        } else {
            throw new InvalidArgumentException('Given file "' . $imageFilePath 
                    . '" has unsupported mime type');
        }
        
        return $imageResource;
    }
}