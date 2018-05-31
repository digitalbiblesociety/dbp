
<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
        <div class="row">
            <div class="medium-3 columns"><label>Glotto ID<input type="text" name="glotto_id" value="{{ $language->glotto_id ?? old('glotto_id') }}" placeholder="Glotto Code" /></label></div>
            <div class="medium-3 columns"><label>Iso Code<input type="text" name="iso" value="{{ $language->iso ?? old('iso') }}" placeholder="Iso Code" /></label></div>
            <div class="medium-4 columns"><label>Name<input type="text" name="name" value="{{ $language->name ?? old('name') }}" placeholder="Name" /></label></div>
            <div class="medium-2 columns"><label>Level <input type="text" name="level" value="{{ $language->level ?? old('level') }}" placeholder="level" /></label></div>
        </div>
        <div class="row">
            <div class="medium-3 columns"><label>Maps <input type="text" name="maps" value="{{ $language->maps ?? old('maps') }}" placeholder="maps" /></label></div>
            <div class="medium-3 columns"><label>Development <input type="text" name="development" value="{{ $language->development ?? old('development') }}" placeholder="development" /></label></div>
            <div class="medium-3 columns"><label>Use <input type="text" name="use" value="{{ $language->use ?? old('use') }}" placeholder="use" /></label></div>
            <div class="medium-3 columns"><label>Area <input type="text" name="area" value="{{ $language->area ?? old('area') }}" placeholder="area" /></label></div>
            <div class="medium-3 columns"><label>Population <input type="number" name="population" value="{{ $language->population ?? old('population') }}" placeholder="population" /></label></div>
            <div class="medium-3 columns"><label>Population Notes <input type="text" name="population_notes" value="{{ $language->population_notes ?? old('population_notes') }}" placeholder="population_notes" /></label></div>
            <div class="medium-3 columns"><label>Notes <input type="text" name="notes" value="{{ $language->notes ?? old('notes') }}" placeholder="notes" /></label></div>
            <div class="medium-3 columns"><label>Typology <input type="text" name="typology" value="{{ $language->typology ?? old('typology') }}" placeholder="typology" /></label></div>
            <div class="medium-3 columns"><label>Writing <input type="text" name="writing" value="{{ $language->writing ?? old('writing') }}" placeholder="writing" /></label></div>
            <div class="medium-3 columns"><label>Family PK <input type="text" name="family_pk" value="{{ $language->family_pk ?? old('family_pk') }}" placeholder="family_pk" /></label></div>
            <div class="medium-3 columns"><label>Father PK <input type="text" name="father_pk" value="{{ $language->father_pk ?? old('father_pk') }}" placeholder="father_pk" /></label></div>
            <div class="medium-3 columns"><label>Child Dialect Count <input type="text" name="child_dialect_count" value="{{ $language->child_dialect_count ?? old('child_dialect_count') }}" placeholder="child_dialect_count" /></label></div>
            <div class="medium-3 columns"><label>Child Family Count <input type="text" name="child_family_count" value="{{ $language->child_family_count ?? old('child_family_count') }}" placeholder="child_family_count" /></label></div>
            <div class="medium-3 columns"><label>Child Language Count <input type="text" name="child_language_count" value="{{ $language->child_language_count ?? old('child_language_count') }}" placeholder="child_language_count" /></label></div>
            <div class="medium-3 columns"><label>Latitude <input type="text" name="latitude" value="{{ $language->latitude ?? old('latitude') }}" placeholder="latitude" /></label></div>
            <div class="medium-3 columns"><label>Longitude <input type="text" name="longitude" value="{{ $language->longitude ?? old('longitude') }}" placeholder="longitude" /></label></div>
            <div class="medium-3 columns"><label>PK <input type="text" name="pk" value="{{ $language->pk ?? old('pk') }}" placeholder="pk" /></label></div>
            <div class="medium-3 columns"><label>Status <input type="text" name="status" value="{{ $language->status ?? old('status') }}" placeholder="status" /></label></div>
            <div class="medium-3 columns"><label>Country ID <input type="text" name="country_id" value="{{ $language->country_id ?? old('country_id') }}" placeholder="country_id" /></label></div>
            <div class="medium-3 columns"><label>Scope <input type="text" name="scope" value="{{ $language->scope ?? old('scope') }}" placeholder="scope" /></label></div>
        </div>
        <div class="row">
            <div class="medium-6 columns"><label>Description <textarea cols="6" name="description">{{ $language->description ?? old('description') }}</textarea></label></div>
            <div class="medium-6 columns"><label>Location <textarea cols="6" name="location">{{ $language->location ?? old('location') }}</textarea></label></div>
        </div>
    </div>

	<?php $swagger = fetchSwaggerSchema("Language","V4"); ?>
    @if($swagger)
    <div class="tabs-panel" id="field_descriptions">
        <table class="unstriped">
            <tbody>
        <tr>
            <td>glotto_id</td>
            <td>CHAR</td>
            <td>Type</td>
            <td>4</td>
            <td></td>
            <td>Unique</td>
            <td>A three letter identifier for the languages resources. This code matches up exactly with the codes from the [ISO 639-3 standard](http://www-01.sil.org/iso639-3/default.asp). If the Iso Code Exists the Glotto Code is not required but may exist!</td>
        </tr>
        <tr>
            <td>iso</td>
            <td>CHAR</td>
            <td>Type</td>
            <td>4</td>
            <td></td>
            <td>Unique</td>
            <td>A three letter identifier for the languages resources. This code matches up exactly with the codes from the <a href="http://www-01.sil.org/iso639-3/default.asp">ISO 639-3 standard</a>. If the Iso Code Exists the Glotto Code is not required but may exist!</td>
        </tr>
        <tr>
        <td>iso2B</td>
        <td>char</td>
        </tr>
        <tr>
        <td>iso2T</td>
        <td>char</td>
        </tr>
        <tr>
        <td>iso1</td>
        <td>char</td>
        </tr>
        <tr>
        <td>name</td>
        <td>string</td>
        </tr>
        <tr>
        <td>autonym</td>
        <td>string</td>
        </tr>
        <tr>
        <td>level</td>
        <td>string</td>
        </tr>
        <tr>
        <td>maps</td>
        <td>string</td>
        </tr>
        <tr>
        <td>development</td>
        <td>text</td>
        </tr>
        <tr>
        <td>use</td>
        <td>text</td>
        </tr>
        <tr>
        <td>location</td>
        <td>text</td>
        </tr>
        <tr>
        <td>area</td>
        <td>text</td>
        </tr>
        <tr>
        <td>population</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>population_notes</td>
        <td>text</td>
        </tr>
        <tr>
        <td>notes</td>
        <td>text</td>
        </tr>
        <tr>
        <td>typology</td>
        <td>text</td>
        </tr>
        <tr>
        <td>writing</td>
        <td>text</td>
        </tr>
        <tr>
        <td>description</td>
        <td>text</td>
        </tr>
        <tr>
        <td>family_pk</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>father_pk</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>child_dialect_count</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>child_family_count</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>child_language_count</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>latitude</td>
        <td>float</td>
        </tr>
        <tr>
        <td>longitude</td>
        <td>float</td>
        </tr>
        <tr>
        <td>pk</td>
        <td>integer</td>
        </tr>
        <tr>
        <td>status</td>
        <td>text</td>
        </tr>
        <tr>
        <td>country_id</td>
        <td>char</td>
        </tr>
        <tr>
        <td>scope</td>
        <td>string</td>
        </tr>
            </tbody>
        </table>
    </div>
    @endif

</div>