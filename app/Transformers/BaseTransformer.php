<?php
/**
 * Created by PhpStorm.
 * User: jon
 * Date: 7/26/17
 * Time: 12:13 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract {

	public function __construct()
	{
		$this->version = $_GET['v'] ?? 4;
		$this->iso = $_GET['iso'] ?? "eng";
		$this->continent = $_GET['continent'] ?? false;
	}

}