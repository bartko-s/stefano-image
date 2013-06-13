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
        $this->drawImageOnCanvas($imagePath, $x, $y, $width, $height, 100);
        return $this;
    }
    
    public function drawWatermark($imagePath, $x, $y, $width, $height, $opacity = 30) {
        $this->drawImageOnCanvas($imagePath, $x, $y, $width, $height, $opacity);
        return $this;
    }
    
    /**
     * @param string $imagePath
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @param int $opacity from 1 to 100
     * @return \StefanoImage\Adapter\Gd
     */
    private function drawImageOnCanvas($imagePath, $x, $y, $width, $height, $opacity) {
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
        $this->save($path, $name, 'jpeg', $quality);
        return $this;
    }

    public function saveAsPng($path, $name, $quality = 75) {
        $this->save($path, $name, 'png', $quality);                
        return $this;
    }

    public function saveAsGif($path, $name) {
        $this->save($path, $name, 'gif');                
        return $this;
    }  
    
    /**
     * 
     * @param string $path
     * @param string $name
     * @param string $outputType
     * @param int $quality from 1 to 100
     */
    private function save($path, $name, $outputType, $quality = 75) {
        $quality = (int) $quality;
        if(1 > $quality) {
            $quality = 0;
        } elseif(100 < $quality) {
            $quality = 100;
        }
        
        $basePath = (string) $path . '/' . $name;
        
        if('jpeg' == $outputType) {
            imagejpeg($this->getCanvas(), $basePath . '.jpeg', $quality);
        } elseif('png' == $outputType) {
            $compression = ceil(abs(($quality / 11.12) - 9));            
            imagepng($this->getCanvas(), $basePath . '.png', $compression);
        } elseif('gif' == $outputType) {
            imagegif($this->getCanvas(), $basePath . '.gif');
        } else {
            throw new InvalidArgumentException('Unsupported "' . $outputType 
                    . '" output type');
        }
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