<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'insurance';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
