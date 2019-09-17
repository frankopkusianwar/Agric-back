<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Utils\Helpers;

class Government extends Model
{
    /**
     * The document type
     * @var string
     */
    protected $table = 'government';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_name',
        'username',
        'phone_number',
        'address',
        'value_chain',
        'account_type',
        'email',
        'district',
        'contact_person',
        '_id',
        'type',
        'password',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password',
    ];
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'government',
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
