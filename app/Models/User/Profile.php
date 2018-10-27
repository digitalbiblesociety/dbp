<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Profile
 *
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Profile model communicates information about a user",
 *     title="Profile",
 *     @OA\Xml(name="Profile")
 * )
 *
 */
class Profile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profiles';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'bio',
		'address_1',
		'address_2',
		'address_3',
		'city',
		'state',
		'zip',
		'country_id',
		'avatar',
		'phone',
	    'birthday',
		'created_at',
		'updated_at',
    ];

	protected $organisation_name;


	/**
	 *
	 * @OA\Property(
	 *   title="Street Address",
	 *   type="string"
	 * )
	 *
	 * @method static Profile whereThoroughfare($value)
	 * @property string $thoroughfare
	 */
	protected $thoroughfare;

	/**
	 *
	 * @OA\Property(
	 *   title="Premise",
	 *   type="string"
	 * )
	 *
	 * @method static Profile wherePremise($value)
	 * @property string $premise
	 */
	protected $premise;

	/**
	 *
	 * @OA\Property(
	 *   title="Sub Premise",
	 *   type="string"
	 * )
	 *
	 * @method static Profile whereSubPremise($value)
	 * @property string $sub_premise
	 */
	protected $sub_premise;

	/**
	 * @OA\Property(
	 *   title="The Gender of the User",
	 *   description="This field aligns with the ISO/IEC 5218 Standard. Codes for the representation of human sexes is an international standard that defines a representation of human sexes through a language-neutral single-digit code.",
	 *   format="int64",
	 *   type="integer",
	 *   minimum=0,
	 *   enum={0,1,2,9},
	 *   @OA\ExternalDocumentation(
	 *     description="Read More about the standard",
	 *     url="http://standards.iso.org/ittf/PubliclyAvailableStandards/c036266_ISO_IEC_5218_2004(E_F).zip"
	 *   )
	 * )
	 *
	 * @method static Profile whereSex($value)
	 * @property integer $sex
	 */
	protected $sex;

	/**
	 *
	 * @OA\Property(
	 *   title="Sub Administrative Area",
	 *   description="County or District",
	 *   type="string"
	 * )
	 *
	 * @method static Profile whereSubAdministrativeArea($value)
	 * @property string $sub_administrative_area
	 */
	protected $sub_administrative_area;

	/**
	 *
	 * @OA\Property(
	 *   title="Administrative Area",
	 *   description="State / Province / Region (ISO code when available)",
	 *   type="string"
	 * )
	 *
	 * @method static Profile whereAdministrativeArea($value)
	 * @property string $administrative_area
	 */
	protected $administrative_area;

	/**
	 *
	 * @OA\Property(
	 *   title="Zip",
	 *   description="Postal code / ZIP Code",
	 *   type="string"
	 * )
	 *
	 * @method static Profile whereZip($value)
	 * @property string $zip
	 */
	protected $zip;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static Profile whereZip($value)
	 * @property string $zip
	 */
	protected $country_id;

    public $timestamps = ['created_at','updated_at','birthday'];


    /**
     * A profile belongs to a user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
