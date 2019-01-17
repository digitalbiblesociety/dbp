# Getting started as a consumer

## Steps

* Request an API key
* Make your first call: a GET call to https://api.dbp4.org/api/bibles?v=4. Here's a sample you can run from your command line, if you'd like to try it out:

```bash
curl -i -H "Authorization: Bearer YOURKEYHERE" -H "Accept: application/json" -H "Content-Type: application/json" -X GET https://api.dbp4.org/bibles/?v=4
```

## Common workflow #1: An app that only displays text Bibles

Let's imagine that we're building an app that starts the end user off looking at a list of Bibles and lets them choose a Bible, then takes them to the first available chapter for that Bible (often, but not always, Genesis 1). Then it will display all of the available content (text, audio, or video) for that Bible.

> Note: All example URLs in these common workflows will assume you're passing an authorization header.

### Step 1: List Bibles that have a text fileset

First, make a query to the "List all Bibles that have a text fileset" route:

`/bibles?media=text_plain&v=4`

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
        /* Other Bibles here... */
    ]
}
```

You can now iterate over all of the Bibles present in the `data` array and present each to the user.

> Note: What's `dbp-prod`? By default, every call you make will present the "filesets" that are provided by DBP, which will all come nested under the `dbp-prod` key. Later you'll learn about requesting content from custom asset providers, but for now, just assume you can always look for your filesets beneath the `dbp-prod` key.

> What is a Fileset? [Fileset definition](#markdown-definition--fileset)

Now, your frontend template code might look a bit like this:

```handlebars
{{#each bibles}}
    <a href="/bibles/{{abbr}}">{{name}}</a>
{{/each}}
```

Great! Now let's assume the user picks our first Bible, the `ENGESV` from the example above;. That means our link would've taken us to this URL on our app:

`/bibles/ENGESV`

On to our next step.

### Step 2: Get first chapter for that Bible's text_plain fileset

Since this app we're building is text-only, we'll want to get the `text_plain` fileset ID for that Bible. You can either have cached the list of available filesets for that Bible from the previous lookup, or you can look up just that Bible fresh on this new page:

```
/bibles/{BIBLE_ABBR}
```

That will return the full Bible, just as as it was originally returned above, with additional metadata that you can ignore for right now.

Now, we want to figure out which fileset on that Bible is our `text_plain` fileset. When we get a Bible as a response from the API, it has *all* of its filesets embedded, not just those for the media type we're working with. So, we need to find just the right fileset that serves this Bible in our desired media type: `text_plain`. Here's an example in JavaScript

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

@todo

### Step 3: Build a chapter navigation menu

/bibles/{BIBLE_ID}/

Show them the shape of this response so they can imagine how to work with it

## Common workflow #2: An app that displays all available content types (text, audio, video)

### Step 1: List Bibles

/bibles

Hit this route:
Data shaped like this:
User chooses this one:
Get the default fileset for each available type:
    Use an example bible that has all three types

### Step 2: For each of those default filesets, get the chapter

/bibles/filesets/?fileset_id={FILESET_ID}&fileset_type={FILESET_TYPE}
/bibles/filesets/?fileset_id={FILESET_ID}&fileset_type={FILESET_TYPE}
/bibles/filesets/?fileset_id={FILESET_ID}&fileset_type={FILESET_TYPE}

### Step 3: Build a chapter navigation menu

/bibles/{BIBLE_ID}/

Show them the shape of this response so they can imagine how to work with it

## Authenticating

There are two ways to authenticate: through the `Authorization` header as used above, or, with our less-preferred option, through the `key` query parameter (e.g. `https://api.dbp4.org/bibles/?v=4&key=YOURKEYHERE`).

## The structure of a response

## Errors

## Rate limiting

## Common routes

## Working with text vs. audio vs. video filesets

- Note that text is cacheable and all in the fileset response
- Note that audio is s3 links that may expire
- Note that video is HLS streaming
- Note other useful notes n stuff

## Terms defined

- Bible
- <a id="markdown-definition--fileset"></a>*Fileset*: Collection of text, audio, or video for a given Bible. Each fileset only covers one Bible (e.g. `ENGESV`), in one media format (e.g. `text_plain`), 
- ?

## Structure and relationships

- Bibles
- Filesets

## SDKs


