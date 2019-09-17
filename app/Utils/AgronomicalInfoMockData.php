<?php

namespace App\Utils;

class AgronomicalInfoMockData extends MockData
{
    protected $agronomicalInfo = [
        "description"=>"Identify places with fertile soils, deep soil support proper root development.",
        "title"=>"Site selections",
        "purpose"=>"Land Preparation"
    ];
    protected $wrongPurposeAgronomicalinfo = [
        "purpose"=>"Land Preparationsss",
        "description"=>"Identify places with fertile soils, deep soil support proper root development.",
        "photo"=>"http/images/86aa2e67-ed0b-47d2-abad-8856dc8bc195.png",
        "title"=>"Site selections",
        ];
    /**
     * Return Array of correct agronomical Information.
     *
     * @return array
     */
    public function getAgronomicalInfoData()
    {
        return $this->agronomicalInfo;
    }
    /**
     * Return Array of wrong agronomical Information.
     *
     * @return array
     */
    public function getWrongAgronomicalInfoData()
    {
        return $this->wrongPurposeAgronomicalinfo;
    }
}
