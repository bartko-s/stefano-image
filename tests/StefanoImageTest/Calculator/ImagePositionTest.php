<?php

namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\ImagePosition as ImageCalculator;
use StefanoImageTest\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ImagePositionTest extends TestCase
{
    public function dataPrivider()
    {
        return array(
            //dont keep aspect ratio
            array(80, 160, 100, 300, true, 13, 0, 54, 160),
            array(80, 160, 300, 150, true, 0, 60, 80, 40),

            //keep aspect ratio
            array(80, 160, 100, 300, false, 0, 0, 80, 160),
            array(80, 160, 300, 150, false, 0, 0, 80, 160),
        );
    }

    /**
     * @dataProvider dataPrivider
     *
     * @param mixed $canvasWidth
     * @param mixed $canvasHeight
     * @param mixed $imageWidth
     * @param mixed $imageHeight
     * @param mixed $keepAspectRatio
     * @param mixed $calculatedXPosition
     * @param mixed $calculatedYPosition
     * @param mixed $calculatedWidth
     * @param mixed $calculatedHeight
     */
    public function test(
        $canvasWidth,
        $canvasHeight,
        $imageWidth,
        $imageHeight,
        $keepAspectRatio,
        $calculatedXPosition,
        $calculatedYPosition,
        $calculatedWidth,
        $calculatedHeight
    ) {
        $calculator = new ImageCalculator(
            $canvasWidth,
            $canvasHeight,
            $imageWidth,
            $imageHeight,
            $keepAspectRatio
        );

        $this->assertEquals($calculatedXPosition, $calculator->getCalculatedXPosition());
        $this->assertEquals($calculatedYPosition, $calculator->getCalculatedYPosition());
        $this->assertEquals($calculatedWidth, $calculator->getCalculatedWidth());
        $this->assertEquals($calculatedHeight, $calculator->getCalculatedHeight());
    }
}
