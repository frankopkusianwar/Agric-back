<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurCrop extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'our_crops';

    protected $fillable = [
            'photo_url',
            'crop',
            '_id'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */

    protected $attributes = [
        'type' => 'our_crops',
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
