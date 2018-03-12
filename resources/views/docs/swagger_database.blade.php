@extends('layouts.app')

@section('head')
    <title>Swagger v4 API</title>
    <style>
        thead th, thead td, tfoot th, tfoot td {padding:0;}
    </style>
@endsection

@section('content')

    <div class="row">
    <table class="table" cellspacing="0" width="100%" >
        <thead>
        <tr>
            <td>Fieldname</td>
            <td>Key</td>
            <td>Type</td>
            <td>Description</td>
            <td>Min Length:</td>
            <td>Max Length:</td>
            <td>Example</td>
            <td>Enum</td>
        </tr>
        </thead>
        <tbody>
            @foreach($docs['components']['schemas'] as $fieldName => $schema)
                @if(isset($id)) @if($fieldName != $id) @continue @endif @endif
                @if(!isset($schema['properties'])) @continue @endisset
                @foreach($schema['properties'] as $key => $property)
                    {{-- handle external references  --}}
                    @isset($property['$ref'])
                        <?php
                            $field = explode('/', $property['$ref']);
                            $fieldName = end($field);
                            $property = $docs['components']['schemas'][$fieldName]
                        ?>
                    @endisset
                        <tr>
                            <td>{{ $fieldName }}</td>
                            <td>{{ $key }}</td>
                            <td>@isset($property['type']) {{ $property['type'] }} @endisset</td>
                            <td>@isset($property['description']) <p>{{ $property['description'] }}</p> @endisset</td>
                            <td>@isset($property['minLength']) {{ $property['minLength'] }} @endisset</td>
                            <td>@isset($property['maxLength']) {{ $property['maxLength'] }} @endisset</td>
                            <td>@isset($property['example']) {{ $property['example'] }} @endisset</td>
                            <td>@isset($property['enum']) @foreach($property['enum'] as $item) {{ $item }} @endforeach @endisset</td>
                        </tr>
                @endforeach
            @endforeach
        </tbody>
        </table>
    </div>

@endsection