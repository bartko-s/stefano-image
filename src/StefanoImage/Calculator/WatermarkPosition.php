<?php
namespace StefanoImage\Calculator;

use StefanoImage\ImageInterface;
use StefanoImage\Calculator\ImagePosition as ImagePositionCalculator;

/**
 * Watermark position calculator
 * 
 * Vypocita rozmer a poziciu watermarku na zaklade vstupnych parametrov
 */
class WatermarkPosition
{
    private $canvasWidth;
    private $canvasHeight;
    private $imagePositionCalculator;
    private $position;
    
    /**
     * @param int $canvasWidth
     * @param int $canvasHeight
     * @param int $maxWatermarkWidth
     * @param int $maxWatermarkHeight
     * @param int $inputWatermarkWidth
     * @param int $inputWatermarkHeight
     * @param int $position ImageInterface::WATERMARK_POSITION_XXX
     */
    public function __construct($canvasWidth, $canvasHeight,
            $maxWatermarkWidth, $maxWatermarkHeight,
            $inputWatermarkWidth, $inputWatermarkHeight,
            $position) {
        $this->canvasWidth = abs($canvasWidth);
        $this->canvasHeight = abs($canvasHeight);
        
        $this->position = trim(strtolower($position));
        
        $this->imagePositionCalculator = new ImagePositionCalculator($maxWatermarkWidth,
                $maxWatermarkHeight, $inputWatermarkWidth, $inputWatermarkHeight);
    }
    
    /**
     * @return int
     */
    public function getCalculatedXPosition() {
        $currentPosition = $this->getPosition();
        
        if(ImageInterface::WATERMARK_POSITION_TOP_LEFT == $currentPosition || 
                ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT == $currentPosition) {
            return round($this->getCanvasWidth() / 10);
        } elseif(ImageInterface::WATERMARK_POSITION_TOP_RIGHT == $currentPosition ||
                ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT == $currentPosition) {
            return round($this->getCanvasWidth() - $this->getCalculatedWidth()
                    - ($this->getCanvasWidth() / 10));
        } else { //default center
            return round(($this->getCanvasWidth() / 2) - ($this->getCalculatedWidth() / 2));
        }
    }

    /**
     * @return int
     */
    public function getCalculatedYPosition() {
        $currentPosition = $this->getPosition();
        
        if(ImageInterface::WATERMARK_POSITION_TOP_LEFT == $currentPosition ||
                ImageInterface::WATERMARK_POSITION_TOP_RIGHT == $currentPosition) {
            return round($this->getCanvasHeight() / 10);
        } elseif(ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT == $currentPosition ||
                ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT == $currentPosition) {
            return round($this->getCanvasHeight() - $this->getCalculatedHeight()
                    - ($this->getCanvasHeight() / 10));
        } else { //default center
            return round(($this->getCanvasHeight() / 2) - ($this->getCalculatedHeight() / 2));
        }
    }
    
    /**
     * @return int
     */
    public function getCalculatedWidth() {
        return $this->getWatemarkPositionCalculator()
                    ->getCalculatedWidth();
    }
       
    /**
     * @return int
     */
    public function getCalculatedHeight() {
        return $this->getWatemarkPositionCalculator()
                    ->getCalculatedHeight();
    }
    
    /**
     * @return ImagePositionCalculator
     */
    private function getWatemarkPositionCalculator() {
        return $this->imagePositionCalculator;
    }
    
    /**
     * @return int
     */
    private function getCanvasWidth() {
        return $this->canvasWidth;
    }
    
    /**
     * @return int
     */
    private function getCanvasHeight() {
        return $this->canvasHeight;
    }
    
    /**
     * @return string
     */
    private function getPosition() {
        return $this->position;
    }
}