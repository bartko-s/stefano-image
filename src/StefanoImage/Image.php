<?php
namespace StefanoImage;

use StefanoImage\ImageInterface;
use StefanoImage\Exception\InvalidArgumentException;
use StefanoImage\Exception\LogicException;
use StefanoImage\Adapter\AdapterInterface as ImageAdapterInterface;
use StefanoImage\Adapter\Gd as GdAdapter;
use StefanoImage\Calculator\Canvas as CanvasSizeCalculator;
use StefanoImage\Calculator\ImagePosition as ImagePositionCalculator;
use StefanoImage\Calculator\WatermarkPosition as WatermarkPositionCalculator;

class Image 
    implements ImageInterface
{
    private $adapter;
    
    private $sourceImagePath;
    private $keepSourceImageAspectRatio = true;
    private $sourceImageWidth = 0;
    private $sourceImageHeight = 0;
    private $outputFormat = self::OUTPUT_FORMAT_JPEG;
    private $quality = 75;
    private $outputMaxWidth;
    private $outputMaxHeight;
    private $adaptOutputResolution = true;
    private $bacgroundColor = array(
        'red' => 200,
        'green' => 200,
        'blue' => 200,
    );
    private $watermarks = array();
    
    /**
     * @param ImageAdapterInterface $adapter
     */
    public function __construct(ImageAdapterInterface $adapter = null) {
        if(null != $adapter) {
            $this->adapter = $adapter;
        }
    }
    
    public function addWatermark($imagePath, $maxWidth, $maxHeight, 
            $opacity = 30, $position = ImageInterface::WATERMARK_POSITION_CENTER) {
        $this->watermarks[] = array(
            'imagePath' => $imagePath,
            'maxWidth' => $maxWidth,
            'maxHeight' => $maxHeight,
            'opacity' => $opacity,
            'position' => $position,
        );
        
        return $this;
    }

    public function clearWatermarks() {
        $this->watermarks = array();
        
        return $this;
    }
    
    public function backgroundColor($red, $green, $blue) {
        $this->bacgroundColor = array(
            'red' => (int) $red,
            'green' => (int) $green,
            'blue' => (int) $blue,
        );
        
        return $this;
    }

    public function outputFormat($outputFormat) {
        $outputFormat = strtolower(trim($outputFormat));
        
        if(self::OUTPUT_FORMAT_GIF != $outputFormat
                && self::OUTPUT_FORMAT_JPEG != $outputFormat
                && self::OUTPUT_FORMAT_PNG != $outputFormat) {
            throw new InvalidArgumentException('Required "' . $outputFormat 
                    . '" output format is not supported');
        }
        
        $this->outputFormat = $outputFormat;
        return $this;
    }

    public function quality($quality) {
        $quality = ceil($quality);
        
        if(1 > $quality) {
            $this->quality = 1;
        } elseif (100 < $quality) {
            $this->quality = 100;
        } else {
            $this->quality = $quality;
        }
        
        return $this;
    }

    public function resize($width = null, $height = null, $adapt = true) {
        $this->outputMaxWidth = (int) $width;
        $this->outputMaxHeight = (int) $height;
        $this->adaptOutputResolution = (bool) $adapt;
        
        return $this;
    }
    
    public function save($destination, $name) {
        $sourceImagePath = $this->getSourceImagePath();
        
        if(null == $sourceImagePath) {
            throw new LogicException('First you must set source image file');
        }
        
        $adapter = $this->getAdapter();
        
        $canvasSizeCalculator = new CanvasSizeCalculator(
                $this->getSourceImageWidth(), 
                $this->getSourceImageHeight(), 
                $this->getOutputMaxWidth(), 
                $this->getOutputMaxHeight());
        $canvasSizeCalculator->keepAspectRatio($this->getAdaptOutputResolution());
        $adapter->createCanvas(
                $canvasSizeCalculator->getCalculatedCanvasWidth(), 
                $canvasSizeCalculator->getCalculatedCanvasHeight());
        
        $bgColor = $this->getBackgroudnColor();
        $adapter->backgroundColor($bgColor['red'], $bgColor['green'], $bgColor['blue']);
        
        $imagePositionCalculator = new ImagePositionCalculator(
                $canvasSizeCalculator->getCalculatedCanvasWidth(),
                $canvasSizeCalculator->getCalculatedCanvasHeight(), 
                $this->getSourceImageWidth(), 
                $this->getSourceImageHeight());
        $imagePositionCalculator->keepAspectRatio($this->getKeepSourceImageAspectRatio());
        $adapter->drawImage(
                $sourceImagePath, 
                $imagePositionCalculator->getCalculatedXPosition(), 
                $imagePositionCalculator->getCalculatedYPosition(), 
                $imagePositionCalculator->getCalculatedWidth(), 
                $imagePositionCalculator->getCalculatedHeight());
        
        $watermarks = $this->getWatermarks();
        foreach($watermarks as $watermark) {
            $inputWatermarkResolution = $this->getImageInfo($watermark['imagePath']);
                        
            $watermarkPositionCalculator = new WatermarkPositionCalculator(
                    $canvasSizeCalculator->getCalculatedCanvasWidth(),
                    $canvasSizeCalculator->getCalculatedCanvasHeight(), 
                    $watermark['maxWidth'], $watermark['maxHeight'], 
                    $inputWatermarkResolution['width'],
                    $inputWatermarkResolution['height'], 
                    $watermark['position']);
            $adapter->drawWatermark($watermark['imagePath'], 
                    $watermarkPositionCalculator->getCalculatedXPosition(),
                    $watermarkPositionCalculator->getCalculatedYPosition(),
                    $watermarkPositionCalculator->getCalculatedWidth(),
                    $watermarkPositionCalculator->getCalculatedHeight(),
                    $watermark['opacity']);
        }
        
        $outputFormat = $this->getOutputFormat();
        if(self::OUTPUT_FORMAT_GIF == $outputFormat) {
            $adapter->saveAsGif($destination, $name);
        } elseif (self::OUTPUT_FORMAT_JPEG == $outputFormat) {
            $adapter->saveAsJpeg($destination, $name, $this->getQuality());
        } elseif (self::OUTPUT_FORMAT_PNG == $outputFormat) {
            $adapter->saveAsPng($destination, $name, $this->getQuality());
        } else {
            throw new LogicException('Invalid output format "' . $outputFormat . '"');
        }        
        
        return $this;
    }

    public function sourceImage($sourceImagePath, $keepAspectRatio = true) {
        if(!file_exists($sourceImagePath)) {
            throw new InvalidArgumentException('File "' . $sourceImagePath . '" does not exist');
        }
        
        $fileInfo = $this->getImageInfo($sourceImagePath);
        if(null == $fileInfo) {
            throw new InvalidArgumentException('File "' . $sourceImagePath . '" is not image file');
        }
        
        $this->sourceImagePath = $sourceImagePath;
        $this->sourceImageWidth = $fileInfo['width'];
        $this->sourceImageHeight = $fileInfo['height'];
        $this->keepSourceImageAspectRatio = (bool) $keepAspectRatio;
        
        return $this;
    }
    
    /**
     * return null if file is not image otherwise array [width, height]
     * 
     * @param string $imagePath
     * @return null|array
     */
    private function getImageInfo($imagePath) {
        $fileInfo = getimagesize($imagePath);
        
        if(false == $fileInfo) {
            return null;
        } else {
            return array(
                'width' => $fileInfo[0],
                'height' => $fileInfo[1],
            );
        }
    }
    
    /**
     * return null if image path has not been set
     * 
     * @return null|string
     */
    private function getSourceImagePath() {
        return $this->sourceImagePath;
    }
    
    /**
     * @return int
     */
    private function getSourceImageWidth(){
        return $this->sourceImageWidth;
    }
    
    /**
     * @return int
     */
    private function getSourceImageHeight(){
        return $this->sourceImageHeight;
    }
    
    /**
     * @return boolean
     */
    private function getKeepSourceImageAspectRatio(){
        return $this->keepSourceImageAspectRatio;
    }
    
    /**
     * @return string
     */
    private function getOutputFormat() {
        return $this->outputFormat;
    }
    
    /**
     * @return \StefanoImage\Adapter\AdapterInterface
     */
    private function getAdapter() {
        if(null == $this->adapter) {
            $this->adapter = new GdAdapter();
        }
        
        return $this->adapter;
    }
    
    /**
     * @return int
     */
    private function getQuality() {
        return $this->quality;
    }
    
    /**
     * @return int|null
     */
    private function getOutputMaxWidth() {
        return $this->outputMaxWidth;
    }
    
    /**
     * @return int|null
     */
    private function getOutputMaxHeight() {
        return $this->outputMaxHeight;
    }
    
    /**
     * @return boolean
     */
    private function getAdaptOutputResolution() {
        return $this->adaptOutputResolution;
    }
    
    /**
     * @return array keys [red, green, blue]
     */
    private function getBackgroudnColor() {
        return $this->bacgroundColor;
    }
    
    /**
     * @return array
     */
    private function getWatermarks() {
        return $this->watermarks;
    }
}