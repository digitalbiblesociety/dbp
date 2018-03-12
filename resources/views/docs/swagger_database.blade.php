@extends('layouts.app')

@section('head')
    <title>Swagger v2 API</title>
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
                url: "/swagger_database.json",
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