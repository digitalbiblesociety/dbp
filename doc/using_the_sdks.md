# Using the DBP SDKs

## Instructions



## Languages

### Installing the PHP SDK via Composer/Packagist

@todo

To use the PHP SDK, you'll want to use Composer to require it:

```bash
composer require digitalbiblesociety/@todo
```

Now, you can make calls to the API using it. First, authenticate by passing the desired URL and your API key:

```php
DbsSwagger\Configuration::$access_token = 'your API key here';

// For Sandbox ? @todo
$apiClient = new DbsSwagger\ApiClient("https://api-sandbox.dbp4.org/");

// For production
$apiClient = new DbsSwagger\ApiClient("https://api.dbp4.org");
```

Once you have a functioning API client, you can make calls using it:

```php

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
            url: './dbp-swagger.json'
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

@todo a list of the possible "tags" and "operations"


## Un-supported

- @todo: Do we want to list SDKs that DBS can't feel comfortable supporting?
