<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropInfo extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'cropinf';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'photo_url',
        'title',
        'purpose',
        'crop',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'cropinf',
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
