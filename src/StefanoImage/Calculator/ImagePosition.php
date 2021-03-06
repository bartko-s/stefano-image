<?php
namespace StefanoImage\Calculator;

/**
 * Image Position Calculator
 * 
 * Na zaklade rozmerov platna a dalsich vstupnych podmienok 
 * vypocita presne umiestnenie a velkost obrazka
 */
class ImagePosition
{
    private $canvasWidth = 0;
    private $canvasHeight = 0;
    private $imageWidth = 0;
    private $imageHeight = 0;
    private $keepAspectRatio = true;
    
    /**
     * @param int $canvasWidth
     * @param int $canvasHeight
     * @param int $imageWidth
     * @param int $imageHeight
     * @param bool $keepAspectRatio
     */
    public function __construct($canvasWidth, $canvasHeight, $imageWidth,
            $imageHeight, $keepAspectRatio) {
        $this->canvasWidth = abs(round($canvasWidth));
        $this->canvasHeight = abs(round($canvasHeight));
        $this->imageWidth = abs(round($imageWidth));
        $this->imageHeight = abs(round($imageHeight));
        $this->keepAspectRatio = (bool) $keepAspectRatio;
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
     * @return int
     */
    private function getImageWidth() {
        return $this->imageWidth;
    }
    
    /**
     * @return int
     */
    private function getImageHeight() {
        return $this->imageHeight;
    }
    
    /**
     * @return boolean
     */
    private function getKeepAspectRatio() {
        return $this->keepAspectRatio;
    }
       
    /**
     * @return int
     */
    public function getCalculatedXPosition() {
        if(false == $this->getKeepAspectRatio()) {
            return 0;
        } else {
            $r1 = $this->getCanvasWidth() / $this->getImageWidth();
            $r2 = $this->getCanvasHeight() / $this->getImageHeight();
            
            if($r1 > $r2) {
                return ceil(($this->getCanvasWidth() / 2) - ($this->getCalculatedWidth() / 2));
            } else {
                return 0;
            }
        }
    }
    
    /**
     * @return int
     */
    public function getCalculatedYPosition() {
        if(false == $this->getKeepAspectRatio()) {
            return 0;
        } else {
            $r1 = $this->getCanvasWidth() / $this->getImageWidth();
            $r2 = $this->getCanvasHeight() / $this->getImageHeight();
            
            if($r1 > $r2) {
                return 0;
            } else {
                return ceil(($this->getCanvasHeight() / 2) - ($this->getCalculatedHeight() / 2));
            }
        }
    }
    
    /**
     * @return int
     */
    public function getCalculatedWidth() {
        if(false == $this->getKeepAspectRatio()) {
            return $this->getCanvasWidth();
        } else {
            $r1 = $this->getCanvasWidth() / $this->getImageWidth();
            $r2 = $this->getCanvasHeight() / $this->getImageHeight();
            
            if($r1 > $r2) {
                $ratio = $this->getImageHeight() / $this->getCanvasHeight();
                return ceil($this->getImageWidth() / $ratio);
            } else {
                return $this->getCanvasWidth();
            }
        }
    }
    
    /**
     * @return int
     */
    public function getCalculatedHeight() {
        if(false == $this->getKeepAspectRatio()) {
            return $this->getCanvasHeight();
        } else {
            $r1 = $this->getCanvasWidth() / $this->getImageWidth();
            $r2 = $this->getCanvasHeight() / $this->getImageHeight();
            
            if($r1 > $r2) {
                return $this->getCanvasHeight();
            } else {
                $ratio = $this->getImageWidth() / $this->getCanvasWidth();
                return ceil($this->getImageHeight() / $ratio);
            }
        }
    }
}