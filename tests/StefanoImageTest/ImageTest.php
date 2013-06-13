<?php
namespace StefanoImageTest;

use StefanoImage\Image;

class ImageTest
    extends \PHPUnit_Framework_TestCase
{
    protected function tearDown() {
        \Mockery::close();
    }
    
    public function testThrowExceptionIfInputImageDoesNotExist() {
        $imagePath = 'neexistuje.jpg';
        $image = new Image();
        
        $this->setExpectedException('\StefanoImage\Exception\InvalidArgumentException',
                'File "' . $imagePath . '" does not exist');
        
        $image->sourceImage($imagePath);
    }
    
    public function testThrowExceptionIfFileIsNotImage() {
        $imagePath = __DIR__ . '/assets/file';
        $image = new Image();
        
        $this->setExpectedException('\StefanoImage\Exception\InvalidArgumentException',
                'File "' . $imagePath . '" is not image file');
        
        $image->sourceImage($imagePath);
    }
    
    public function testThrowExceptionIfCallSaveAndSourceImageHasNotBeenSet() {
        $image = new Image();
        
        $this->setExpectedException('\StefanoImage\Exception\LogicException',
                'First you must set source image file');
        
        $image->save('dest', 'name');
    }
    
    public function testThrowExceptionIfrequiredOutputFormatIsNotSupported() {
        $outputFormat = 'unsupported-type';
        $image = new Image();
        
        $this->setExpectedException('\StefanoImage\Exception\InvalidArgumentException',
                'Required "' . $outputFormat . '" output format is not supported');
        
        $image->outputFormat($outputFormat);
    }
    
    public function testSaveAsDefaultJpegFormat() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 75)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->save($targetPath, $newName);
    }
    
    public function testSaveAsPng() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsPng')
                    ->with($targetPath, $newName, 75)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->outputFormat(Image::OUTPUT_FORMAT_PNG)
              ->save($targetPath, $newName);
    }    
    
    public function testSaveAsGif() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsGif')
                    ->with($targetPath, $newName)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->outputFormat(Image::OUTPUT_FORMAT_GIF)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputKvality() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        $outputKvality = 17;
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, $outputKvality)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality($outputKvality)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputKvalityWrongValue() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 1)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality(-125)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputKvalityWrongValue2() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 100)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality(99999)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputResolutionAndAdaptCanvasToTheImage() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 200)
                    ->andReturn($adapterMock)
                    ->once();        
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->resize(400, 400)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputResolutionAndDontAdaptCanvasToTheImage() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 400)
                    ->andReturn($adapterMock)
                    ->once();        
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->resize(400, 400, false)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputResolutionAdaptCanvasToTheImageKeepAspectRatio() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 200)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 0, 400, 200)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->resize(400, 400)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputResolutionDontAdaptCanvasToTheImageKeepAspectRatio() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 400)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 100, 400, 200)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->resize(400, 400, false)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputResolutionDontAdaptCanvasToTheImageDontKeepAspectRatio() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 400)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 0, 400, 400)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath, false)
              ->resize(400, 400, false)
              ->save($targetPath, $newName);
    }
    
    public function testDefaultBackgroundColor() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('backgroundColor')
                    ->with(200, 200, 200)
                    ->andReturn($adapterMock)
                    ->ordered()
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath, false)
              ->backgroundColor(200, 200, 200)
              ->save($targetPath, $newName);
    }
    
    public function testChangeBackgroundColor() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('backgroundColor')
                    ->with(125, 250, 75)
                    ->andReturn($adapterMock)
                    ->ordered()
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath, false)
              ->backgroundColor(125, 250, 75)
              ->save($targetPath, $newName);
    }
    
    public function testAddWatermarksCenterDefaultPosition() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $watermarkPath = __DIR__ . '/assets/watermark.gif';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('drawWatermark')
                    ->with($watermarkPath, 450, 240, 100, 20, 62)
                    ->andReturn($adapterMock)
                    ->twice();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath, false)
              ->addWatermark($watermarkPath, 100, 100, 62)
              ->addWatermark($watermarkPath, 100, 100, 62)
              ->save($targetPath, $newName);
    }
    
    public function testClearWatermarks() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $watermarkPath = __DIR__ . '/assets/watermark.gif';
        $targetPath = '/target';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\AdapterInterface');
        $adapterMock->shouldIgnoreMissing();        
        $adapterMock->shouldReceive('drawWatermark')
                    ->andReturn($adapterMock)
                    ->never();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath, false)
              ->addWatermark($watermarkPath, 100, 100)
              ->clearWatermarks()
              ->save($targetPath, $newName);
    }
}