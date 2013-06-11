<?php
namespace StefanoImage\Calculator;

class Canvas
{
    private $inputWidth = 0;
    private $inputHeight = 0;
    private $maxOutputWidth = 0;
    private $maxOutputHeight = 0;
    private $keepAspectRatio = true;
    
    public function inputResolution($inputWidth, $inputHeight) {
        $this->inputWidth = abs(round($inputWidth));
        $this->inputHeight = abs(round($inputHeight));
        
        return $this;
    }
    
    private function getInputWidth() {
        return $this->inputWidth;
    }
    
    private function getInputHeight() {
        return $this->inputHeight;
    }
    
    public function maxOutputResolution($maxOutputWidth, $maxOutputHeight) {
        $this->maxOutputWidth = abs(round($maxOutputWidth));
        $this->maxOutputHeight = abs(round($maxOutputHeight));
        
        return $this;
    }
    
    private function getMaxOutputWidth() {
        return $this->maxOutputWidth;
    }
    
    private function getMaxOutputHeight() {
        return $this->maxOutputHeight;
    }
    
    public function keepAspectRatio($keepAspectRatio) {
        $this->keepAspectRatio = (bool) $keepAspectRatio;
        return $this;
    }
    
    private function getKeepAspectRatio() {
        return $this->keepAspectRatio;
    }
    
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
        } elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutWidth;
        }
    }
    
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
        } elseif(0 != $maxOutWidth && 0 != $maxOutHeigth && false == $keepAspectRatio) {
            return $maxOutHeigth;
        }
    }
}