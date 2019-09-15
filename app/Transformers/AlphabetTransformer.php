<?php

namespace App\Transformers;

use App\Models\Language\Alphabet;

class AlphabetTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @param Alphabet $alphabet
     * @return array
     */
    public function transform(Alphabet $alphabet)
    {
        switch ($this->version) {
            case 2:
            case 3:
                return $this->transformForV2($alphabet);
            case 4:
            default:
                return $this->transformForV4($alphabet);
        }
    }

    public function transformForV2(Alphabet $alphabet)
    {
        return [
            'name'      => $alphabet->name,
            'script'    => $alphabet->script,
            'family'    => $alphabet->family,
            'type'      => $alphabet->type,
            'direction' => $alphabet->direction
        ];
    }

    public function transformForV4(Alphabet $alphabet)
    {
        switch ($this->route) {

            /**
             *
             * @OA\Schema (
             *     type="array",
             *     schema="v4_alphabets_all_response",
             *     description="The minimized alphabet return for the all alphabets route",
             *     title="The all alphabets response",
             *     @OA\Xml(name="v4_alphabets_all_response"),
             *     @OA\Items(
             *          @OA\Property(property="name",      ref="#/components/schemas/Alphabet/properties/name"),
             *          @OA\Property(property="script",    ref="#/components/schemas/Alphabet/properties/script"),
             *          @OA\Property(property="family",    ref="#/components/schemas/Alphabet/properties/family"),
             *          @OA\Property(property="type",      ref="#/components/schemas/Alphabet/properties/type"),
             *          @OA\Property(property="direction", ref="#/components/schemas/Alphabet/properties/direction")
             *     )
             * )
             *
             */
            case 'v4_alphabets.all':
                return [
                    'name'      => $alphabet->name,
                    'script'    => $alphabet->script,
                    'family'    => $alphabet->family,
                    'type'      => $alphabet->type,
                    'direction' => $alphabet->direction
                ];

            /**
             *
             * @OA\Schema (
             *     type="object",
             *     schema="v4_alphabets_one_response",
             *     description="The full alphabet return for the single alphabet route",
             *     title="The single alphabet response",
             *     @OA\Xml(name="v4_alphabets_one_response"),
             *     @OA\Property(property="name",                   ref="#/components/schemas/Alphabet/properties/name"),
             *     @OA\Property(property="script",                 ref="#/components/schemas/Alphabet/properties/script"),
             *     @OA\Property(property="family",                 ref="#/components/schemas/Alphabet/properties/family"),
             *     @OA\Property(property="type",                   ref="#/components/schemas/Alphabet/properties/type"),
             *     @OA\Property(property="direction",              ref="#/components/schemas/Alphabet/properties/direction"),
             *     @OA\Property(property="fonts",type="array",     @OA\Items(ref="#/components/schemas/AlphabetFont")),
             *     @OA\Property(property="languages",type="array", @OA\Items(ref="#/components/schemas/Language")),
             *     @OA\Property(property="bibles",type="array",    @OA\Items(ref="#/components/schemas/Bible"))
             * )
             *
             */
            case 'v4_alphabets.one':
                return $alphabet->toArray();
                break;

            case 'v4_numbers.all':
                return $alphabet->toArray();
        }
    }
}
