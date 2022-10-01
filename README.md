Stefano Image
=============

[![Build Status](https://app.travis-ci.com/bartko-s/stefano-image.svg?branch=master)](https://app.travis-ci.com/bartko-s/stefano-image)
[![Coverage Status](https://coveralls.io/repos/bartko-s/stefano-image/badge.png?branch=master)](https://coveralls.io/r/bartko-s/stefano-image?branch=master) 

Features
--------
- Resize and save image
- Add watermark
- Supported input and output format jpg, png, gif

Dependencies
------------
- php GD2 extension

Instalation using Composer
--------------------------
1. Run command ``` composer require stefano/stefano-image ```

Usage
-----

This is the original image

<img src="./doc/images/source.jpeg" />

- resize and keep source image aspect ration

```
$maxWidth = 200;
$maxHeight = 200;
$resizer = new \StefanoImage\Image();
$resizer->sourceImage($sourceImage)
        ->resize($maxWidth, $maxHeight)
        ->save($outputDir, $name);
```

This is the output

<img src="./doc/images/resize.jpeg" />

- adaptive resize

```
$width = 200;
$height = 50;
$resizer = new \StefanoImage\Image();
$resizer->sourceImage($sourceImage)
        ->adaptiveResize($width, $height)
        ->save($outputDir, $name);
```

This is the output

<img src="./doc/images/adaptive-resize.jpeg" />

- pad

```
$width = 200;
$height = 200;
$resizer = new \StefanoImage\Image();
$resizer->sourceImage($sourceImage)
        ->pad($width, $height)
        ->save($outputDir, $name);
```

This is the output

<img src="./doc/images/pad.jpeg" />

- pad and change background color

```
$width = 350;
$height = 150;
$resizer = new \StefanoImage\Image();
$resizer->sourceImage($sourceImage)
        ->pad($width, $height)
        ->backgroundColor(35, 210, 240)
        ->save($outputDir, $name);
```

This is the output

<img src="./doc/images/pad-2.jpeg" />

- add watermark

```
$maxWidth = 350;
$maxHeight = 150;
$maxWidthPercent = 40;
$maxHeightPercent = 40;
$opacity = 30;
$watermarkPosition = \StefanoImage\Image::WATERMARK_POSITION_TOP_RIGHT;
$resizer = new \StefanoImage\Image();
$resizer->sourceImage($sourceImage)
        ->resize($maxWidth, $maxHeight)
        ->addWatermark($watermark, $maxWidthPercent, $maxHeightPercent, $opacity, $watermarkPosition)
        ->save($outputDir, $name);
```

This is the output

<img src="./doc/images/watermark.jpeg" />

- change output format

```
$resizer->outputFormat(\StefanoImage\Image::OUTPUT_FORMAT_PNG);
```

- change output quality

```
$resizer->quality(15);
```