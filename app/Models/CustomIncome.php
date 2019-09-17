<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomIncome extends Model
{

    /**
     * The document type
     * @var string
     */
    protected $table = 'custom_income';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
