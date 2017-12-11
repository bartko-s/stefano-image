<?php
namespace StefanoImageTest\Calculator;

use PHPUnit\Framework\TestCase;
use StefanoImage\ImageInterface;
use StefanoImage\Calculator\WatermarkPosition as WatermarkPositionCalculator;

class WatermarkPosition
    extends TestCase
{
    public function dataProvider() {
        return array(
            array(1000, 500, 20, 20, 150, 300,
                ImageInterface::WATERMARK_POSITION_CENTER, 10,
                40, 80, 480, 210),
            array(1000, 500, 20, 20, 300, 150,
                ImageInterface::WATERMARK_POSITION_CENTER, 10,
                160, 80, 420, 210),

            array(1000, 500, 20, 20, 150, 300,
                ImageInterface::WATERMARK_POSITION_TOP_LEFT, 10,
                40, 80, 100, 50),
            array(1000, 500, 20, 20, 300, 150,
                ImageInterface::WATERMARK_POSITION_TOP_LEFT, 10,
                160, 80, 100, 50),

            array(1000, 500, 20, 20, 150, 300,
                ImageInterface::WATERMARK_POSITION_TOP_RIGHT, 10,
                40, 80, 860, 50),
            array(1000, 500, 20, 20, 300, 150,
                ImageInterface::WATERMARK_POSITION_TOP_RIGHT, 10,
                160, 80, 740, 50),

            array(1000, 500, 20, 20, 150, 300,
                ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT, 10,
                40, 80, 100, 370),
            array(1000, 500, 20, 20, 300, 150,
                ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT, 10,
                160, 80, 100, 370),

            array(1000, 500, 20, 20, 150, 300,
                ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT, 10,
                40, 80, 860, 370),
            array(1000, 500, 20, 20, 300, 150,
                ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT, 10,
                160, 80, 740, 370),

            //min values (maxWatermarkWidth, maxWatermarkHeight, margin)
            array(1000, 500, 0, 0, 300, 150,
                ImageInterface::WATERMARK_POSITION_CENTER, 0,
                10, 5, 495, 248),

            //max values (maxWatermarkWidth, maxWatermarkHeight, margin)
            array(1000, 500, 100000, 1000000, 300, 150,
                ImageInterface::WATERMARK_POSITION_CENTER, 10000000,
                200, 100, 400, 200),
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function test($canvasWidth, $canvasHeight, $maxWatermarkWidthPercent,
            $maxWatermarkHeightPercent, $inputWatermarkWidth, $inputWatermarkHeight,
            $position, $marginPercent, $calculatedWidth, $calculatedHeight,
            $calculatedXPosition, $calculatedYPosition) {
        $calculator = new WatermarkPositionCalculator($canvasWidth, $canvasHeight,
            $maxWatermarkWidthPercent, $maxWatermarkHeightPercent, $inputWatermarkWidth,
            $inputWatermarkHeight, $position, $marginPercent);

        $this->assertEquals($calculatedWidth, $calculator->getCalculatedWidth());
        $this->assertEquals($calculatedHeight, $calculator->getCalculatedHeight());
        $this->assertEquals($calculatedXPosition, $calculator->getCalculatedXPosition());
        $this->assertEquals($calculatedYPosition, $calculator->getCalculatedYPosition());

    }

    public function testThrowExceptionIfPositionIsUnknownCalculatedXPosition() {
        $position = 'abc';
        $calculator = new WatermarkPositionCalculator(100, 100, 25, 25, 150, 150,
            $position);

        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown position "' . $position . '"');

        $calculator->getCalculatedXPosition();
    }

    public function testThrowExceptionIfPositionIsUnknownCalculatedYPosition() {
        $position = 'abc';
        $calculator = new WatermarkPositionCalculator(100, 100, 25, 25, 150, 150,
            $position);

        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown position "' . $position . '"');

        $calculator->getCalculatedYPosition();
    }
}