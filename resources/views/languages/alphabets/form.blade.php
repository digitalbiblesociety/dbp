<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
        @if(session('status'))<div class="callout success">{{ session('status') }}</div>@endif

            @if($errors->any())
                <div class="callout alert medium-6 columns centered">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </div>
            @endif

        <div class="row">
            <aside class="medium-3 columns">
                <label>{{ trans('fields.alphabets_requires_font') }} <input type="checkbox" name="requires_font" @if($alphabet->requires_font) checked @endif /></label>
                <label>{{ trans('fields.alphabets_unicode') }} <input type="checkbox" name="unicode" @if($alphabet->unicode) checked @endif /></label>
                <label>{{ trans('fields.alphabets_diacritics') }} <input type="checkbox" name="diacritics" @if($alphabet->diacritics) checked @endif /></label>
                <label>{{ trans('fields.alphabets_contextual_forms') }} <input type="checkbox" name="contextual_forms" @if($alphabet->contextual_forms) checked @endif /></label>
                <label>{{ trans('fields.alphabets_reordering') }} <input type="checkbox" name="reordering" @if($alphabet->reordering) checked @endif /></label>
                <label>{{ trans('fields.alphabets_case') }} <input type="checkbox" name="case" @if($alphabet->case) checked @endif /></label>
                <label>{{ trans('fields.alphabets_split_graphs') }} <input type="checkbox" name="split_graphs" @if($alphabet->split_graphs) checked @endif /></label>
                <label>{{ trans('fields.alphabets_complex_positioning') }} <input type="checkbox" name="complex_positioning" @if($alphabet->complex_positioning) checked @endif /></label>
                <input type="submit" class="button expanded" value="{{ trans('fields.alphabets_save') }}"/>
            </aside>
            <div class="medium-9 columns">
                <label class="medium-4 columns">{{ trans('fields.alphabets_script') }} <input type="text" name="script" value="{{ $alphabet->script ?? old('script') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_open_type_tag') }} <input type="text" name="open_type_tag" value="{{ $alphabet->open_type_tag ?? old('open_type_tag') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_name') }} <input type="text" name="name" value="{{ $alphabet->name ?? old('name') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_unicode_pdf') }} <input type="text" name="unicode_pdf" value="{{ $alphabet->unicode_pdf ?? old('unicode_pdf') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_family') }}
                    <select name="family">
                        <option @if($alphabet->family == "African") selected @endif value="African">{{ trans('fields.alphabets_family_African') }}</option>
                        <option @if($alphabet->family == "American") selected @endif value="American">{{ trans('fields.alphabets_family_American') }}</option>
                        <option @if($alphabet->family == "European") selected @endif value="European">{{ trans('fields.alphabets_family_European') }}</option>
                        <option @if($alphabet->family == "Southeast Asian") selected @endif value="Southeast Asian">{{ trans('fields.alphabets_family_SoutheastAsian') }}</option>
                        <option @if($alphabet->family == "Middle Eastern") selected @endif value="Middle Eastern">{{ trans('fields.alphabets_family_MiddleEastern') }}</option>
                        <option @if($alphabet->family == "Insular Southeast Asian") selected @endif value="Insular Southeast Asian">{{ trans('fields.alphabets_family_InsularSoutheastAsian') }}</option>
                        <option @if($alphabet->family == "Indic") selected @endif value="Indic">{{ trans('fields.alphabets_family_Indic') }}</option>
                        <option @if($alphabet->family == "Artificial") selected @endif value="Artificial">{{ trans('fields.alphabets_family_Artificial') }}</option>
                        <option @if($alphabet->family == "East Asian") selected @endif value="East Asian">{{ trans('fields.alphabets_family_EastAsian') }}</option>
                        <option @if($alphabet->family == "Central Asian") selected @endif value="Central Asian">{{ trans('fields.alphabets_family_CentralAsian') }}</option>
                        <option @if($alphabet->family == "unspecified") selected @endif value="unspecified">{{ trans('fields.alphabets_family_unspecified') }}</option>
                        <option @if($alphabet->family == "Signed Language") selected @endif value="Signed Language">{{ trans('fields.alphabets_family_SignedLanguage') }}</option>
                        <option @if($alphabet->family == "Pacific") selected @endif value="Pacific">{{ trans('fields.alphabets_family_Pacific') }}</option>
                        <option @if($alphabet->family == "Handsigns") selected @endif value="Handsigns">{{ trans('fields.alphabets_family_Handsigns') }}</option>
                    </select></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_type') }}
                    <select name="type">
                        <option @if($alphabet->type == "unspecified") selected @endif value="unspecified">{{ trans('fields.alphabets_type_unspecified') }}</option>
                        <option @if($alphabet->type == "lphabet") selected @endif value="alphabet">{{ trans('fields.alphabets_type_alphabet') }}</option>
                        <option @if($alphabet->type == "yllabary") selected @endif value="syllabary">{{ trans('fields.alphabets_type_syllabary') }}</option>
                        <option @if($alphabet->type == "bugida") selected @endif value="abugida">{{ trans('fields.alphabets_type_abugida') }}</option>
                        <option @if($alphabet->type == "bjad") selected @endif value="abjad">{{ trans('fields.alphabets_type_abjad') }}</option>
                        <option @if($alphabet->type == "ogo_syllabary") selected @endif value="logo_syllabary">{{ trans('fields.alphabets_type_logo_syllabary') }}</option>
                        <option @if($alphabet->type == "eatural") selected @endif value="featural">{{ trans('fields.alphabets_type_featural') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_white_space') }}
                    <select name="white_space">
                        <option @if($alphabet->white_space == "unspecifie") selected @endif value="unspecified">unspecified</option>
                        <option @if($alphabet->white_space == "between words") selected @endif value="between words">between words</option>
                        <option @if($alphabet->white_space == "discretionar") selected @endif value="discretionary">discretionary</option>
                        <option @if($alphabet->white_space == "non") selected @endif value="none">none</option>
                        <option @if($alphabet->white_space == "between phrases") selected @endif value="between phrases">between phrases</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_status') }}
                    <select name="status">
                        <option @if($alphabet->status == "Current") selected @endif value="Current">{{ trans('fields.alphabets_status_current') }}</option>
                        <option @if($alphabet->status == "Historical") selected @endif value="Historical">{{ trans('fields.alphabets_status_historical') }}</option>
                        <option @if($alphabet->status == "Fictional") selected @endif value="Fictional">{{ trans('fields.alphabets_status_fictional') }}</option>
                        <option @if($alphabet->status == "Unclear") selected @endif value="Unclear">{{ trans('fields.alphabets_status_unclear') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_baseline') }}
                    <select name="baseline">
                        <option @if($alphabet->status == "bottom") selected @endif value="bottom">{{ trans('fields.alphabets_baseline_bottom') }}</option>
                        <option @if($alphabet->status == "unspecified") selected @endif value="unspecified">{{ trans('fields.alphabets_baseline_unspecified') }}</option>
                        <option @if($alphabet->status == "hanging") selected @endif value="hanging">{{ trans('fields.alphabets_baseline_hanging') }}</option>
                        <option @if($alphabet->status == "centered") selected @endif value="centered">{{ trans('fields.alphabets_baseline_centered') }}</option>
                        <option @if($alphabet->status == "vertical") selected @endif value="vertical">{{ trans('fields.alphabets_baseline_vertical') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_ligatures') }}
                    <select name="ligatures">
                        <option @if($alphabet->ligatures == "unspecified") selected @endif value="unspecified">unspecified</option>
                        <option @if($alphabet->ligatures == "none") selected @endif value="none">none</option>
                        <option @if($alphabet->ligatures == "required") selected @endif value="required">required</option>
                        <option @if($alphabet->ligatures == "optional") selected @endif value="optional">optional</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_direction') }}
                    <select name="direction">
                        <option @if($alphabet->direction == "LTR") selected @endif value="LTR">{{ trans('fields.alphabets_direction_ltr') }}</option>
                        <option @if($alphabet->direction == "RTL") selected @endif value="RTL">{{ trans('fields.alphabets_direction_rtl') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_sample_img') }} <input type="file" name="sample_img" value="{{ $alphabet->sample_img ?? old('sample_img') }}" /></label>
                <label class="medium-6 columns">{{ trans('fields.alphabets_direction_notes') }} <textarea name="direction_notes">{{ $alphabet->direction_notes ?? old('direction_notes') }}</textarea></label>
                <label class="medium-6 columns">{{ trans('fields.alphabets_sample') }} <textarea name="sample">{{ $alphabet->sample ?? old('sample') }}</textarea></label>
                <label class="medium-12 columns">{{ trans('fields.alphabets_description') }} <textarea name="description">{{ $alphabet->description ?? old('description') }}</textarea></label>
            </div>
        </div>
    </div>

    <div class="tabs-panel" id="field_descriptions">
        @include('layouts.swagger_descriptions', ['schema' => 'Alphabet'])
    </div>

</div>




