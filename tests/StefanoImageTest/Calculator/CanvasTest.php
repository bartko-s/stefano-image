<?php
namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\Canvas as CanvasCalculator;

class CanvasTest
    extends \PHPUnit_Framework_TestCase
{
    public function testWithoutChangesResolutionKeepAspectRatio() {
        $calculator = new CanvasCalculator(123, 456);
        
        $this->assertEquals(123, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(456, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testWithoutChangesResolutionDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(159, 753);
        $calculator->keepAspectRatio(false);
        
        $this->assertEquals(159, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(753, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputWidthKeepAspectRatio() {
        $calculator = new CanvasCalculator(251, 501, 50);        
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(100, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputHeightKeepAspectRatio() {
        $calculator = new CanvasCalculator(251, 501, null, 200);
                           
        $this->assertEquals(100, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(200, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputResolutionKeepAspectRatio() {
        $calculator = new CanvasCalculator(51, 501, 150, 30);
                           
        $this->assertEquals(3, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(30, $calculator->getCalculatedCanvasHeight());        
        
        //new test
        $calculator2 = new CanvasCalculator(51, 501, 30, 350);
        
        $this->assertEquals(30, $calculator2->getCalculatedCanvasWidth());
        $this->assertEquals(295, $calculator2->getCalculatedCanvasHeight());        
    }
    
    public function testChangeMaxOutputWidthDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(126, 523, 50);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(208, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputHeightDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(127, 145, null, 45);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(39, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(45, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeOutputResolutionDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(748, 259, 25, 45);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(25, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(45, $calculator->getCalculatedCanvasHeight());
    }
}