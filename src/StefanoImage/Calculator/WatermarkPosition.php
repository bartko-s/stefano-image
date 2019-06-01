<?php

namespace StefanoImage\Calculator;

use StefanoImage\Exception\InvalidArgumentException;
use StefanoImage\ImageInterface;

/**
 * Watermark position calculator.
 *
 * Vypocita rozmer a poziciu watermarku na zaklade vstupnych parametrov
 */
class WatermarkPosition
{
    private $canvasWidth;
    private $canvasHeight;
    private $watermarkWidthPercent;
    private $watermarkHeightPercent;
    private $inputWatermarkWidth;
    private $inputWatermarkHeight;
    private $position;
    private $marginPercent;

    /**
     * @param int $canvasWidth
     * @param int $canvasHeight
     * @param int $watermarkWidthPercent  valid 1 - 100
     * @param int $watermarkHeightPercent valid 1 - 100
     * @param int $inputWatermarkWidth
     * @param int $inputWatermarkHeight
     * @param int $position               ImageInterface::WATERMARK_POSITION_XXX
     * @param int $marginPercent          valid 1 - 40
     */
    public function __construct(
        $canvasWidth,
        $canvasHeight,
        $watermarkWidthPercent,
        $watermarkHeightPercent,
        $inputWatermarkWidth,
        $inputWatermarkHeight,
        $position,
        $marginPercent = 10
    ) {
        $this->setCanvasWidth($canvasWidth)
            ->setCanvasHeight($canvasHeight)
            ->setWatermarkWidthPercent($watermarkWidthPercent)
            ->setWatermarkHeightPercent($watermarkHeightPercent)
            ->setInputWatermarkWidth($inputWatermarkWidth)
            ->setInputWatermarkHeight($inputWatermarkHeight)
            ->setPosition($position)
            ->setMarginPercent($marginPercent);
    }

    /**
     * @throws InvalidArgumentException Unknown position
     *
     * @return int
     */
    public function getCalculatedXPosition()
    {
        $currentPosition = $this->getPosition();

        if (ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT == $currentPosition
            || ImageInterface::WATERMARK_POSITION_TOP_RIGHT == $currentPosition) {
            $value = $this->getCanvasWidth() - $this->getMarginWidth()
                - $this->getCalculatedWidth();
        } elseif (ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT == $currentPosition
            || ImageInterface::WATERMARK_POSITION_TOP_LEFT == $currentPosition) {
            $value = $this->getMarginWidth();
        } elseif (ImageInterface::WATERMARK_POSITION_CENTER == $currentPosition) {
            return ($this->getCanvasWidth() / 2) - ($this->getCalculatedWidth() / 2);
        } else {
            throw new InvalidArgumentException('Unknown position "'.$currentPosition.'"');
        }

        return ceil($value);
    }

    /**
     * @throws InvalidArgumentException Unknown position
     *
     * @return int
     */
    public function getCalculatedYPosition()
    {
        $currentPosition = $this->getPosition();

        if (ImageInterface::WATERMARK_POSITION_BOTTOM_RIGHT == $currentPosition
            || ImageInterface::WATERMARK_POSITION_BOTTOM_LEFT == $currentPosition) {
            $value = $this->getCanvasHeight() - $this->getMarginHeight()
                - $this->getCalculatedHeight();
        } elseif (ImageInterface::WATERMARK_POSITION_TOP_LEFT == $currentPosition
            || ImageInterface::WATERMARK_POSITION_TOP_RIGHT == $currentPosition) {
            $value = $this->getMarginHeight();
        } elseif (ImageInterface::WATERMARK_POSITION_CENTER == $currentPosition) {
            $value = ($this->getCanvasHeight() / 2) - ($this->getCalculatedHeight() / 2);
        } else {
            throw new InvalidArgumentException('Unknown position "'.$currentPosition.'"');
        }

        return ceil($value);
    }

    /**
     * @return int
     */
    public function getCalculatedWidth()
    {
        $watemarkWidth = $this->getInputWatermarkWidth();
        $watemarkHeight = $this->getInputWatermarkHeight();

        $maxWatermarkWidth = ($this->getCanvasWidth() - (2 * $this->getMarginWidth()))
            * ($this->getWatermarkWidthPercent() / 100);
        $maxWatermarkHeight = ($this->getCanvasHeight() - (2 * $this->getMarginHeight()))
            * ($this->getWatermarkHeightPercent() / 100);

        $r1 = $watemarkWidth / $maxWatermarkWidth;
        $r2 = $watemarkHeight / $maxWatermarkHeight;

        if ($r1 < $r2) {
            return ceil($watemarkWidth / $r2);
        }

        return ceil($maxWatermarkWidth);
    }

    /**
     * @return int
     */
    public function getCalculatedHeight()
    {
        $watemarkWidth = $this->getInputWatermarkWidth();
        $watemarkHeight = $this->getInputWatermarkHeight();

        $maxWatermarkWidth = ($this->getCanvasWidth() - (2 * $this->getMarginWidth()))
            * ($this->getWatermarkWidthPercent() / 100);
        $maxWatermarkHeight = ($this->getCanvasHeight() - (2 * $this->getMarginHeight()))
            * ($this->getWatermarkHeightPercent() / 100);

        $r1 = $watemarkWidth / $maxWatermarkWidth;
        $r2 = $watemarkHeight / $maxWatermarkHeight;

        if ($r1 > $r2) {
            return ceil($watemarkHeight / $r1);
        }

        return ceil($maxWatermarkHeight);
    }

    /**
     * @return int
     */
    private function getMarginWidth()
    {
        return ($this->getMarginPercent() / 100) * $this->getCanvasWidth();
    }

    /**
     * @return int
     */
    private function getMarginHeight()
    {
        return ($this->getMarginPercent() / 100) * $this->getCanvasHeight();
    }

    /**
     * @param int $canvasWidth
     *
     * @return self
     */
    private function setCanvasWidth($canvasWidth)
    {
        $this->canvasWidth = ceil($canvasWidth);

        return $this;
    }

    /**
     * @return int
     */
    private function getCanvasWidth()
    {
        return $this->canvasWidth;
    }

    /**
     * @param int $canvasHeight
     *
     * @return self
     */
    private function setCanvasHeight($canvasHeight)
    {
        $this->canvasHeight = ceil($canvasHeight);

        return $this;
    }

    /**
     * @return int
     */
    private function getCanvasHeight()
    {
        return $this->canvasHeight;
    }

    /**
     * @param int $watermarkWidthPercent 1 - 100
     *
     * @return self
     */
    private function setWatermarkWidthPercent($watermarkWidthPercent)
    {
        $watermarkWidthPercent = ceil($watermarkWidthPercent);

        if (1 > $watermarkWidthPercent) {
            $this->watermarkWidthPercent = 1;
        } elseif (100 < $watermarkWidthPercent) {
            $this->watermarkWidthPercent = 100;
        } else {
            $this->watermarkWidthPercent = $watermarkWidthPercent;
        }

        return $this;
    }

    /**
     * @return int
     */
    private function getWatermarkWidthPercent()
    {
        return $this->watermarkWidthPercent;
    }

    /**
     * @param int $watermarkHeightPercent 1 - 100
     *
     * @return self
     */
    private function setWatermarkHeightPercent($watermarkHeightPercent)
    {
        $watermarkHeightPercent = ceil($watermarkHeightPercent);

        if (1 > $watermarkHeightPercent) {
            $this->watermarkHeightPercent = 1;
        } elseif (100 < $watermarkHeightPercent) {
            $this->watermarkHeightPercent = 100;
        } else {
            $this->watermarkHeightPercent = $watermarkHeightPercent;
        }

        return $this;
    }

    /**
     * @return int
     */
    private function getWatermarkHeightPercent()
    {
        return $this->watermarkHeightPercent;
    }

    /**
     * @param int $inputWatermarkWidth
     *
     * @return self
     */
    private function setInputWatermarkWidth($inputWatermarkWidth)
    {
        $this->inputWatermarkWidth = ceil($inputWatermarkWidth);

        return $this;
    }

    /**
     * @return int
     */
    private function getInputWatermarkWidth()
    {
        return $this->inputWatermarkWidth;
    }

    /**
     * @param int $inputWatermarkHeight
     *
     * @return self
     */
    private function setInputWatermarkHeight($inputWatermarkHeight)
    {
        $this->inputWatermarkHeight = ceil($inputWatermarkHeight);

        return $this;
    }

    /**
     * @return int
     */
    private function getInputWatermarkHeight()
    {
        return $this->inputWatermarkHeight;
    }

    /**
     * @param string $position
     *
     * @return self;
     */
    private function setPosition($position)
    {
        $this->position = (string) trim(strtolower($position));

        return $this;
    }

    /**
     * @return string
     */
    private function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $marginPercent 1 - 40
     *
     * @return self
     */
    private function setMarginPercent($marginPercent)
    {
        $marginPercent = ceil($marginPercent);

        if (1 > $marginPercent) {
            $this->marginPercent = 1;
        } elseif (40 < $marginPercent) {
            $this->marginPercent = 40;
        } else {
            $this->marginPercent = $marginPercent;
        }

        return $this;
    }

    /**
     * @return int
     */
    private function getMarginPercent()
    {
        return $this->marginPercent;
    }
}
