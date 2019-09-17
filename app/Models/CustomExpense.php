<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomExpense extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'custom_expenses';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
