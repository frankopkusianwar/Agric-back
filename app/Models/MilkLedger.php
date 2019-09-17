<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkLedger extends Model
{

    /**
     * The document type
     * @var string
     */
    protected $table = 'milk_ledger';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
