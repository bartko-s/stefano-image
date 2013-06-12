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
    const WATERMARK_POSITION_CENTER = 'centre';
    
    /**
     * @param string $sourceImagePath
     * @param boolean $keepAspectRatio
     * @return self
     */
    public function sourceImage($sourceImagePath, $keepAspectRatio = true);
    
    /**
     * @param string $destination
     * @param string $name
     * @return self
     */
    public function save($destination, $name);  
    
    /**
     * @param int $width
     * @param int $height
     * @param bool $adapt adapt canvas size to the source image proportion
     * @return self
     */
    public function resize($width = null, $height = null, $adapt = true);
    
    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return self
     */
    public function backgroundColor($red, $green, $blue);
    
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
    public function addWwatermarkImage($imagePath, $maxWidth, $maxHeight, 
            $opacity = 30, $position = ImageInterface::WATERMARK_POSITION_CENTER);
    public function clearWatermarks();
}