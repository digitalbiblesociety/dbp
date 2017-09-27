<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
<fieldset class="text-center callout">
    <legend>Numerals</legend>
    <div class="equivalent-group">
        <div class="row clonedInput" data-type="equivalent">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <label class="medium-2 columns">Alphabets <input type="text" name="numerals[1][script_id]" list="alphabets">
                <datalist id="alphabets">
                    @foreach($alphabets as $alphabet)
                        <option value="{{ $alphabet->script }}" />
                    @endforeach
                </datalist></label>
            <label class="medium-2 columns">Languages <input type="text" name="numerals[1][script_varient_iso]" list="languages">
                <datalist id="languages">
                    @foreach($languages as $language)
                        <option value="{{ $language->iso }}">{{ $language->name }}</option>
                    @endforeach
                </datalist></label>
            <label class="medium-2 columns">Numeral <input type="number" name="numerals[1][numeral]"></label>
            <label class="medium-2 columns">Vernacular Numeral <input type="text" name="numerals[1][numeral_vernacular]"></label>
            <label class="medium-2 columns">Written Numeral <input type="text" name="numerals[1][numeral_written]"></label>
        </div>
    </div>
</fieldset>
    </div>
<div class="tabs-panel" id="field_descriptions">
<table class="unstriped">
    <thead>
    <tr>
        <td>Name</td>
        <td>Description</td>
        <td>Type</td>
        <td>Length</td>
        <td>Required</td>
        <td>Unique</td>
        <td>Description</td>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>script_id</td>
            <td>The Script Code</td>
            <td>alpha string</td>
            <td>exactly 4 characters</td>
            <td><span class="tag required">required</span></td>
            <td><span class="tag unique">unique</span></td>
            <td>This should match exactly the codes from the <a href="http://www.unicode.org/iso15924/iso15924-codes.html">ISO 15924 Registration Authority.</a> Any Custom codes will be punishable by immediate execution by stork squad.</td>
        </tr>
        <tr>
            <td>script_varient_iso</td>
            <td>The Iso Code</td>
            <td>alpha string</td>
            <td>exactly 3 characters</td>
            <td></td>
            <td></td>
            <td>A three letter identifier for the languages resources. This code matches up exactly with the codes from the <a href="http://www-01.sil.org/iso639-3/default.asp">ISO 639-3 standard</a>.</td>
        </tr>
        <tr>
            <td>numeral</td>
            <td>Numeral</td>
            <td>integer</td>
            <td></td>
            <td><span class="tag required">required</span></td>
            <td></td>
            <td>The integer value for the vernacular number</td>
        </tr>
        <tr>
            <td>numeral_vernacular</td>
            <td>Vernacular Numeral</td>
            <td>string</td>
            <td></td>
            <td><span class="tag required">required</span></td>
            <td></td>
            <td>The vernacular number</td>
        </tr>
        <tr>
            <td>numeral_written</td>
            <td>Written Numeral</td>
            <td>string</td>
            <td></td>
            <td></td>
            <td></td>
            <td>The written word for the vernacular number</td>
        </tr>
    </tbody>
</table>
</div>
</div>