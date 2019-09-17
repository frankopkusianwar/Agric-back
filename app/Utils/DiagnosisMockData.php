<?php
namespace App\Utils;

use Faker\Factory;

class DiagnosisMockData extends MockData
{
    protected $diagnosis = [
        "cause" => "Virus",
        "name" => "Bean common mosaic virus",
        "control" => "<p>1. First control method.<br />2. Second control method.<br />3. Third control method</p>",
        "type" => "diagnosis",
        "eloquent_type" => "diagnosis",
        "category" => "Disease",
        "photo_url" => "https://images.unsplash.com/photo-1512006410192-5e496c2c207b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80",
        "explanation" => "<p>1. First control method.<br />2. Second control method.<br />3. Third control method</p>",
        "crop" => "Beans"
    ];

    protected $validData = [
        'name'       => 'Bean common Virus',
        'cause'      => 'We dont know',
        'crop'       => 'Beans',
        'control'    => 'Lorem Ipsum is simply',
        'explanation' => '1. Ipsum is simply, 2. Lorem Ipsum is simply',
    ];

    public function getDiagnosisInformation()
    {
        $faker = Factory::create();
        $this->diagnosis['_id'] = $faker->uuid;
        return $this->diagnosis;
    }

    public function getEditDiagnosisData()
    {
        $editDiagnosis = $this->diagnosis;
        $editDiagnosis['name'] = 'edited name';
        return $editDiagnosis;
    }

    public function getInvalidDiagnosisData()
    {
        $faker = Factory::create();
        $editDiagnosis = $this->diagnosis;
        $editDiagnosis['_id'] = $faker->uuid;
        $editDiagnosis['photo_url'] = 'https://example.com/bucket/category/image.jpg';
        return $editDiagnosis;
    }

    public function getValidData()
    {
        return $this->validData;
    }
}
