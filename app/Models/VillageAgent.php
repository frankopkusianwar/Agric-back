<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageAgent extends Model
{
    protected $table = 'va';

    protected $fillable = [
    'agriculture_experience_in_years',
    'assets_held',
    'certification_doc_url',
    'education_doc_url',
    'education_level',
    'farmers_enterprises',
    'ma_id',
    'other_occupation',
    'partner_id',
    'position held_in_community',
    'service_provision_experience_in_years',
    'services_va_provides',
    'status',
    'time',
    'total_farmers_acreage',
    'total_number_of_farmers',
    'type',
    'vaId',
    'va_country',
    'va_district',
    'va_dob',
    'va_gender',
    'va_home_gps_Accuracy',
    'va_home_gps_Altitude',
    'va_home_gps_Latitude',
    'va_home_gps_Longitude',
    'va_id_number',
    'va_id_type',
    'va_name',
    'va_parish',
    'va_phonenumber',
    'va_photo',
    'va_region',
    'va_subcounty',
    'va_village'
  ];

    protected $dates = [];

    public static $rules = [
    // Validation rules
  ];

    // Relationships
}
