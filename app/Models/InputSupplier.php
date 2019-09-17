<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputSupplier extends Model
{
    protected $fillable = ['name', 'crops', 'category', 'description', 'photo_url',
    'price', 'unit', 'supplier', 'quantity', '_id'];
    protected $table = 'input';

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    protected $attributes = [
        'type' => 'input'
    ];

    // Relationships
}
