<?php
namespace StefanoImageTest\Calculator;

use StefanoImage\ImageInterface;
use StefanoImage\Calculator\WatermarkPosition as WatermarkPositionCalculator;

class WatermarkPosition
    extends \PHPUnit_Framework_TestCase
{
    public function testCalculateCenterPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 150, 300,
                ImageInterface::WATERMARK_POSITION_CENTER);
        
        $this->assertEquals(100, $calculator->getCalculatedWidth());
        $this->assertEquals(200, $calculator->getCalculatedHeight());        
        $this->assertEquals(450, $calculator->getCalculatedXPosition());
        $this->assertEquals(150, $calculator->getCalculatedYPosition());
    }
    
    public function testIfPositionIsUnsuportedUseCenterPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 150, 300,
                'this-position-is-not-exist');
        
        $this->assertEquals(100, $calculator->getCalculatedWidth());
        $this->assertEquals(200, $calculator->getCalculatedHeight());        
        $this->assertEquals(450, $calculator->getCalculatedXPosition());
        $this->assertEquals(150, $calculator->getCalculatedYPosition());
    }
    
    public function testCalculateTopLeftPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 300, 150,
            ImageInterface::WATERMARK_POSITION_TOP_LEFT);
        
        $this->assertEquals(200, $calculator->getCalculatedWidth());
        $this->assertEquals(100, $calculator->getCalculatedHeight());        
        $this->assertEquals(50, $calculator->getCalculatedXPosition());
        $this->assertEquals(25, $calculator->getCalculatedYPosition());
    }
    
    public function testCalculateTopRightPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 300, 150,
            ImageInterface::WATERMARK_POSITION_TOP_RIGHT);
        
        $this->assertEquals(200, $calculator->getCalculatedWidth());
        $this->assertEquals(100, $calculator->getCalculatedHeight());        
        $this->assertEquals(750, $calculator->getCalculatedXPosition());
        $this->assertEquals(25, $calculator->getCalculatedYPosition());
    }
    
    public function testCalculateBottomLeftPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 300, 150,
            ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT);
        
        $this->assertEquals(200, $calculator->getCalculatedWidth());
        $this->assertEquals(100, $calculator->getCalculatedHeight());        
        $this->assertEquals(50, $calculator->getCalculatedXPosition());
        $this->assertEquals(375, $calculator->getCalculatedYPosition());
    }
    
    public function testCalculateBottomRightPosition() {
        $calculator = new WatermarkPositionCalculator(1000, 500, 200, 200, 300, 150,
            ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT);
        
        $this->assertEquals(200, $calculator->getCalculatedWidth());
        $this->assertEquals(100, $calculator->getCalculatedHeight());        
        $this->assertEquals(750, $calculator->getCalculatedXPosition());
        $this->assertEquals(375, $calculator->getCalculatedYPosition());
    }
}