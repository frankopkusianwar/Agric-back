<?php
namespace App\Utils;

class CropMockData extends MockData
{
    protected $newCrop = [
        'photo_url' => 'http://storage.googleapis.com/ezyagric.com/Cotton2.jpg',
        'crop' => 'Carrot',
    ];
    protected $invalidNewCropData = [
        'photo_url'  => 'wrong-url',
        'crop'       => 'Carrot ?',
    ];

    public function getNewCropData()
    {
        return $this->newCrop;
    }
    
    public function getInvalidNewCropData()
    {
        return $this->invalidNewCropData;
    }

    public function getEditCropData()
    {
        $editCropData = $this->newCrop;
        $editCropData['crop'] = "Pineapple";
        return $editCropData;
    }
}
