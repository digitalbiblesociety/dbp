<?php

namespace App\Transformers\Serializers;

use League\Fractal\Serializer\DataArraySerializer as BaseSerializer;
use App\Traits\FractalPaginationTrait;

class DataArraySerializer extends BaseSerializer
{
    use FractalPaginationTrait;
}
