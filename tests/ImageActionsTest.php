<?php

use App\Utils\Helpers;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as BaseUploadedFile;

class ImageActionsTest extends TestCase
{
    private $imageUrl;
    const IMAGE_PATH = 'test/';
    const GCS_BASE_URL = 'https://storage.googleapis.com/ezyagric_dev_image_bucket/';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testShouldSuccessfullyUploadAndDeleteImage()
    {
        $imagePath = __DIR__ . "/stubs/images/crop_pest.jpeg";
        $file = UploadedFile::createFromBase(new BaseUploadedFile(
            $imagePath,
            'crop_pest.jpeg',
            'image/jpeg'
        ));
        $this->imageUrl = Helpers::processImageUpload($file, self::IMAGE_PATH);
        $photoUrl = explode('/', $this->imageUrl);
        Helpers::imageActions($photoUrl[4] . '/'. $photoUrl[5], null, 'delete');
        $this->addToAssertionCount(1);
    }
}
