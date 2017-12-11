<?php
namespace StefanoImageTest\Adapter;

use PHPUnit\Framework\TestCase;
use StefanoImage\Adapter\Gd as GdAdapter;

class GdTest
    extends TestCase
{
    protected function setUp() {
        if(!file_exists($this->getBasePath())) {
            mkdir($this->getBasePath(), 0777, true);
        }
    }
    
    protected function tearDown() {
        $basePath = $this->getBasePath();
        if(file_exists($basePath)) {
            $iterator = new \DirectoryIterator($basePath);
            foreach ($iterator as $file) {
                if($file->isFile()) {
                    unlink($file->getPathname());
                }
            }
            
            rmdir($this->getBasePath());
        }
        
        \Mockery::close();
    }
    
    private function getBasePath() {
        return TEMP_BASE_DIRECOTORY . '/ImageGdAdapter';
    }
    
    public function createCanvasAndSaveProvider() {
        return array(
            array(123, 456, 'saveAsJpeg', 'jpeg', 'image/jpeg'),
            array(123, 456, 'saveAsJpeg', 'jpeg', 'image/jpeg', 101),
            array(123, 456, 'saveAsJpeg', 'jpeg', 'image/jpeg', -10),
            array(147, 369, 'saveAsPng', 'png', 'image/png'),
            array(147, 369, 'saveAsPng', 'png', 'image/png', 101),
            array(147, 369, 'saveAsPng', 'png', 'image/png', -10),
            array(159, 158, 'saveAsGif', 'gif', 'image/gif'),
        );
    }
    
    /**
     * @dataProvider createCanvasAndSaveProvider
     */
    public function testCreateCanvasAndSave($outputWidth, $outputHeight, 
            $callMethod, $extension, $outputMimeType, $quality = null) {
        $adapter = new GdAdapter();
        
        $adapter->createCanvas($outputWidth, $outputHeight);
                
        if(null === $quality) {
            $adapter->$callMethod($this->getBasePath(), 'new-image');
        } else {
            $adapter->$callMethod($this->getBasePath(), 'new-image', $quality);
        }
        
        $newFilePath = $this->getBasePath() . '/new-image.' . $extension;
        $this->assertFileExists($newFilePath);
        
        $fileInfo = getimagesize($newFilePath);
        
        $this->assertEquals($outputWidth, $fileInfo[0]);
        $this->assertEquals($outputHeight, $fileInfo[1]);
        $this->assertEquals($outputMimeType, $fileInfo['mime']);
    }

    public function testGetCanvasWidth() {
        $adapter = new GdAdapter();
        $adapter->createCanvas(150, 250);

        $this->assertEquals(150, $adapter->getCanvasWidth());
    }

    public function testGetCanvasHeight() {
        $adapter = new GdAdapter();
        $adapter->createCanvas(150, 250);

        $this->assertEquals(250, $adapter->getCanvasHeight());
    }
    
    public function testThrowExceptionIfDrawImageDoesNostExist() {
        $imagePath = 'neexistuje';
        $adapter = new GdAdapter();
        $adapter->createCanvas(10, 10);

        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('File "' . $imagePath . '" does not exist');
        
        $adapter->drawImage($imagePath, 0, 0, 5, 5, 100);
    }
    
    public function testThrowExceptionIfTryDrawImageAndGivenFileIsNotImage() {
        $imagePath = __DIR__ . '/assets/file';
        $adapter = new GdAdapter();
        $adapter->createCanvas(10, 10);
        
        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Given file "' . $imagePath . '" is not image file');
        
        $adapter->drawImage($imagePath, 0, 0, 5, 5, 100);
    }
    
    public function testThrowExceptionIfTryDrawImageWithUnsuportedMimeType() {
        $imagePath = __DIR__ . '/assets/unsupported.ico';
        $adapter = new GdAdapter();
        $adapter->createCanvas(10, 10);
        
        $this->expectException(\StefanoImage\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Given file "' . $imagePath . '" has unsupported mime type');
        
        $adapter->drawImage($imagePath, 0, 0, 5, 5, 100);
    }
    
    public function drawImageFromSupportedImageFileProvider() {
        return array(
            array(__DIR__ . '/assets/source.jpg'),
            array(__DIR__ . '/assets/source.png'),
            array(__DIR__ . '/assets/source.gif'),
        );
    }
    
    /**
     * @dataProvider drawImageFromSupportedImageFileProvider
     */
    public function testDrawImageFromSupportedImage($file) {
        //test only if code is called without errors
        $adapter = new GdAdapter();
        $adapter->createCanvas(10, 10)
                ->drawImage($file, 0, 0, 5, 5, 50)
                ->drawImage($file, 0, 0, 5, 5, -125) //wrong opacity
                ->drawImage($file, 0, 0, 5, 5, 1256); //wrong opacity
    }
    
    public function testChangeBackgroundColor() {
        //test only if code is called without errors
        $adapter = new GdAdapter();
        $adapter->createCanvas(10, 10)
                ->backgroundColor(5, 5, 5)
                ->backgroundColor('a', '12', 589);
    }
    
}