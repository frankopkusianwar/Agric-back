<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPassword extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'request-password';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'timestamp',
        'token',
        'type',
        '_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */

    protected $attributes = [
        'type' => 'request-password',
    ];
}
