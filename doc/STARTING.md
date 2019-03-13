# Getting started as a consumer
 
> Before you can complete this tutorial, you'll need an API key from dbp4.org
 
Make your first call: a GET request to `https://api.dbp4.org/api/bibles`
Here's a sample you can run from the command line if you'd like to try it out

```bash
curl -i -H "Authorization: Bearer YOURKEY" -H "v: 4" -X GET https://api.dbp.test/bibles/
```

### Common workflow #1: An app that only displays text Bibles

Let's imagine that we're building an app that starts the end user off looking 
at a list of Bibles from which they will choose a Bible, Book, and Chapter to 
read.

> Note: All example URLs in these common workflows will assume you're passing 
an `Authorization` header and the api version header `v: 4`

#### Step 1: List Bibles that have a text fileset

First, make a query to the Bibles route with the added requirement text_plain

`/bibles?media=text_plain`

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
        }
    ]
}
```

> Note: What's `dbp-prod`? The bibles call nests all `filesets` under varient 
`asset_id` values. Each asset id corresponds with a DBP provider AWS instance
hit `/api/buckets` for a complete list of `asset_id` values and organizations


Now iterate over the Bibles in the `data` array and present each to the user.
Your frontend template code might look a bit like like this:

```handlebars
{{#each bibles}}
    <a href="/bibles/{{fileset_id}}">{{name}}</a>
{{/each}}
```

Great! Now let us assume the user picked our first fileset, `ENGESV` from the 
example above; That means our link would have taken us to this URL on our app
Since this app we're building is text only we'll want to get the `text_plain` 
fileset ID for that Bible.

Now, we want to figure out which fileset on that Bible is our `text_plain` fileset.
So, we need to find just the right fileset that serves this Bible in our desired media 
type: `text_plain`. Here's an example in JavaScript

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

#### Step 2: Get navigation for that Bible's text_plain fileset


`/bibles/filesets/{FILESET_ID}/books?type=text_plain&asset_id=dbp-prod`

This route returns a complete list of books for the fileset but for brevity's
sake only the first two are shown.

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
        "chapters": [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50]
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
        "chapters": [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40]
    }
  ]
}

```

### Step 3: Displaying the text

Now we wait for user input, say the user chooses Genesis 4 in your apps menu.
We are now ready to make a request to get the actual scriptural text content.

`/bibles/filesets/ENGESV/MAT/7?type=text_plain&asset_id=dbp-prod`

This route returns a complete list of verses for the fileset but for the sake
of brevity only the first two are shown. Select verse_text and displaying the
values to the user finishes our first workflow.

```json
{
  "data": [
     {
         "book_id": "MAT",
         "book_name": "Matthew",
         "book_name_alt": "Matthew",
         "chapter": 1,
         "chapter_alt": "1",
         "verse_start": 1,
         "verse_start_alt": "1",
         "verse_end": 1,
         "verse_end_alt": "1",
         "verse_text": "The book of the genealogy of Jesus Christ, the son of David, the son of Abraham."
     },
     {
         "book_id": "MAT",
         "book_name": "Matthew",
         "book_name_alt": "Matthew",
         "chapter": 1,
         "chapter_alt": "1",
         "verse_start": 2,
         "verse_start_alt": "2",
         "verse_end": 2,
         "verse_end_alt": "2",
         "verse_text": "Abraham was the father of Isaac, and Isaac the father of Jacob, and Jacob the father of Judah and his brothers,"
     }
  ]
}
```

### Step 4: Adding Audio

You might want to add Audio to your new app. We'll need to refer back to step
1 and get the fileset_id of the audio bible of your choice. Let's say the app
has knows that this user prefers dramatized audio. So the filesets we will be
looking for are `audio` or `audio_drama`. `ENGESVC2DA16` is the fileset we'll
select for so to ensure that the same book and chapter combination is readily 
available for the dramatized audio bible. We'll hit the books route again.

`bibles/filesets/ENGESVN2DA/books?fileset_type=audio_drama&asset_id=dbp-prod`

```json
[
  {
    "book_id": "MAT",
    "book_id_usfx": "MT",
    "book_id_osis": "Matt",
    "name": "Matthew",
    "testament": "NT",
    "testament_order": 1,
    "book_order": 41,
    "book_group": "Gospels",
    "chapters": [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28]
  }
]
```

We can now fetch files directly, fetch the filesets route with the additional
params `type`, `book_id`, and `chapter_id`

`bibles/filesets/ENGESVN2DA?type=audio_drama&book_id=MAT&chapter_id=1`

```json
{
"data":
    [{
        "book_id":"MAT",
        "book_name":"Matthew",
        "chapter_start":1,
        "chapter_end":null,
        "verse_start":1,
        "verse_end":null,
        "timestamp":null,
        "path":"https:\/\/content.cdn.dbp-prod.dbp4.org\/audio\/ENGESV\/ENGESVN2DA\/B01___01_Matthew_____ENGESVN2DA.mp3?x-amz-transaction=0000001&Expires=1552418215",
        "duration":210
	}]
}
```
