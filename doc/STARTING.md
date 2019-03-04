# Getting started as a consumer

## Steps

 
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

First, make a query to the "List all Bibles that have a text fileset" route:

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
    <a href="/bibles/{{abbr}}">{{name}}</a>
{{/each}}
```

Great! Now let's assume the user picks our first Bible, the `ENGESV` from the 
example above; That means our link would have taken us to this URL on our app
Since this app we're building is text only we'll want to get the `text_plain` 
fileset ID for that Bible.

#### Step 2: Get navigation for that Bible's text_plain fileset

`/bibles/filesets/{FILESET_ID}?type=text_plain&asset_id=dbp-prod`

Now, we want to figure out which fileset on that Bible is our `text_plain` fileset.
When we get a Bible as a response from the API, it has *all* of its filesets embedded, not just those for the media type we're working with.
So, we need to find just the right fileset that serves this Bible in our desired media type: `text_plain`. Here's an example in JavaScript

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

Now we need to look up the first chapter for this Bible. 