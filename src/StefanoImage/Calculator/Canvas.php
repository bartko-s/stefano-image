<?php
namespace StefanoImage\Calculator;

/**
 * Canvas Calculator
 * 
 * Na zaklade povodneho rozlisenia obrazka a hranicnych vystupnych podmienok
 * vypocita celkove rozmery vystupneho obrazka
 */
class Canvas
{
    private $inputWidth = 0;
    private $inputHeight = 0;
    private $maxOutputWidth = 0;
    private $maxOutputHeight = 0;
    private $adaptOutputResolution = true;
    
    /**
     * 
     * @param int $inputImageWidth
     * @param int $inputImageHeight
     * @param int $maxOutputImageWidth null is unlimited
     * @param int $maxOutputImageHeight null is unlimited
     * @param bool $adaptOutputResolution Adapt output resolution to the source image
     */
    public function __construct($inputImageWidth, $inputImageHeight, 
            $maxOutputImageWidth, $maxOutputImageHeight,
            $adaptOutputResolution) {
        $this->inputWidth = abs(ceil($inputImageWidth));
        $this->inputHeight = abs(ceil($inputImageHeight));
        $this->maxOutputWidth = abs(ceil($maxOutputImageWidth));
        $this->maxOutputHeight = abs(ceil($maxOutputImageHeight));
        $this->adaptOutputResolution = (bool) $adaptOutputResolution;
    }
    
    /**
     * @return int
     */
    private function getInputWidth() {
        return $this->inputWidth;
    }
    
    /**
     * @return int
     */
    private function getInputHeight() {
        return $this->inputHeight;
    }
    
    /**
     * @return int
     */
    private function getMaxOutputWidth() {
        return $this->maxOutputWidth;
    }
    
    /**
     * @return int
     */
    private function getMaxOutputHeight() {
        return $this->maxOutputHeight;
    }
    
    /**
     * @return bool
     */
    private function getAdaptOutputResolution() {
        return $this->adaptOutputResolution;
    }
    
    /**
     * @return int
     */
    public function getCalculatedCanvasWidth() {
        $inputWidth = $this->getInputWidth();
        $inputHeight = $this->getInputHeight();
        $maxOutWidth = $this->getMaxOutputWidth();
        $maxOutHeigth = $this->getMaxOutputHeight();
        $adaptOutputResolution = $this->getAdaptOutputResolution();

        if(null == $maxOutHeigth && null == $maxOutWidth) {
            return $inputWidth;
        } elseif(null == $maxOutHeigth || null == $maxOutWidth) {
            if(null == $maxOutHeigth) {
                return $maxOutWidth;
            } else {
                return ceil(($inputWidth / $inputHeight) * $maxOutHeigth);
            }
        } else {
            if(true == $adaptOutputResolution) {
                $r1 = $inputWidth / $maxOutWidth;
                $r2 = $inputHeight / $maxOutHeigth;

                if($r1 < $r2) {
                    return ceil($inputWidth / $r2);
                } else {
                    return $maxOutWidth;
                }
            } else {
                return $maxOutWidth;
            }
        }
    }
    
    /**
     * @return int
     */
    public function getCalculatedCanvasHeight() {
        $inputWidth = $this->getInputWidth();
        $inputHeight = $this->getInputHeight();
        $maxOutWidth = $this->getMaxOutputWidth();
        $maxOutHeigth = $this->getMaxOutputHeight();
        $adaptOutputResolution = $this->getAdaptOutputResolution();

        if(null == $maxOutHeigth && null == $maxOutWidth) {
            return $inputHeight;
        } elseif(null == $maxOutHeigth || null == $maxOutWidth) {
            if(null == $maxOutWidth) {
                return $maxOutHeigth;
            } else {
                return ceil(($inputHeight / $inputWidth) * $maxOutWidth);
            }
        } else {
            if(true == $adaptOutputResolution) {
                $r1 = $inputWidth / $maxOutWidth;
                $r2 = $inputHeight / $maxOutHeigth;

                if($r1 > $r2) {
                    return ceil($inputHeight / $r1);
                } else {
                    return $maxOutHeigth;
                }
            } else {
                return $maxOutHeigth;
            }
        }
    }
}