
<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
        <div class="row">
            <div class="medium-3 columns"><label>Glotto ID<input type="text" name="glotto_id" value="{{ old('glotto_id') or '' }}" placeholder="Glotto Code" /></label></div>
            <div class="medium-3 columns"><label>Iso Code<input type="text" name="iso" value="{{ old('iso') or '' }}" placeholder="Iso Code" /></label></div>
            <div class="medium-4 columns"><label>Name<input type="text" name="name" value="{{ old('name') or '' }}" placeholder="Name" /></label></div>
            <div class="medium-2 columns"><label>Level <input type="text" name="level" value="{{ old('level') or '' }}" placeholder="level" /></label></div>
        </div>
        <div class="row">
            <div class="medium-3 columns"><label>Maps <input type="text" name="maps" value="{{ old('maps') or '' }}" placeholder="maps" /></label></div>
            <div class="medium-3 columns"><label>Development <input type="text" name="development" value="{{ old('development') or '' }}" placeholder="development" /></label></div>
            <div class="medium-3 columns"><label>Use <input type="text" name="use" value="{{ old('use') or '' }}" placeholder="use" /></label></div>
            <div class="medium-3 columns"><label>Location <input type="text" name="location" value="{{ old('location') or '' }}" placeholder="location" /></label></div>
            <div class="medium-3 columns"><label>Area <input type="text" name="area" value="{{ old('area') or '' }}" placeholder="area" /></label></div>
            <div class="medium-3 columns"><label>Population <input type="number" name="population" value="{{ old('population') or '' }}" placeholder="population" /></label></div>
            <div class="medium-3 columns"><label>Population Notes <input type="text" name="population_notes" value="{{ old('population_notes') or '' }}" placeholder="population_notes" /></label></div>
            <div class="medium-3 columns"><label>Notes <input type="text" name="notes" value="{{ old('notes') or '' }}" placeholder="notes" /></label></div>
            <div class="medium-3 columns"><label>Typology <input type="text" name="typology" value="{{ old('typology') or '' }}" placeholder="typology" /></label></div>
            <div class="medium-3 columns"><label>Writing <input type="text" name="writing" value="{{ old('writing') or '' }}" placeholder="writing" /></label></div>
            <div class="medium-3 columns"><label>Description <input type="text" name="description" value="{{ old('description') or '' }}" placeholder="description" /></label></div>
            <div class="medium-3 columns"><label>Family PK <input type="text" name="family_pk" value="{{ old('family_pk') or '' }}" placeholder="family_pk" /></label></div>
            <div class="medium-3 columns"><label>Father PK <input type="text" name="father_pk" value="{{ old('father_pk') or '' }}" placeholder="father_pk" /></label></div>
            <div class="medium-3 columns"><label>Child Dialect Count <input type="text" name="child_dialect_count" value="{{ old('child_dialect_count') or '' }}" placeholder="child_dialect_count" /></label></div>
            <div class="medium-3 columns"><label>Child Family Count <input type="text" name="child_family_count" value="{{ old('child_family_count') or '' }}" placeholder="child_family_count" /></label></div>
            <div class="medium-3 columns"><label>Child Language Count <input type="text" name="child_language_count" value="{{ old('child_language_count') or '' }}" placeholder="child_language_count" /></label></div>
            <div class="medium-3 columns"><label>Latitude <input type="text" name="latitude" value="{{ old('latitude') or '' }}" placeholder="latitude" /></label></div>
            <div class="medium-3 columns"><label>Longitude <input type="text" name="longitude" value="{{ old('longitude') or '' }}" placeholder="longitude" /></label></div>
            <div class="medium-3 columns"><label>PK <input type="text" name="pk" value="{{ old('pk') or '' }}" placeholder="pk" /></label></div>
            <div class="medium-3 columns"><label>Status <input type="text" name="status" value="{{ old('status') or '' }}" placeholder="status" /></label></div>
            <div class="medium-3 columns"><label>Country ID <input type="text" name="country_id" value="{{ old('country_id') or '' }}" placeholder="country_id" /></label></div>
            <div class="medium-3 columns"><label>Scope <input type="text" name="scope" value="{{ old('scope') or '' }}" placeholder="scope" /></label></div>
        </div>
    </div>
    <div class="tabs-panel" id="field_descriptions">

        <table class="unstriped">
            <?php
	            $swagger = fetchSwaggerSchema("Language","V4");
            ?>
            <thead>
            <tr>
                @foreach($swagger->field_names as $field_name)
                    <td>{{ $field_name }}</td>
                @endforeach
            </tr>
            </thead>
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
</div>