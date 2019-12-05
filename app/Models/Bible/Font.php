<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema (
 *     type="object",
 *     description="Font",
 *     title="Font",
 *     @OA\Xml(name="Font")
 * )
 *
 */
class Font extends Model
{
    protected $connection = 'dbp';
    protected $hidden = ['id', 'created_at', 'updated_at'];
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="name",
     *   type="string",
     *   description="The font name"
     * )
     *
     */
    protected $name;

    /**
     *
     * @OA\Property(
     *   title="data",
     *   type="string",
     *   description="The base64 data raw"
     * )
     *
     */
    protected $data;

    /**
     *
     * @OA\Property(
     *   title="type",
     *   type="string",
     *   description="The type of font",
     *   enum={"ttf","otf"}
     * )
     *
     */
    protected $type;

    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp at which the font was originally created"
     * )
     *
     */
    protected $created_at;
    /**
     *
     * @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp at which the font was last updated"
     * )
     *
     */
    protected $updated_at;

    public $incrementing = false;
}
