<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'diagnosis';

    protected $fillable = [
        'cause',
        'name',
        'control',
        'photo_url',
        'explanation',
        'crop',
        'category',
        '_id'
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    protected $attributes = [
        'type' => 'diagnosis',
    ];
}
