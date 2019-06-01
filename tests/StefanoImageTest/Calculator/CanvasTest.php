<?php

namespace StefanoImageTest\Calculator;

use StefanoImage\Calculator\Canvas as CanvasCalculator;
use StefanoImageTest\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CanvasTest extends TestCase
{
    public function dataProvider()
    {
        return array(
            /*
             * Output resolution is not defined.
             * Calculated resolution must be same as input image.
             */
            array(250, 500, null, null, true, 250, 500),
            array(250, 500, null, null, false, 250, 500),

            /*
             * Defined only one dimension
             * Output resolution is adapted
             */
            array(250, 500, 125, null, true, 125, 250),
            array(250, 500, null, 125, true, 63, 125),
            array(250, 500, 125, null, false, 125, 250),
            array(250, 500, null, 125, false, 63, 125),

            // Output resolution is defined
            array(250, 500, 125, 125, true, 63, 125), //resize, pad
            array(500, 250, 125, 125, true, 125, 63), //resize, pad
            array(250, 500, 125, 125, false, 125, 125), //adaptive resize
        );
    }

    /**
     * @dataProvider dataProvider
     *
     * @param mixed $inputImageWidth
     * @param mixed $inputImageHeight
     * @param mixed $maxOutputImageWidth
     * @param mixed $maxOutputImageHeight
     * @param mixed $adaptOutputResolution
     * @param mixed $calculatedWidth
     * @param mixed $calculatedHeight
     */
    public function test(
        $inputImageWidth,
        $inputImageHeight,
        $maxOutputImageWidth,
        $maxOutputImageHeight,
        $adaptOutputResolution,
        $calculatedWidth,
        $calculatedHeight
    ) {
        $calculator = new CanvasCalculator(
            $inputImageWidth,
            $inputImageHeight,
            $maxOutputImageWidth,
            $maxOutputImageHeight,
            $adaptOutputResolution
        );

        $this->assertEquals($calculatedWidth, $calculator->getCalculatedCanvasWidth());
        $this->assertEquals($calculatedHeight, $calculator->getCalculatedCanvasHeight());
    }
}
