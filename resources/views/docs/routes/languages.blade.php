@extends('layouts.app')

@section('head')
    <style>
        .route-section {
            padding:80px 40px;
        }
    </style>
@endsection

@section('content')

    <div class="route-section row">
        <ul>
        <li><b>v:</b> Specifies the version of the API requested.</li>
        <li><b>key:</b> Your DBT API key provided upon registration.</li>
        <li><b>reply (optional):</b> [json|jsonp|html] Specifies the response type requested by the caller. Default is json.</li>
        <li><b>callback (optional):</b> Specifies the name of the function returned when a JSONP reply is requested. (Requires reply = jsonp)</li>
        <li><b>echo (optional):</b> [true|false] Not available in v. 1. If unused the results are as before. If set to \'true\', the results will be prepended with the array of calling arguments used. This does mean that the results will be one layer deeper as the results will be an array where the first element is the array of input arguments and the second element will be the results as normally returned.</li>
        <li><b>_method=put:</b> REQUIRED for PUT DBT methods - PUT is not properly supported. To effect DBT methods requiring PUT, use the GET HTTP method and &_method=put.</li>
        <li><b>code (optional):</b> Get the entry for a three letter language code.</li>
        <li><b>name (optional):</b> Get the entry for a part of a language name in either native language or English.</li>
        <li><b>full_word (optional):</b> [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.</li>
        <li><b>family_only (optional):</b> [true|false] When set to true the returned list is of only legal language families. The default is false.</li>
        <li><b>possibilities (optional); [true|false] When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria.</li>
        <li><b>sort_by (optional):</b> [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.</li>
        </ul>
        <form id="api_form" action="http://dbt.io/library/language" method="get">
            <div class="row">
                <label>URL: </label>http://dbt.io/library/language
            </div>
            <div class="medium-4 columns">
                <label>Key (required): </label>
                <input type="text" name="key" size="32">
            </div>
            <div class="medium-4 columns">
                <label>Code:</label>
                <input type="text" name="code" size="64">
            </div>
            <div class="medium-4 columns">
                <label>Name:</label>
                <input type="text" name="name" size="64">
            </div>
            <div class="medium-4 columns">
                <label>full_word:</label>
                <input type="text" name="full_word" size="64">
            </div>
            <div class="medium-4 columns">
                <label>family_only:</label>
                <input type="text" name="family_only" size="64">
            </div>
            <div class="medium-4 columns">
                <label>possibilities: </label>
                <input type="text" name="possibilities" size="64">
            </div>
            <div class="medium-4 columns">
                <label>sort_by: </label>
                <input type="text" name="sort_by" size="64">
            </div>
            <div class="medium-4 columns">
                <label>reply: </label>
                <input type="text" name="reply">
            </div>
            <div class="medium-4 columns">
                <label>callback: </label>
                <input type="text" name="callback">
            </div>
            <div class="medium-4 columns">
                <label>echo: </label>
                <input type="text" name="echo">
            </div>
            <input name="v" value="2">
            <input type="submit" name="Submit" value="Test"></td>
        </form>
        <div class="medium-4 columns">
            <h5>{{ trans('docs.languages_index_title') }}</h5>
            <p>{{ trans('docs.languages_index_description') }}</p>
            <code>{{ route('api_languages.index') }}</code>
        </div>
        <div class="medium-8 columns">
            <form>
                <input name="limit" placeholder="Limit the number of results" />
                <code class="playground-results"></code>
            </form>
        </div>
    </div>

    <div class="route-section row">
        <div class="medium-4 columns">
            <h5>{{ trans('docs.languages_show_title') }}</h5>
            <p>{{ trans('docs.languages_show_description') }}</p>
            <code>{{ route('api_languages.show', ['eng']) }}</code>
        </div>
    </div>

@endsection

@section('footer')
    <script>

    </script>
@endsection