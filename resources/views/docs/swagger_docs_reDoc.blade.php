<!DOCTYPE html>
<html>
<head>
    <title>ReDoc</title>
    <!-- needed for adaptive design -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">

    <!--
    ReDoc doesn't change outer page styles
    -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div id="redoc-container"></div>

<script src="https://cdn.jsdelivr.net/npm/redoc@next/bundles/redoc.standalone.js"> </script>
<script src="/js/ref-parser.min.js"></script>
<script>
    var request = new XMLHttpRequest();
    request.open('GET', '/eng/swagger_docs', true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            // Success!
            var mySchema = JSON.parse(this.response);
            $RefParser.dereference(mySchema, function(err, schema) {
                if (err) {
                    console.error(err);
                }
                else {
                    // `schema` is just a normal JavaScript object that contains your entire JSON Schema,
                    // including referenced files, combined into a single object

                    Redoc.init(mySchema,['suppressWarnings','noAutoAuth','requiredPropsFirst'],document.getElementById('redoc-container'))
                    //console.log(schema.definitions.person.properties.firstName);
                }
            });

        }
    };
    request.send();
</script>
</body>
</html>