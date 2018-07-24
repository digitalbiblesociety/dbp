<?php
namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\PaginatorInterface;
trait FractalPaginationTrait
{
	public function paginator(PaginatorInterface $adapter): array
	{
		$paginator = static::getPaginatorFromAdapter($adapter);
		$pagination = [
			'total'         => $paginator->total(),
			'per_page'      => $paginator->perPage(),
			'current_page'  => $paginator->currentPage(),
			'last_page'     => $paginator->lastPage(),
			'next_page_url' => $paginator->nextPageUrl(),
			'prev_page_url' => $paginator->previousPageUrl(),
			'from'          => $paginator->firstItem(),
			'to'            => $paginator->lastItem(),
		];
		return compact('pagination');
	}
	public static function getPaginatorFromAdapter(PaginatorInterface $adapter): LengthAwarePaginator
	{
		return $adapter->getPaginator();
	}
}