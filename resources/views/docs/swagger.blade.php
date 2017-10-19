@extends('layouts.app')

@section('head')
    <title>Swagger API</title>
    <link href="/css/swagger-ui.css" rel="stylesheet" />
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body {
            margin:0;
            background: #fafafa;
        }
    </style>
@endsection

@section('content')

    <div id="swagger-ui"></div>

@endsection

@section('footer')

    <script src="/js/swagger-ui-bundle.js"> </script>
    <script src="/js/swagger-ui-standalone-preset.js"> </script>
    <script>
        window.onload = function() {

            // Build a system
            const ui = SwaggerUIBundle({
                url: "/swagger_v2.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            })

            window.ui = ui
        }
    </script>
@endsection