<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputOrder extends Model
{
    protected $fillable = [];
    protected $table = 'order';
    protected $dates = [];
    public static $rules = [];
}
