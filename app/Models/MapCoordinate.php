<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapCoordinate extends Model
{
    protected $fillable = [];
    protected $table = 'map_cordinates';
    protected $dates = [];
    public static $rules = [];
}
