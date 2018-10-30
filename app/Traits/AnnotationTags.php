<?php

namespace App\Traits;

trait AnnotationTags
{
	public function handleTags(Request $request, $annotation)
	{
		if(\is_string($request->tags)) {
			$tags = collect(explode(',', $request->tags))->map(function ($tag) {

				if (strpos($tag, ':::') !== false) {
					$tag = explode(':::', $tag);
					return ['value' => ltrim($tag[1]), 'type' => ltrim($tag[0])];
				}

				return ['value' => ltrim($tag), 'type' => 'general'];
			})->toArray();
		} else {
			$tags = $request->tags;
		}

		if ($request->method() === 'POST') {
			$annotation->tags()->createMany($tags);
		}
		if ($request->method() === 'PUT') {
			$annotation->tags()->delete();
			$annotation->tags()->createMany($tags);
		}
	}

}
