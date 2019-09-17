<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'activity_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'activity',
        'email',
        'target_email',
        'target_account_name',
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
        'type' => 'activity_log',
    ];
}
