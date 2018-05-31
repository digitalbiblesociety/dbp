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
                <label>{{ trans('fields.alphabets_requires_font') }} <input type="checkbox" name="requires_font" @if(isset($alphabet)) @if($alphabet->requires_font) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_unicode') }} <input type="checkbox" name="unicode" @if(isset($alphabet)) @if($alphabet->unicode) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_diacritics') }} <input type="checkbox" name="diacritics" @if(isset($alphabet)) @if($alphabet->diacritics) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_contextual_forms') }} <input type="checkbox" name="contextual_forms" @if(isset($alphabet)) @if($alphabet->contextual_forms) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_reordering') }} <input type="checkbox" name="reordering" @if(isset($alphabet)) @if($alphabet->reordering) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_case') }} <input type="checkbox" name="case" @if(isset($alphabet)) @if($alphabet->case) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_split_graphs') }} <input type="checkbox" name="split_graphs" @if(isset($alphabet)) @if($alphabet->split_graphs) checked @endif @endif /></label>
                <label>{{ trans('fields.alphabets_complex_positioning') }} <input type="checkbox" name="complex_positioning" @if(isset($alphabet)) @if($alphabet->complex_positioning) checked @endif @endif /></label>
                <input type="submit" class="button expanded" value="{{ trans('fields.alphabets_save') }}"/>
            </aside>
            <div class="medium-9 columns">
                <label class="medium-4 columns">{{ trans('fields.alphabets_script') }} <input type="text" name="script" value="{{ $alphabet->script ?? old('script') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_open_type_tag') }} <input type="text" name="open_type_tag" value="{{ $alphabet->open_type_tag ?? old('open_type_tag') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_name') }} <input type="text" name="name" value="{{ $alphabet->name ?? old('name') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_unicode_pdf') }} <input type="text" name="unicode_pdf" value="{{ $alphabet->unicode_pdf ?? old('unicode_pdf') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_family') }}
                    <select name="family">
                        <option @if(isset($alphabet)) @if($alphabet->family == "African") selected @endif @endif value="African">{{ trans('fields.alphabets_family_African') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "American") selected @endif @endif value="American">{{ trans('fields.alphabets_family_American') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "European") selected @endif @endif value="European">{{ trans('fields.alphabets_family_European') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Southeast Asian") selected @endif @endif value="Southeast Asian">{{ trans('fields.alphabets_family_SoutheastAsian') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Middle Eastern") selected @endif @endif value="Middle Eastern">{{ trans('fields.alphabets_family_MiddleEastern') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Insular Southeast Asian") selected @endif @endif value="Insular Southeast Asian">{{ trans('fields.alphabets_family_InsularSoutheastAsian') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Indic") selected @endif @endif value="Indic">{{ trans('fields.alphabets_family_Indic') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Artificial") selected @endif @endif value="Artificial">{{ trans('fields.alphabets_family_Artificial') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "East Asian") selected @endif @endif value="East Asian">{{ trans('fields.alphabets_family_EastAsian') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Central Asian") selected @endif @endif value="Central Asian">{{ trans('fields.alphabets_family_CentralAsian') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "unspecified") selected @endif @endif value="unspecified">{{ trans('fields.alphabets_family_unspecified') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Signed Language") selected @endif @endif value="Signed Language">{{ trans('fields.alphabets_family_SignedLanguage') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Pacific") selected @endif @endif value="Pacific">{{ trans('fields.alphabets_family_Pacific') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->family == "Handsigns") selected @endif @endif value="Handsigns">{{ trans('fields.alphabets_family_Handsigns') }}</option>
                    </select></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_type') }}
                    <select name="type">
                        <option @if(isset($alphabet)) @if($alphabet->type == "unspecified") selected @endif @endif value="unspecified">{{ trans('fields.alphabets_type_unspecified') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "lphabet") selected @endif @endif value="alphabet">{{ trans('fields.alphabets_type_alphabet') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "yllabary") selected @endif @endif value="syllabary">{{ trans('fields.alphabets_type_syllabary') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "bugida") selected @endif @endif value="abugida">{{ trans('fields.alphabets_type_abugida') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "bjad") selected @endif @endif value="abjad">{{ trans('fields.alphabets_type_abjad') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "ogo_syllabary") selected @endif @endif value="logo_syllabary">{{ trans('fields.alphabets_type_logo_syllabary') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->type == "eatural") selected @endif @endif value="featural">{{ trans('fields.alphabets_type_featural') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_white_space') }}
                    <select name="white_space">
                        <option @if(isset($alphabet)) @if($alphabet->white_space == "unspecifie") selected @endif @endif value="unspecified">unspecified</option>
                        <option @if(isset($alphabet)) @if($alphabet->white_space == "between words") selected @endif @endif value="between words">between words</option>
                        <option @if(isset($alphabet)) @if($alphabet->white_space == "discretionar") selected @endif @endif value="discretionary">discretionary</option>
                        <option @if(isset($alphabet)) @if($alphabet->white_space == "non") selected @endif @endif value="none">none</option>
                        <option @if(isset($alphabet)) @if($alphabet->white_space == "between phrases") selected @endif @endif value="between phrases">between phrases</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_status') }}
                    <select name="status">
                        <option @if(isset($alphabet)) @if($alphabet->status == "Current") selected @endif @endif value="Current">{{ trans('fields.alphabets_status_current') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "Historical") selected @endif @endif value="Historical">{{ trans('fields.alphabets_status_historical') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "Fictional") selected @endif @endif value="Fictional">{{ trans('fields.alphabets_status_fictional') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "Unclear") selected @endif @endif value="Unclear">{{ trans('fields.alphabets_status_unclear') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_baseline') }}
                    <select name="baseline">
                        <option @if(isset($alphabet)) @if($alphabet->status == "bottom") selected @endif @endif value="bottom">{{ trans('fields.alphabets_baseline_bottom') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "unspecified") selected @endif @endif value="unspecified">{{ trans('fields.alphabets_baseline_unspecified') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "hanging") selected @endif @endif value="hanging">{{ trans('fields.alphabets_baseline_hanging') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "centered") selected @endif @endif value="centered">{{ trans('fields.alphabets_baseline_centered') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->status == "vertical") selected @endif @endif value="vertical">{{ trans('fields.alphabets_baseline_vertical') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_ligatures') }}
                    <select name="ligatures">
                        <option @if(isset($alphabet)) @if($alphabet->ligatures == "unspecified") selected @endif @endif value="unspecified">unspecified</option>
                        <option @if(isset($alphabet)) @if($alphabet->ligatures == "none") selected @endif @endif value="none">none</option>
                        <option @if(isset($alphabet)) @if($alphabet->ligatures == "required") selected @endif @endif value="required">required</option>
                        <option @if(isset($alphabet)) @if($alphabet->ligatures == "optional") selected @endif @endif value="optional">optional</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_direction') }}
                    <select name="direction">
                        <option @if(isset($alphabet)) @if($alphabet->direction == "LTR") selected @endif @endif value="LTR">{{ trans('fields.alphabets_direction_ltr') }}</option>
                        <option @if(isset($alphabet)) @if($alphabet->direction == "RTL") selected @endif @endif value="RTL">{{ trans('fields.alphabets_direction_rtl') }}</option>
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




