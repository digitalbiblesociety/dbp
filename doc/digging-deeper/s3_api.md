# The Layout of Objects in S3

```scss
/
  - bibles.json
  - bibles/{ #id }/info.json
  - bibles/{ #id }/{ #version_id }/{ #provider_id }/{ #file_type }/{ #filename }.html
  - bibles/{ #id }/{ #version_id }/{ #provider_id }/{ #file_type }/{ #metaData }/{ #filename }.json
  - languages.json
  - languages/{ #iso }.json
  - fonts/{ #script_id }.json
````

### Variable Definitions

#### ID:

**Description:** The Bible ID, a human readable string between 6-12 alpha characters. Starting with the iso code and ending with a version code

**Example:** *ENGNIV*

#### version_id

**Description:** The version ID for each bible ID. A human readable string between 4 and 16 alpha-numeric characters.

**Example:** *NIV1984*

#### provider_id

**Description:** The organization ID for each version ID. A human readable string under 64 alpha-numeric characters.

**Example:** *Crossways*

#### file_type

**Description:** The type of Bible media being delivered.

**Note:**
There are multiple possible duplicate content collections under file_type
for instance you might have might have several different audio Bibles for
a single version id. The *file_type* field should the guide here. It is a
convention not strictly limited to file names. It may contain metadata or
tags.

Tags should be after a dash character multiple modifiers are separated by
underscores.

So if you have a high quality Dramatized Audio Bible...
```
bibles/ENGKJV/ENGKJV1611/FCBH/mp3-hq_drama/GEN_1.mp3
```

#### metaData

**Description:** Additional data files to accompany the parent entity

**Note:** These could take the form of timestamps for the audio files and
they could also be dictionary, glossaries, or maps. All variants ought to
be listed in the central index.json

```
bibles/ENGKJV/ENGKJV1611/FCBH/mp3-hq_drama/timestamps/GEN_1.json
bibles/ENGKJV/ENGKJV1611/FCBH/mp3-hq_drama/commentaries/GEN_1.json
bibles/ENGKJV/ENGKJV1611/FCBH/mp3-hq_drama/meta/glossary.json
bibles/ENGKJV/ENGKJV1611/FCBH/mp3-hq_drama/meta/dictionary.json
```

#### filename

**Description:** The filename of the book, chapter, or selection.

**Example 1:** *ENGNIV_40_MAT_001.mp3*

**Example 2:** *ENGNIV_40_MAT.pdf*

#### iso

**Description:** The Iso 639-3 codes curated by the ethnologue and SIL

**Example:** eng

#### script_id

**Description:** The ISO 15924 codes curated by the script source project


## Section Examples:

### Two Possible Variations for Chapter Jsons

```json

[
	"2": {
		"0" {
			"preface": "A Word about the Contemporary English Version",
			"preface_type": "is1",
			"text": "Translation it is that opens the window, to let in the light; that breaks the shell, that we may eat the kernel; that puts aside the curtain, that we may look into the most holy place; that removes the cover of the well, that we may come by the water.",
			"text_type": ["imi","em"],
			"text_after": "(“The Translators to the Reader,” King James Version, 1611).",
		}
		"1": {
			"text": "In the beginning, God created the heavens and the earth.",
			"text_type": null,
			"preface": "The Creation of the World",
			"preface_type": "is",
			"after": null,
			"after_type": null,
			"footnotes": [
				"Beginning, means at the start",
				"Also known as the big man upstairs",
				"This includes New Jersey"
			],
			"crossreferences": {
				"GN_1_2": "This is the next verse",
				"GN_1_3": "We'll get here eventually"
			}
			"new_paragraph": true,
		}
	}
]
```


```json
[
	"1": {
		"1": "In the beginning, God created the heavens and the earth.",
		"2": "The earth was without form and void, and darkness was over the face of the deep. And the Spirit of God was hovering over the face of the waters."
	}
]

```

### Language JSONs


#### index.json
```json
[
	{
      "glotto_id": "stan1293",
      "iso": "eng",
      "name": "English"
	}
]

```

#### { #iso }.json
```json
[
	{
      "glotto_id": "stan1293",
      "iso": "eng",
      "name": "English"
	}
]

```


### Countries JSONs
