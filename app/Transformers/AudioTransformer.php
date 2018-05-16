<?php

namespace App\Transformers;

class AudioTransformer extends BaseTransformer
{
	/**
	 * A Fractal transformer.
	 *
	 * @return array
	 */
	public function transform($audio)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($audio);
			case "2":
			case "3": return $this->transformForV2($audio);
			case "4":
			default: return $this->transformForV4($audio);
		}
	}

	public function transformForV2($audio) {
		switch($this->route) {

			/**
			 * @OAS\Response(
			 *   response="v2_audio_timestamps",
			 *   description="The v2_audio_timestamps response",
			 *   @OAS\MediaType(
			 *     mediaType="application/json",
			 *     @OAS\Schema(
			 *              @OAS\Property(property="verse_id",             ref="#/components/schemas/BibleFile/properties/verse_start"),
			 *              @OAS\Property(property="verse_start",          @OAS\Schema(type="string",example="1",description="The duration of the timestamp in seconds"))
			 *     )
			 *   )
			 * )
			 */
			case "v2_audio_timestamps": {
				return [
					"verse_id"    => (string) $audio->verse_start,
					"verse_start" => $audio->timestamp
				];
			}

			/**
			 * @OAS\Response(
			 *   response="v2_audio_path",
			 *   description="The audio_path",
			 *   @OAS\MediaType(
			 *     mediaType="application/json",
			 *     @OAS\Schema(
			 *              @OAS\Property(property="book_id",       ref="#/components/schemas/Book/properties/id_osis"),
			 *              @OAS\Property(property="chapter_id",    ref="#/components/schemas/BibleFile/properties/chapter_start"),
			 *              @OAS\Property(property="path",          @OAS\Schema(type="string"))
			 *     )
			 *   )
			 * )
			 */
			case "v2_audio_path": {
				return [
					"book_id"    => ucfirst(strtolower($audio->book->id_osis)),
					"chapter_id" => (string) $audio->chapter_start,
					"path"       => preg_replace("/https:\/\/.*?\/.*?\//", "", $audio->file_name)
				];
			}

		}
	}

	public function transformForV4($audio) {
		/**
		 * @OAS\Response(
		 *   response="v4_timestamps_tag",
		 *   description="The v4 timestamps tag",
		 *   @OAS\MediaType(
		 *     mediaType="application/json",
		 *     @OAS\Schema(
		 *              @OAS\Property(property="book_id",       ref="#/components/schemas/Book/properties/id"),
		 *              @OAS\Property(property="book_name",     ref="#/components/schemas/Book/properties/name"),
		 *              @OAS\Property(property="chapter_start", ref="#/components/schemas/BibleFile/properties/chapter_start"),
		 *              @OAS\Property(property="chapter_end",   ref="#/components/schemas/BibleFile/properties/chapter_end"),
		 *              @OAS\Property(property="verse_start",   ref="#/components/schemas/BibleFile/properties/verse_start"),
		 *              @OAS\Property(property="verse_end",     ref="#/components/schemas/BibleFile/properties/verse_end"),
		 *              @OAS\Property(property="timestamp",     ref="#/components/schemas/BibleFileTimestamp/properties/timestamp"),
		 *              @OAS\Property(property="path",          ref="#/components/schemas/BibleFile/properties/file_name")
		 *     )
		 *   )
		 * )
		 */
		return [
			"book_id"       => $audio->book_id,
			"book_name"     => $audio->book->currentTranslation->name ?? $audio->book->name,
			"chapter_start" => $audio->chapter_start,
			"chapter_end"   => $audio->chapter_end,
			"verse_start"   => $audio->verse_start,
			"verse_end"     => $audio->verse_end,
			"timestamp"     => $audio->timestamp,
			"path"          => $audio->file_name
		];
	}

}
