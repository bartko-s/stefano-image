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
        $calculator = new CanvasCalculator(150, 30, 50, 50);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(10, $calculator->getCalculatedCanvasHeight());        
        
        //new test
        $calculator2 = new CanvasCalculator(30, 150, 50, 50);
        
        $this->assertEquals(10, $calculator2->getCalculatedCanvasWidth());
        $this->assertEquals(50, $calculator2->getCalculatedCanvasHeight());        
    }
    
    public function testChangeMaxOutputWidthDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(150, 500, 50);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(167, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeMaxOutputHeightDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(150, 500, null, 50);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(15, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(50, $calculator->getCalculatedCanvasHeight());
    }
    
    public function testChangeOutputResolutionDontKeepAspectRatio() {
        $calculator = new CanvasCalculator(750, 250, 50, 50);
        $calculator->keepAspectRatio(false);
                           
        $this->assertEquals(50, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals(50, $calculator->getCalculatedCanvasHeight());
    }
}