<?php
namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\ImagePosition as ImageCalculator;

class ImagePositionTest
    extends \PHPUnit_Framework_TestCase
{
    public function testDontKeepAspektRatio() {
       $calculator = new ImageCalculator(150, 250, 300, 300);
       $calculator->keepAspectRatio(false);
       
       $this->assertEquals(0, $calculator->getCalculatedXPosition());
       $this->assertEquals(0, $calculator->getCalculatedYPosition());
       $this->assertEquals(150, $calculator->getCalculatedWidth());
       $this->assertEquals(250, $calculator->getCalculatedHeight());
    }
    
    public function testKeepAspektRatioPortraitImage() {
       $calculator = new ImageCalculator(300, 300, 150, 300);
       
       $this->assertEquals(150, $calculator->getCalculatedWidth());
       $this->assertEquals(300, $calculator->getCalculatedHeight());
       $this->assertEquals(75, $calculator->getCalculatedXPosition());
       $this->assertEquals(0, $calculator->getCalculatedYPosition());       
       
       //test
       $calculator2 = new ImageCalculator(300, 300, 450, 750);
       
       $this->assertEquals(180, $calculator2->getCalculatedWidth());
       $this->assertEquals(300, $calculator2->getCalculatedHeight());
       $this->assertEquals(60, $calculator2->getCalculatedXPosition());
       $this->assertEquals(0, $calculator2->getCalculatedYPosition());       
    }
    
    public function testKeepAspektRatioLandscapeImage() {
       $calculator = new ImageCalculator(300, 300, 300, 150);
       
       $this->assertEquals(300, $calculator->getCalculatedWidth());
       $this->assertEquals(150, $calculator->getCalculatedHeight());
       $this->assertEquals(0, $calculator->getCalculatedXPosition());
       $this->assertEquals(75, $calculator->getCalculatedYPosition());       
       
       //test
       $calculator2 = new ImageCalculator(300, 300, 750, 450);
       
       $this->assertEquals(300, $calculator2->getCalculatedWidth());
       $this->assertEquals(180, $calculator2->getCalculatedHeight());
       $this->assertEquals(0, $calculator2->getCalculatedXPosition());
       $this->assertEquals(60, $calculator2->getCalculatedYPosition());       
    }
}