<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use App\Utils\Helpers;

class OffTaker extends Model
{
    use Authenticatable, Authorizable;
    /**
     * The document type
     * @var string
     */
    protected $table = 'offtaker';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_name',
        'username',
        'organization',
        'phone_number',
        'district',
        'email',
        'status',
        'contact_person',
        'value_chain',
        'account_type',
        'status',
        'type',
        '_id',
        'password'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'id',
    ];
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'offtaker',
        'status' => 'demo',
    ];
    /**
     * A flag that confirms that the model's
     * attributes have been converted to
     * uppercase first
     *
     * @var boolean
     */
    private $converted = false;
    /**
     * The attributes to convert to upppercase first.
     *
     * @var array
     */
    private $toUpperCaseFirst = [
        'account_name',
        'username',
        'organization',
        'district',
        'contact_person'
    ];
    /**
     *  Used to set the model's attributes
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function __set($key, $value)
    {
        parent::__set($key, $value);
        
        if (!$this->converted) {
            $this->converted = Helpers::mutateAttributes($this, $this->toUpperCaseFirst);
        }
    }
    /**
     * Hash password
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
