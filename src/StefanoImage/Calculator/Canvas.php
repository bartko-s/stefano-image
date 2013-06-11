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
    private $keepAspectRatio = true;
    
    /**
     * @param int $inputWidth
     * @param int $inputHeight
     * @return \StefanoImage\Calculator\Canvas
     */
    public function inputResolution($inputWidth, $inputHeight) {
        $this->inputWidth = abs(round($inputWidth));
        $this->inputHeight = abs(round($inputHeight));
        
        return $this;
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
     * @param int $maxOutputWidth
     * @param int $maxOutputHeight
     * @return \StefanoImage\Calculator\Canvas
     */
    public function maxOutputResolution($maxOutputWidth, $maxOutputHeight) {
        $this->maxOutputWidth = abs(round($maxOutputWidth));
        $this->maxOutputHeight = abs(round($maxOutputHeight));
        
        return $this;
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
     * @param int $keepAspectRatio
     * @return \StefanoImage\Calculator\Canvas
     */
    public function keepAspectRatio($keepAspectRatio) {
        $this->keepAspectRatio = (bool) $keepAspectRatio;
        return $this;
    }
    
    /**
     * @return int
     */
    private function getKeepAspectRatio() {
        return $this->keepAspectRatio;
    }
    
    /**
     * @return int
     */
    public function getCalculatedCanvasWidth() {
        $inputWidth = $this->getInputWidth();
        $inputHeight = $this->getInputHeight();
        $maxOutWidth = $this->getMaxOutputWidth();
        $maxOutHeigth = $this->getMaxOutputHeight();
        $keepAspectRatio = $this->getKeepAspectRatio();
        
        if(0 == $maxOutWidth && 0 == $maxOutHeigth && true == $keepAspectRatio) {
            return $inputWidth;
        } elseif(0 == $maxOutWidth && 0 == $maxOutHeigth && false == $keepAspectRatio) {
            return $inputWidth;
        } elseif(0 == $maxOutWidth && 0 != $maxOutHeigth && true == $keepAspectRatio) {
            $ratio = $inputHeight / $maxOutHeigth;
            
            return round($inputWidth / $ratio);
        } elseif(0 == $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            $ratio = $inputHeight / $maxOutHeigth;
            
            return round($inputWidth / $ratio);
        } elseif(0 != $maxOutWidth && 0 == $maxOutHeigth && true == $keepAspectRatio) {
            return $maxOutWidth;
        } elseif(0 != $maxOutWidth && 0 == $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutWidth;
        } elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && true == $keepAspectRatio) {
            $r1 = $inputWidth / $maxOutWidth;
            $r2 = $inputHeight / $maxOutHeigth;
            
            if($r1 < $r2) {
                return round($inputWidth / $r2);
            } else {
                return $maxOutWidth;
            }
        } else { //elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutWidth;
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
        $keepAspectRatio = $this->getKeepAspectRatio();
        
        if(0 == $maxOutWidth && 0 == $maxOutHeigth && true == $keepAspectRatio) {
            return $inputHeight;
        } elseif(0 == $maxOutWidth && 0 == $maxOutHeigth && false == $keepAspectRatio) {
            return $inputHeight;
        } elseif(0 == $maxOutWidth && 0 != $maxOutHeigth && true == $keepAspectRatio) {
            return $maxOutHeigth;
        } elseif(0 == $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutHeigth;
        } elseif(0 != $maxOutWidth && 0 == $maxOutHeigth && true == $keepAspectRatio) {
            $ratio = $inputWidth / $maxOutWidth;
            
            return round($inputHeight / $ratio);
        } elseif(0 != $maxOutWidth && 0 == $maxOutHeigth && false == $keepAspectRatio) {
            $ratio = $inputWidth / $maxOutWidth;
            
            return round($inputHeight / $ratio);
        } elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && true == $keepAspectRatio) {
            $r1 = $inputWidth / $maxOutWidth;
            $r2 = $inputHeight / $maxOutHeigth;
            
            if($r1 > $r2) {
                return round($inputHeight / $r1);
            } else {
                return $maxOutHeigth;
            }
        } else { //elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutHeigth;
        }
    }
}