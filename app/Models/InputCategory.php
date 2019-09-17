<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputCategory extends Model
{
    protected $fillable = [];
    protected $table = 'input_category';

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    protected $attributes = [
        'type' => 'input_category'
    ];
    // Relationships
}
