<?php

namespace App\Utils;

class InputData extends MockData
{
    protected $newInput = [
        'name' => 'Beans Beans', 'crops' => 'maize, beans', 'category' => 'Herbicide',
        'description' => 'An Input of quality',
        'price' => '100,200', 'unit' => 'Kg,Ton', 'supplier' => 'east cooperative', 'quantity' => 20
    ];
    protected $invalidInputData = [
        'name' => '0000',
        'category' => 'Pticide',
        'description' => 'An Input of quality',
        'price' => 'No',
        'unit' => 00,
        'supplier' => 'east cooperative',
        'quantity' => 'testquantity'
    ];
    //**get input */
    public function getInputsData()
    {
        return $this->newInput;
    }
    public function getInvalidInputData()
    {
        return $this->invalidInputData;
    }
}
