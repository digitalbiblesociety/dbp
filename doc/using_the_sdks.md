# Using the DBP SDKs

## Instructions



## Languages

### Installing the PHP SDK via Composer/Packagist

@todo

To use the PHP SDK, you'll want to use Composer to require it:

```bash
composer require digitakbiblesociety/@todo
```

Now, you can make calls to the API using it. First, authenticate by passing the desired URL and your API key:

```php
$apiInstance = new DbpPhpSdk\Client\Api\APIApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$v = 56; // int | The Version Number
$key = "key_example"; // string | The Key granted to the api user upon sign up
$pretty = true; // bool | Setting this param to true will add human readable whitespace to the return
$format = "format_example"; // string | Setting this param to true will add format the return as a specific file type. The currently supported return types are `xml`, `csv`, `json`, and `yaml`

try {
    $result = $apiInstance->v4ApiGitVersion($v, $key, $pretty, $format);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling APIApi->v4ApiGitVersion: ', $e->getMessage(), PHP_EOL;
}
```


See the @todo to find documentation of which calls are available to you.

### Installing the TypeScript SDK

DO STUFF

### Requiring the `swagger-js` JavaScript global module 

Follow the instructions on the [Swagger-js GitHub page](https://github.com/swagger-api/swagger-js) to include Swagger in your project, and point it at this spec URL:

```
https://api.dbp4.org/swagger_docs.json
```

For example, if you're pulling the client directly in via the browser (instead of via a locally-built JavaScript file), it might look like this to load and use it:

```html
<html>
<head>
    <script src="https://unpkg.com/swagger-client@3.8.23/browser/index.js" type="text/javascript"></script>
    <script>
        SwaggerClient({
            url: 'https://api.dbp4.org/swagger_docs.json'
        }).then((client) => {
            client
                .apis
                .Bibles // the "Bibles" tag
                .v4_bible_all({ // the "v4_bible_all" operation
                    v: 4,
                    key: 'YOUR API KEY HERE',
                },
                {
                })
                .then(response => {
                    console.log(response);
                    // List of Bibles now available at:
                    // response.body.data
                }, error => {
                    console.log(error);
                });
        });
    </script>
</head>
<body>
</body>
</html>
```

For a list of possible tags and operations, visit the [Swagger documentation](https://api.dbp4.org/docs/swagger/v4). The initial entries you'll see on that page (Bibles, Users, etc.) will each represent a "tag", and the entries underneath each tag (e.g. `get /timestamps`) will each, on their detail page, show the "route name" (the operation name, in our example above).


## Un-supported

- @todo: Do we want to list SDKs that DBS can't feel comfortable supporting?
