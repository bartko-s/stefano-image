<?php
namespace StefanoImage;

interface ImageInterface
{
    const OUTPUT_FORMAT_JPEG = 'jpeg';
    const OUTPUT_FORMAT_GIF = 'gif';
    const OUTPUT_FORMAT_PNG = 'png';
    
    const WATERMARK_POSITION_TOP_LEFT = 'top-left';
    const WATERMARK_POSITION_TOP_RIGHT = 'top-right';
    const WATERMARK_POSITION_BOTTOM_LEFT = 'bottom-left';
    const WATERMARK_POSITION_BOTTOM_RIGHT = 'bottom-right';
    const WATERMARK_POSITION_CENTER = 'center';
    
    /**
     * @param string $sourceImagePath
     * @return self
     */
    public function sourceImage($sourceImagePath);
    
    /**
     * @param string $destination
     * @param string $name
     * @return self
     */
    public function save($destination, $name);  
    
    /**
     * @param int $maxWidth
     * @param int $maxHeight
     * @return self
     */
    public function resize($maxWidth = null, $maxHeight = null);

    /**
     * @param int $width
     * @param int $height
     * @return self
     */
    public function adaptiveResize($width, $height);

    /**
     * @param int $width
     * @param int $height
     * @param array|null $color
     * @return self
     */
    public function pad($width, $height, $color = array(200, 200, 200));
        
    /**
     * @param int $quality form 1 to 100
     * @return self
     */
    public function quality($quality);
    
    /**
     * @param string $outputFormat
     * @return self
     */
    public function outputFormat($outputFormat);
    
    /**
     * @param string $imagePath
     * @param int $maxWidthPercent 1 - 100
     * @param int $maxHeightPercent 1 - 100
     * @param int $opacity from 1 to 100
     * @param string $position ImageInterface::WATERMARK_POSITION_XXX
     * @return self
     */
    public function addWatermark($imagePath, $maxWidthPercent, $maxHeightPercent,
            $opacity = 30, $position = ImageInterface::WATERMARK_POSITION_CENTER);
    
    /**
     * @return self
     */
    public function clearWatermarks();
}