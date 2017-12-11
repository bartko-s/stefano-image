<?php
namespace StefanoImageTest;

use PHPUnit\Framework\TestCase;
use StefanoImage\Adapter\Gd as GdAdapter;
use StefanoImage\Image;

class ImageTest
    extends TestCase
{
    protected function tearDown() {
        \Mockery::close();
    }
    
    public function testThrowExceptionIfInputImageDoesNotExist() {
        $imagePath = 'neexistuje.jpg';
        $image = new Image();
        
        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('File "' . $imagePath . '" does not exist');
        
        $image->sourceImage($imagePath);
    }
    
    public function testThrowExceptionIfFileIsNotImage() {
        $imagePath = __DIR__ . '/assets/file';
        $image = new Image();
        
        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('File "' . $imagePath . '" is not image file');
        
        $image->sourceImage($imagePath);
    }
    
    public function testThrowExceptionIfCallSaveAndSourceImageHasNotBeenSet() {
        $image = new Image();
        
        $this->expectException(\StefanoImage\Exception\LogicException::class);
        $this->expectExceptionMessage('First you must set source image file');
        
        $image->save('dest', 'name');
    }
    
    public function testThrowExceptionIfRequiredOutputFormatIsNotSupported() {
        $outputFormat = 'unsupported-type';
        $image = new Image();
        
        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Required "' . $outputFormat . '" output format is not supported');
        
        $image->outputFormat($outputFormat);
    }
    
    public function testSaveAsDefaultJpegFormat() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath =  __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
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
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
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
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('saveAsGif')
                    ->with($targetPath, $newName)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->outputFormat(Image::OUTPUT_FORMAT_GIF)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputQuality() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        $outputQuality = 17;
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, $outputQuality)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality($outputQuality)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputQualityWrongValue() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 1)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality(-125)
              ->save($targetPath, $newName);
    }
    
    public function testChangeOutputQualityWrongValue2() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 100)
                    ->andReturn($adapterMock)
                    ->once();
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->quality(99999)
              ->save($targetPath, $newName);
    }
    
    public function testResize() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapter = new GdAdapter();
        $adapter->createCanvas(400, 200);

        $adapterMock = \Mockery::mock($adapter);
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 200)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 0, 400, 200, 100)
                    ->andReturn($adapterMock)
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->resize(400, 400)
              ->save($targetPath, $newName);
    }

    public function testPad() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapter = new GdAdapter();
        $adapter->createCanvas(400, 400);

        $adapterMock = \Mockery::mock($adapter);
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 400)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 100, 400, 200, 100)
                    ->andReturn($adapterMock)
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->pad(400, 400)
              ->save($targetPath, $newName);
    }
    
    public function testAdaptiveResize() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';

        $adapter = new GdAdapter();
        $adapter->createCanvas(400, 400);

        $adapterMock = \Mockery::mock($adapter);
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('createCanvas')
                    ->with(400, 400)
                    ->andReturn($adapterMock)
                    ->once();        
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 0, 400, 400, 100)
                    ->andReturn($adapterMock)
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->adaptiveResize(400, 400)
              ->save($targetPath, $newName);
    }
    
    public function testDefaultBackgroundColor() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('backgroundColor')
                    ->with(200, 200, 200)
                    ->andReturn($adapterMock)
                    ->ordered()
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->pad(100, 100)
              ->save($targetPath, $newName);
    }
    
    public function testChangeBackgroundColor() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('backgroundColor')
                    ->with(125, 250, 75)
                    ->andReturn($adapterMock)
                    ->ordered()
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->pad(100, 100)
              ->backgroundColor(125, 250, 75)
              ->save($targetPath, $newName);
    }
    
    public function testAddWatermarksCenterDefaultPosition() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $watermarkPath = __DIR__ . '/assets/watermark.gif';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';

        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('drawImage')
                    ->with($sourceImagePath, 0, 0, 1000, 500, 100)
                    ->andReturn($adapterMock)
                    ->once();
        $adapterMock->shouldReceive('drawImage')
                    ->with($watermarkPath, 100, 170, 800, 160, 62)
                    ->andReturn($adapterMock)
                    ->twice();
        $adapterMock->shouldReceive('saveAsJpeg');

        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->addWatermark($watermarkPath, 100, 100, 62)
              ->addWatermark($watermarkPath, 100, 100, 62)
              ->save($targetPath, $newName);
    }
    
    public function testClearWatermarks() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $watermarkPath = __DIR__ . '/assets/watermark.gif';
        $targetPath = __DIR__ . '/assets/temp';
        $newName = 'new-image-name';
        
        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('drawImage')
                    ->andReturn($adapterMock)
                    ->once();
        $adapterMock->shouldReceive('saveAsJpeg');
        
        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->addWatermark($watermarkPath, 100, 100)
              ->clearWatermarks()
              ->save($targetPath, $newName);
    }

    public function testCreteTargetDirectoryIfNotExists() {
        $sourceImagePath = __DIR__ . '/assets/source.jpg';
        $targetPath = __DIR__ . '/assets/temp/this-dir/does-not-exists';

        @rmdir(__DIR__ . '/assets/temp/this-dir/does-not-exists');
        @rmdir(__DIR__ . '/assets/temp/this-dir');

        $this->assertDirectoryNotExists($targetPath, 'Before test directory must not exists');

        $newName = 'new-image-name';

        $adapterMock = \Mockery::mock('\StefanoImage\Adapter\Gd');
        $adapterMock->makePartial();
        $adapterMock->shouldReceive('saveAsJpeg')
                    ->with($targetPath, $newName, 75)
                    ->andReturn($adapterMock)
                    ->once();

        $image = new Image($adapterMock);
        $image->sourceImage($sourceImagePath)
              ->save($targetPath, $newName);

        $this->assertDirectoryExists($targetPath);
    }
}