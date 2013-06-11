<?php
namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\ImagePosition as ImageCalculator;

class ImagePositionTest
    extends \PHPUnit_Framework_TestCase
{
    public function testDontKeepAspektRatio() {
       $calculator = new ImageCalculator(123, 456, 789, 963);
       $calculator->keepAspectRatio(false);
       
       $this->assertEquals(0, $calculator->getCalculatedXPosition());
       $this->assertEquals(0, $calculator->getCalculatedYPosition());
       $this->assertEquals(123, $calculator->getCalculatedWidth());
       $this->assertEquals(456, $calculator->getCalculatedHeight());
    }
    
    public function testKeepAspektRatioPortraitImage() {
       $calculator = new ImageCalculator(311, 302, 72, 455);
       
       $this->assertEquals(48, $calculator->getCalculatedWidth());
       $this->assertEquals(302, $calculator->getCalculatedHeight());
       $this->assertEquals(180, $calculator->getCalculatedXPosition());
       $this->assertEquals(0, $calculator->getCalculatedYPosition());       
    }
    
    public function testKeepAspektRatioLandscapeImage() {
       $calculator = new ImageCalculator(315, 311, 427, 86);
       
       $this->assertEquals(315, $calculator->getCalculatedWidth());
       $this->assertEquals(63, $calculator->getCalculatedHeight());
       $this->assertEquals(0, $calculator->getCalculatedXPosition());
       $this->assertEquals(187, $calculator->getCalculatedYPosition());       
    }
}