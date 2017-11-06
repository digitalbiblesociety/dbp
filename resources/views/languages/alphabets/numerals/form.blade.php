<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
<fieldset class="text-center callout">
    <legend>Numerals</legend>
    <div class="equivalent-group">
        @if(isset($numbers))
            @foreach($numbers as $key => $number)
                <div class="row clonedInput" data-type="equivalent">
                    <label class="medium-2 columns">Clone/Remove
                        <div class="button-group expanded columns">
                            <a tabindex="0" class="clone button expanded">+</a>
                            <a tabindex="0" class="remove button expanded alert">-</a>
                        </div>
                    </label>
                    <label class="medium-2 columns">Alphabets <input type="text" name="numerals[{{ $key }}][script_id]" list="alphabets" value="{{ $number->script_id }}"></label>
                    <label class="medium-2 columns">Languages <input type="text" name="numerals[{{ $key }}][script_variant_iso]" list="languages" value="{{ $number->script_variant_iso }}"></label>
                    <label class="medium-2 columns">Numeral <input type="number" name="numerals[{{ $key }}][numeral]" value="{{ $number->numeral }}"></label>
                    <label class="medium-2 columns">Vernacular Numeral <input type="text" name="numerals[{{ $key }}][numeral_vernacular]" value="{{ $number->numeral_vernacular }}"></label>
                    <label class="medium-2 columns">Written Numeral <input type="text" name="numerals[{{ $key }}][numeral_written]" value="{{ $number->numeral_written }}"></label>
                </div>
            @endforeach
        @else
        <div class="row clonedInput" data-type="equivalent">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <label class="medium-2 columns">Alphabets <input type="text" name="numerals[1][script_id]" list="alphabets"></label>
            <label class="medium-2 columns">Languages <input type="text" name="numerals[1][script_variant_iso]" list="languages"></label>
            <label class="medium-2 columns">Numeral <input type="number" name="numerals[1][numeral]"></label>
            <label class="medium-2 columns">Vernacular Numeral <input type="text" name="numerals[1][numeral_vernacular]"></label>
            <label class="medium-2 columns">Written Numeral <input type="text" name="numerals[1][numeral_written]"></label>
        </div>
        @endif
    </div>
    <datalist id="languages">
        @foreach($languages as $language)
            <option value="{{ $language->iso }}">{{ $language->name }}</option>
        @endforeach
    </datalist>
    <datalist id="alphabets">
        @foreach($alphabets as $alphabet)
            <option value="{{ $alphabet->script }}" />
        @endforeach
    </datalist>
    <button class="button">Save</button>
</fieldset>
    </div>
    <div class="tabs-panel" id="field_descriptions">
        @include('layouts.swagger_descriptions', ['schema' => 'Number'])
    </div>
</div>