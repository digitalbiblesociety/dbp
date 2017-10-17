<?php

$swagger = fetchSwaggerSchema($schema);
?>
@if(isset($swagger['schema']))
<div class="field-definitions row">
    <table class="unstriped">
        <thead>
            <tr>
                <td>ID</td>
                <td>Type</td>
                <td>Example</td>
                <td>Description</td>
            </tr>
        </thead>
        <tbody>
        @foreach($swagger['schema']['properties'] as $key => $properties)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $properties['type'] ?? "" }}</td>
                <td>{{ $properties['example'] ?? "" }}</td>
                <td>{{ $properties['description'] ?? "" }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-center">Field Definitions for the <b>{{ $schema }}</b> schema are coming soon!</p>
@endif