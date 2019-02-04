# Getting started as a consumer

## First steps

* Request an API key here: https://dbp4.org/dashboard/keys/create
* Make your first call: a GET call to https://api.dbp4.org/api/bibles?v=4. Here's a sample you can run from your command line, if you'd like to try it out:

```bash
curl -i -H "Authorization: Bearer YOURKEYHERE" -H "Accept: application/json" -H "Content-Type: application/json" -X GET https://api.dbp4.org/bibles/?v=4
```

## Common workflow: An app that only displays text Bibles

Let's imagine we're building an app that starts the end user off looking at a list of Bibles and lets them choose a Bible, then lists the books and chapters and allows them to select a book or a chapter. Then it will display all of the available content (in this case, just text) for that Bible.

> NOTE: All example URLs in these common workflows will assume you're passing an authorization header. As you can see in the `curl` example above, you'll pass a header with the name of `Authorization` and the value of `Bearer YOURKEYHERE`, where `YOURKEYHERE` is your API key.

### Step 1: List all Bibles that have a text fileset

First, make a query to the "List all Bibles that have a text fileset" route:

`/bibles?media=text_plain&v=4`

> What is a Fileset? [Fileset definition](#markdown-definition--fileset)

Here's what your response might look like:

```json
{
    "data": [
        {
            "abbr": "ENGESV",
            "name": "English Standard Version",
            "vname": "English Standard Version",
            "language": "English",
            "autonym": "English",
            "language_id": 6414,
            "iso": "eng",
            "date": "2001",
            "filesets": {
                "dbp-prod": [
                    {
                        "id": "ENGESV",
                        "type": "text_plain",
                        "size": "C"
                    },
                    {
                        "id": "ENGESVN1DA",
                        "type": "audio",
                        "size": "NT"
                    },
                    {
                        "id": "ENGESVN2DA",
                        "type": "audio_drama",
                        "size": "NT"
                    },
                    {
                        "id": "ENGESVO1DA",
                        "type": "audio",
                        "size": "OT"
                    },
                    {
                        "id": "ENGESVO2DA",
                        "type": "audio_drama",
                        "size": "OT"
                    }
                ]
            }
        },
        'Other Bibles here...',
    ]
}
```

You can now iterate over all of the Bibles present in the `data` array and present each to the user.

> Note: What's `dbp-prod`? By default, every call you make will present the "filesets" that are provided by DBP, which will all come nested under the `dbp-prod` key. Later you'll learn about requesting content from custom asset providers, but for now, just assume you can always look for your filesets beneath the `dbp-prod` key.

Now, your frontend template code might look a bit like this:

```handlebars
{{#each bibles}}
    <a href="/bibles/{{abbr}}">{{name}}</a>
{{/each}}
```

Great! Now let's assume the user picks our first Bible, the `ENGESV` Bible from the example above. That means our link would've taken us to this URL on our app:

`/bibles/ENGESV`

On to our next step.

### Step 2: Get that Bible's `text_plain` fileset ID for future lookups

Since this app we're building is text-only, we'll want to get the `text_plain` fileset ID for that Bible. You can either have cached the list of available filesets for that Bible from the previous lookup, or you can look up just that Bible fresh on this new page, using the following API query:

```
/bibles/{BIBLE_ABBR}/?v=4
```

That will return the full Bible, just as as it was originally returned above, with additional metadata that you can ignore for now.

Now, we want to figure out which fileset on that Bible is our `text_plain` fileset. When we get a Bible as a response from the API, it has *all* of its filesets embedded, not just those for the media type we're working with. So, we need to find just the right fileset that serves this Bible in our desired media type: `text_plain`. Here's an example in JavaScript:

```javascript
// This Bible would be whichever Bible in the result list you're linking to at the moment;
// `this_bible` could also just be pulled from a /bibles/{BIBLE_ABBR} call
let this_bible = results.data[0];

let filesets = this_bible.filesets['dbp-prod'];

let text_plain_fileset = filesets.find(function (fileset) {
  return fileset.type == 'text_plain';
});

let text_plain_fileset_id = text_plain_fileset.id;
```

### Step 3: Build a chapter navigation menu

Now that we have the fileset ID, we can look up the list of books available for this fileset. Make a call to this endpoint, using the `text_plain_fileset_id` we parsed in the previous step:

```
/bibles/filesets/{fileset_id}/books?v=4&fileset_type=text_plain
```

> NOTE: The fileset_id parameter is *not* always unique between filesets, which is why we're also passing the `fileset_type` parameter.

Here's a truncated example of what you might be back:

```json
{
    "data": [
        {
            "book_id": "GEN",
            "book_id_usfx": "GN",
            "book_id_osis": "Gen",
            "name": "Genesis",
            "testament": "OT",
            "testament_order": 1,
            "book_order": 1,
            "book_group": "The Law",
            "chapters": [
                1,
                'More chapters here...',
                50
            ]
        },
        {
            "book_id": "EXO",
            "book_id_usfx": "EX",
            "book_id_osis": "Exod",
            "name": "Exodus",
            "testament": "OT",
            "testament_order": 2,
            "book_order": 2,
            "book_group": "The Law",
            "chapters": [
                1,
                'More chapters here...',
                40
            ]
        },
        'More books here...',
    ]
}
```

As you can see, we're getting quite a bit of metadata on each book, but what we need most in order to build a book and chapter navigation are the book IDs and the chapter list.

So, if we were to build a Handlebars template with this data for book and chapter navigation, it might look like this (assuming "books" was the "data" array from the result above):

```handlebars
{{#each books}}
    <h3><a href="/bibles/{{abbr}}/{{book_id}}">{{name}}</a></h3>

    <ul>
    {{#each chapters}}
        <li><a href="/bibles/{{abbr}}/{{book_id}}/{{this}}">{{this}}</a></li>
    {{/each}}
    </ul>
{{/each}}
```

This would output something like this (each URL meant to represent a possible URL structure in our app, not API paths): 

```html
<h3><a href="/bibles/ENGESV/GEN">Genesis</a></h3>

<ul>
    <li><a href="/bibles/ENGESV/GEN/1">1</a></li>
    <li><a href="/bibles/ENGESV/GEN/2">2</a></li>
    <!-- ... etc. -->
</ul>
```

Now, once a user clicks on either a chapter or a verse, let's build out how to pull and display the text for that chapter.

### Step 4: Display Bible text

There are different endpoints for getting the contents of a chapter, depending on the media type. We'll hit the "text" endpoint and build the URL for this Bible, book, and chapter:

```
/bibles/filesets/{fileset_id}/{book_id}/{chapter_number}/?v=4
```

Here's what the response from that will look like:

```json
{
    "data": [
        {
            "book_id": "GEN",
            "book_name": "Genesis",
            "book_name_alt": "Genesis",
            "chapter": 1,
            "chapter_alt": "1",
            "verse_start": 1,
            "verse_start_alt": "1",
            "verse_end": 1,
            "verse_end_alt": "1",
            "verse_text": "In the beginning, God created the heavens and the earth."
        },
        {
            "book_id": "GEN",
            "book_name": "Genesis",
            "book_name_alt": "Genesis",
            "chapter": 1,
            "chapter_alt": "1",
            "verse_start": 2,
            "verse_start_alt": "2",
            "verse_end": 2,
            "verse_end_alt": "2",
            "verse_text": "The earth was without form and void, and darkness was over the face of the deep. And the Spirit of God was hovering over the face of the waters."
        },
        'More verses here...',
    ]
}
```

So, if you were to ignore version numbers, you'd simply concatenate the `verse_text` property of each item in the array, add a space character btween each, and display it to the user.

```javascript
let verses = response.data;
let verse_texts = verses.map(verse_object => {
    return verse_object.verse_text;
});
let chapter_string = verse_texts.join(' ');
```

That's it! Your app lets your users pick Bibles, books, and chapters, and read the text directly.

Let's take a look at a few other important parts of connecting to the API.

## Authenticating

There are two ways to authenticate: through the `Authorization` header as used above, or, with our less-preferred option, through the `key` query parameter (e.g. `https://api.dbp4.org/bibles/?v=4&key=YOURKEYHERE`).

## The structure of a response

Every success response will have a top-level key named `data`, which is an array (with multiple results) or an object (with a single result). All information, including metadata, lives under the `data` key.

Every error response will have two important keys: `status_code` and `errors`. `status_code` will be populated with the HTTP status code (e.g. `404` for "Not Found"), and the `errors` key will be an array filled with one or more string messages (e.g. `You need to provide a valid API key. To request an api key please email access@dbp4.org.`).

## Rate limiting

Every API key will be rate limited to 500 requests per minute across the entire API.

## Terms defined

- <a id="markdown-definition--bible"></a>*Bible*: A translation of the Bible (e.g. "The English ESV Bible")
- <a id="markdown-definition--fileset"></a>*Fileset*: A collection of text, audio, or video for a given Bible. Each fileset only covers one Bible (e.g. `ENGESV`), in one media format (e.g. `text_plain`), so could be considered as, for example, "The text version of the English ESV Bible".
- <a id="markdown-definition--book-group"></a>*Book Group*: One of the following: "Apocalypse ", "Apostolic History ", "General Epistles", " Gospels", " Historical Books", " Major Prophets", " Minor Prophets ", "Pauline Epistles ", "The Law", " Wisdom Books"

The `_alt` keys (e.g. `book_name_alt`) are those keys in the vernacular of the language requested. For example, "Genesis" in Mandarin would be "创世记"; and `25`, for a chapter number, would be `二十五 `.

## Structure and relationships

- *Bibles* have many filesets
- *Filesets* many books
- *Books* have many chapters
- *Chapters* have many verses
