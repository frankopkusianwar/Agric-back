<?php

namespace App\Utils;

use Crisu83\ShortId\ShortId;

class UserMockData
{
    public $adminData = ['email' => 'someone@gmail.com', 'password' => '123456'];
    public $masterAgentData = ['email' => 'masteragent2121@gmail.com', 'password' => '123123'];
    public $newAdmin = [
    'password' => 'admin12345',
    'confirmPassword' => 'admin12345',
    'adminRole' => 'Analyst',
    'firstname' => 'maxxy',
    'lastname' => 'max',
];

    public $wrongAdminRole = [
  'password' => 'admin12345',
  'confirmPassword' => 'admin12345',
  'adminRole' => 'Manager',
  'firstname' => 'maxxy',
  'lastname' => 'max',
];

    public $newOffTaker = [
  'password' => 'masterAgent12345',
  'account_type' => 'Custom',
  'value_chain' => 'Crop',
  'account_name' => 'masteragent',
  'contact_person' => 'Samuel',
  'phone_number' => '23456789765',
  'district' => 'Kitgum',
  'address' => 'somewhere',
  'username' => 'offtaker',
];

    public $newMasterAgent = [
  'account_type' => 'Custom',
  'password' => 'masterAgent12345',
  'value_chain' => 'Crop',
  'account_name' => 'masteragent',
  'contact_person' => 'Samuel',
  'phone_number' => '234567897654',
  'district' => 'Kitgum',
  'address' => 'somewhere',
];
    public $existingMasterAgent = [
  'password' => 'masterAgent12345',
  'account_type' => 'Custom',
  'value_chain' => 'Crop',
  'account_name' => 'masteragent',
  'username' => 'masteragent',
  'contact_person' => 'Samuel',
  'phone_number' => '234567897654',
  'district' => 'Kitgum',
  'address' => 'somewhere',
];

    public $newDevtPartner = [
  'password' => 'masterAgent12345',
  'account_type' => 'Custom',
  'value_chain' => 'Crop',
  'account_name' => 'masteragent',
  'contact_person' => 'Samuel',
  'phone_number' => '234567897654',
  'district' => 'Kitgum',
  'address' => 'somewhere',
];

    public $accountRequest = [
  'phone_number' => '32489765478',
  'account_name' => 'fghjklkjhgf',
  'address' => 'somewhere',
];

    public $invalidData = [
  'email' => 'admin2020@gmail.com',
  'password' => 'masterAgent12345',
  'account_type' => 'someaccount',
  'value_chain' => 'somevalue',
  'account_name' => 'masteragent',
  'username' => 'masteragent',
  'contact_person' => 'Samuel',
  'phone_number' => '234567897654',
  'district' => 'somewhere',
  'address' => 'somewhere',
];

    public $validVaData = [
  "villageAgents" => [
      [
          'agriculture_experience_in_years' => 'NA', 'assets_held' => 'NA',
          'certification_doc_url' => 'NA', 'education_doc_url' => 'NA',
          'education_level' => 'NA', 'eloquent_type' => 'va', 'farmers_enterprises' => 'NA',
          'ma_id' => 'NA',
          'other_occupation' => 'NA',
          'partner_id' => 'NA',
          'password' => '$2y$10$0hRHy0Ktg8QW3cAfDqgdvuP4YfwjYMBzunlY5LcrxrdsORahMAu7u',
          'position held_in_community' => 'NA',
          'service_provision_experience_in_years' => 'NA',
          'services_va_provides' => 'NA',
          'status' => 'active',
          'time' => '2018-07-05T21:48:13:141586',
          'total_farmers_acreage' => 'NA',
          'total_number_of_farmers' => 'NA',
          'type' => 'va',
          'va_country' => 'Uganda',
          'va_district' => 'Bushenyi',
          'va_dob' => 'NA',
          'va_gender' => 'female',
          'va_home_gps_Accuracy' => 'NA',
          'va_home_gps_Altitude' => 'NA',
          'va_home_gps_Latitude' => 'NA',
          'va_home_gps_Longitude' => 'NA',
          'va_id_number' => 'NA',
          'va_id_type' => 'NA',
          'va_name' => 'Prof. Colton Stoltenberg Jr.',
          'va_parish' => 'Nyakariro',
          'va_phonenumber' => '',
          'va_photo' => 'https =>\/\/drive.google.com\/open?id=1MwZuPcWTOcJYa6536Buk9FEc5i7HrZ3U',
          'va_region' => 'Western',
          'va_subcounty' => 'Bwambara',
          'va_village' => 'Kashayo',
      ],
  ],
];

    public $newGovernmentPartner = [
  'password' => 'govAgent12345',
  'account_type' => 'Custom',
  'value_chain' => 'Crop',
  'account_name' => 'govagent',
  'contact_person' => 'Samuel',
  'phone_number' => '234567897654',
  'district' => 'Kitgum',
  'address' => 'somewhere',
];

    public $shortid;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->shortid = ShortId::create();
    }
    public function getAdminData()
    {
        return $this->adminData;
    }
   
    public function getMasterAgentData()
    {
        return $this->masterAgentData;
    }
   
    public function getNewAdmin()
    {
        $this->newAdmin['email'] = $this->shortid->generate() . '@gmail.com';
        $this->newAdmin['_id'] = $this->shortid->generate();
        return $this->newAdmin;
    }
    public function getWrongAdminRole()
    {
        $this->wrongAdminRole['email'] = $this->shortid->generate() . '@gmail.com';
        $this->wrongAdminRole['_id'] = $this->shortid->generate();
        return $this->wrongAdminRole;
    }
    public function getNewOffTaker()
    {
        $this->newOffTaker['email'] = $this->shortid->generate() . '@gmail.com';
        $this->newOffTaker['username'] = $this->shortid->generate();
        return $this->newOffTaker;
    }
    public function getNewMasterAgent()
    {
        $this->newMasterAgent['email'] = $this->shortid->generate() . '@gmail.com';
        $this->newMasterAgent['username'] = $this->shortid->generate();
        return $this->newMasterAgent;
    }
    public function getExistingMasterAgent()
    {
        $this->existingMasterAgent['email'] = 'admin1234';
        return $this->existingMasterAgent;
    }
    
    public function getNewDevtPartner()
    {
        $this->newDevtPartner['email'] = $this->shortid->generate() . '@gmail.com';
        $this->newDevtPartner['username'] = $this->shortid->generate();
        return $this->newDevtPartner;
    }
    public function getAccountRequest()
    {
        $this->accountRequest['email'] = $this->shortid->generate() . '@gmail.com';
        $this->accountRequest['username'] = $this->shortid->generate();
        return $this->accountRequest;
    }
    public function getInvalidData()
    {
        return $this->invalidData;
    }
    
    
    public function getValidVaData()
    {
        $this->validVaData['villageAgents'][0]['ma_id'] = $this->shortid->generate();
        $this->validVaData['villageAgents'][0]['va_phonenumber'] = mt_rand(1000000000, 9999999999);
        return $this->validVaData;
    }
    public function getDuplicateVaPhoneNo()
    {
        $this->validVaData['villageAgents'][0]['ma_id'] = $this->shortid->generate();
        $this->validVaData['villageAgents'][0]['va_phonenumber'] = '1111111111';
        return [
            "villageAgents" => [
                $this->validVaData['villageAgents'][0], $this->validVaData['villageAgents'][0],
            ],
        ];
    }

    public function getInvalidVaData()
    {
        return $this->validVaData;
    }

    public function getNewGovernmentPartner()
    {
        $this->newGovernmentPartner['email'] = $this->shortid->generate() . '@gmail.com';
        $this->newGovernmentPartner['username'] = $this->shortid->generate();
        return $this->newGovernmentPartner;
    }
}
