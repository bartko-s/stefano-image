<?php
namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\Canvas as CanvasCalculator;

class CanvasTest
    extends \PHPUnit_Framework_TestCase
{
    public function testWithoutChangesResolutionKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(123, 456);
        
        $this->assertEquals(123, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(456, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testWithoutChangesResolutionDontKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(159, 753)
                   ->keepAspectRatio(false);
        
        $this->assertEquals(159, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(753, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputWidthKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(251, 501)
                   ->maxOutputResolution(50, 0);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(100, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputHeightKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(251, 501)
                   ->maxOutputResolution(0, 200);
                           
        $this->assertEquals(100, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(200, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputResolutionKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(51, 501)
                   ->maxOutputResolution(150, 30);
                           
        $this->assertEquals(3, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(30, $calculator->getCalculatedCanvasHeight());        
        
        $calculator->maxOutputResolution(30, 350);
        $this->assertEquals(30, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(295, $calculator->getCalculatedCanvasHeight());        
    }
    
    public function testChangeMaxOutputWidthDontKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(126, 523)
                   ->maxOutputResolution(50, 0)
                   ->keepAspectRatio(false);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(208, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputHeightDontKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(127, 145)
                   ->maxOutputResolution(0, 45)
                   ->keepAspectRatio(false);
                           
        $this->assertEquals(39, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(45, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeOutputResolutionDontKeepAspectRatio() {
        $calculator = new CanvasCalculator();
        $calculator->inputResolution(748, 259)
                   ->maxOutputResolution(25, 45)
                   ->keepAspectRatio(false);
                           
        $this->assertEquals(25, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(45, $calculator->getCalculatedCanvasHeight());
    }
}