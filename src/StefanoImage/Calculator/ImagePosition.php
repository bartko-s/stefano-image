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
     */
    public function __construct($canvasWidth, $canvasHeight, $imageWidth, $imageHeight) {
        $this->canvasWidth = abs(round($canvasWidth));
        $this->canvasHeight = abs(round($canvasHeight));
        $this->imageWidth = abs(round($imageWidth));
        $this->imageHeight = abs(round($imageHeight));
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
     * @param boolean $keepAspectRatio
     * @return \StefanoImage\Calculator\ImagePosition
     */
    public function keepAspectRatio($keepAspectRatio) {
        $this->keepAspectRatio = (bool) $keepAspectRatio;
        return $this;
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
                return round(($this->getCanvasWidth() + $this->getCalculatedWidth()) / 2);
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
                return round(($this->getCanvasHeight() + $this->getCalculatedHeight()) / 2);
            }
        }
    }
    
    /**
     * @return type
     */
    public function getCalculatedWidth() {
        if(false == $this->getKeepAspectRatio()) {
            return $this->getCanvasWidth();
        } else {
            $r1 = $this->getCanvasWidth() / $this->getImageWidth();
            $r2 = $this->getCanvasHeight() / $this->getImageHeight();
            
            if($r1 > $r2) {
                $ratio = $this->getImageHeight() / $this->getCanvasHeight();
                return round($this->getImageWidth() / $ratio);
            } else {
                return $this->getCanvasWidth();
            }
        }
    }
    
    /**
     * @return type
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
                return round($this->getImageHeight() / $ratio);
            }
        }
    }
}