<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spraying extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'spraying';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
