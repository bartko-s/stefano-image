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
    private $outputFormat = self::OUTPUT_FORMAT_JPEG;
    private $quality = 75;
    private $bacgroundColor = array(
        'red' => 200,
        'green' => 200,
        'blue' => 200,
    );
    private $watermarks = array();

    private $maxOutputWidth;
    private $maxOutputHeight;
    private $keepSourceImageAspectRatio = true;
    private $adaptOutputResolution = true;
    
    /**
     * @param ImageAdapterInterface $adapter
     */
    public function __construct(ImageAdapterInterface $adapter = null) {
        if(null != $adapter) {
            $this->adapter = $adapter;
        }
    }
    
    public function addWatermark($imagePath, $maxWidthPercent, $maxHeightPercent,
            $opacity = 30, $position = ImageInterface::WATERMARK_POSITION_CENTER) {
        $this->watermarks[] = array(
            'imagePath' => $imagePath,
            'maxWidthPercent' => $maxWidthPercent,
            'maxHeightPercent' => $maxHeightPercent,
            'opacity' => $opacity,
            'position' => $position,
        );
        
        return $this;
    }

    public function clearWatermarks() {
        $this->watermarks = array();
        
        return $this;
    }
    
    private function backgroundColor($red, $green, $blue) {
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

    public function resize($maxWidth = null, $maxHeight = null) {
        $this->maxOutputWidth = (int) $maxWidth;
        $this->maxOutputHeight = (int) $maxHeight;
        $this->adaptOutputResolution = true;

        $this->keepSourceImageAspectRatio = true;

        return $this;
    }

    public function adaptiveResize($width, $height) {
        $this->maxOutputWidth = (int) $width;
        $this->maxOutputHeight = (int) $height;
        $this->adaptOutputResolution = false;

        $this->keepSourceImageAspectRatio = false;

        return $this;
    }

    public function pad($width, $height, $color = array(200, 200, 200)) {
        $this->maxOutputWidth = (int) $width;
        $this->maxOutputHeight = (int) $height;
        $this->adaptOutputResolution = false;

        $this->keepSourceImageAspectRatio = true;

        $this->backgroundColor($color[0], $color[1], $color[2]);

        return $this;
    }
    
    public function save($destination, $name) {
        $sourceImagePath = $this->getSourceImagePath();
        
        if(null == $sourceImagePath) {
            throw new LogicException('First you must set source image file');
        }
        
        $adapter = $this->getAdapter();

        //calculate and create canvas
        $canvasSizeCalculator = new CanvasSizeCalculator(
                $this->getSourceImageWidth(), 
                $this->getSourceImageHeight(), 
                $this->getMaxOutputWidth(),
                $this->getMaxOutputHeight(),
                $this->getAdaptOutputResolution());
        $adapter->createCanvas(
                $canvasSizeCalculator->getCalculatedCanvasWidth(), 
                $canvasSizeCalculator->getCalculatedCanvasHeight());

        //set background color
        $bgColor = $this->getBackgroudnColor();
        $adapter->backgroundColor($bgColor['red'], $bgColor['green'], $bgColor['blue']);
        
        //draw image
        $imagePositionCalculator = new ImagePositionCalculator(
                $adapter->getCanvasWidth(),
                $adapter->getCanvasHeight(),
                $this->getSourceImageWidth(), 
                $this->getSourceImageHeight(),
                $this->getKeepSourceImageAspectRatio());
        $adapter->drawImage(
                $sourceImagePath, 
                $imagePositionCalculator->getCalculatedXPosition(), 
                $imagePositionCalculator->getCalculatedYPosition(), 
                $imagePositionCalculator->getCalculatedWidth(), 
                $imagePositionCalculator->getCalculatedHeight(),
                100);

        //draw watermarks
        $watermarks = $this->getWatermarks();
        foreach($watermarks as $watermark) {
            $inputWatermarkResolution = $this->getImageInfo($watermark['imagePath']);
                        
            $watermarkPositionCalculator = new WatermarkPositionCalculator(
                    $canvasSizeCalculator->getCalculatedCanvasWidth(),
                    $canvasSizeCalculator->getCalculatedCanvasHeight(), 
                    $watermark['maxWidthPercent'], $watermark['maxHeightPercent'],
                    $inputWatermarkResolution['width'],
                    $inputWatermarkResolution['height'], 
                    $watermark['position']);
            $adapter->drawImage($watermark['imagePath'],
                    $watermarkPositionCalculator->getCalculatedXPosition(),
                    $watermarkPositionCalculator->getCalculatedYPosition(),
                    $watermarkPositionCalculator->getCalculatedWidth(),
                    $watermarkPositionCalculator->getCalculatedHeight(),
                    $watermark['opacity']);
        }

        //save
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

    public function sourceImage($sourceImagePath) {
        if(!file_exists($sourceImagePath)) {
            throw new InvalidArgumentException('File "' . $sourceImagePath . '" does not exist');
        }
        
        $fileInfo = $this->getImageInfo($sourceImagePath);
        if(null == $fileInfo) {
            throw new InvalidArgumentException('File "' . $sourceImagePath . '" is not image file');
        }
        
        $this->sourceImagePath = $sourceImagePath;
        
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
        $fileInfo = getimagesize($this->getSourceImagePath());
        return $fileInfo[0];
    }
    
    /**
     * @return int
     */
    private function getSourceImageHeight(){
        $fileInfo = getimagesize($this->getSourceImagePath());
        return $fileInfo[1];
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
    private function getMaxOutputWidth() {
        return $this->maxOutputWidth;
    }
    
    /**
     * @return int|null
     */
    private function getMaxOutputHeight() {
        return $this->maxOutputHeight;
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