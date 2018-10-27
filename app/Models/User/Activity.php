<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'userType',
        'userId',
        'route',
        'ipAddress',
        'userAgent',
        'locale',
        'referer',
        'methodType',
    ];

    protected $casts = [
        'description'   => 'string',
        'user'          => 'integer',
        'route'         => 'url',
        'ipAddress'     => 'ipAddress',
        'userAgent'     => 'string',
        'locale'        => 'string',
        'referrer'      => 'url',
        'methodType'    => 'string',
    ];

	/**
	 * Create a new instance to set the table and connection.
	 *
	 * @param array $attributes
	 */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
        $this->table = config('LaravelLogger.loggerDatabaseTable');
        $this->connection = config('LaravelLogger.loggerDatabaseConnection');
    }

    /**
     * Get the database connection.
     */
    public function getTableName()
    {
        return $this->table;
    }

	/**
	 * An activity has a user.
	 *
	 * @var array
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
    public function user()
    {
        return $this->hasOne(config('LaravelLogger.defaultUserModel'));
    }

    /**
     * Get a validator for an incoming Request.
     *
     * @param array $merge (rules to optionally merge)
     *
     * @return array
     */
    public static function rules(array $merge)
    {
        return array_merge([
            'description'   => 'required|string',
            'userType'      => 'required|string',
            'userId'        => 'nullable|integer',
            'route'         => 'nullable|url',
            'ipAddress'     => 'nullable|ip',
            'userAgent'     => 'nullable|string',
            'locale'        => 'nullable|string',
            'referrer'      => 'nullable|url',
            'methodType'    => 'nullable|string',
        ],
        $merge);
    }
}
