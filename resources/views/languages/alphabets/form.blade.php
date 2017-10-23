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
                <label>{{ trans('fields.alphabets_requires_font') }} <input type="checkbox" name="requires_font" /></label>
                <label>{{ trans('fields.alphabets_unicode') }} <input type="checkbox" name="unicode" /></label>
                <label>{{ trans('fields.alphabets_diacritics') }} <input type="checkbox" name="diacritics" /></label>
                <label>{{ trans('fields.alphabets_contextual_forms') }} <input type="checkbox" name="contextual_forms" /></label>
                <label>{{ trans('fields.alphabets_reordering') }} <input type="checkbox" name="reordering" /></label>
                <label>{{ trans('fields.alphabets_case') }} <input type="checkbox" name="case" /></label>
                <label>{{ trans('fields.alphabets_split_graphs') }} <input type="checkbox" name="split_graphs" /></label>
                <label>{{ trans('fields.alphabets_complex_positioning') }} <input type="checkbox" name="complex_positioning" /></label>
                <input type="submit" class="button expanded" value="{{ trans('fields.alphabets_save') }}"/>
            </aside>
            <div class="medium-9 columns">
                <label class="medium-4 columns">{{ trans('fields.alphabets_script') }} <input type="text" name="script" value="{{ old('script') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_open_type_tag') }} <input type="text" name="open_type_tag" value="{{ old('open_type_tag') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_name') }} <input type="text" name="name" value="{{ old('name') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_unicode_pdf') }} <input type="text" name="unicode_pdf" value="{{ old('unicode_pdf') }}" /></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_family') }}
                    <select name="family">
                        <option value="African">{{ trans('fields.alphabets_family_African') }}</option>
                        <option value="American">{{ trans('fields.alphabets_family_American') }}</option>
                        <option value="European">{{ trans('fields.alphabets_family_European') }}</option>
                        <option value="Southeast Asian">{{ trans('fields.alphabets_family_SoutheastAsian') }}</option>
                        <option value="Middle Eastern">{{ trans('fields.alphabets_family_MiddleEastern') }}</option>
                        <option value="Insular Southeast Asian">{{ trans('fields.alphabets_family_InsularSoutheastAsian') }}</option>
                        <option value="Indic">{{ trans('fields.alphabets_family_Indic') }}</option>
                        <option value="Artificial">{{ trans('fields.alphabets_family_Artificial') }}</option>
                        <option value="East Asian">{{ trans('fields.alphabets_family_EastAsian') }}</option>
                        <option value="Central Asian">{{ trans('fields.alphabets_family_CentralAsian') }}</option>
                        <option value="unspecified">{{ trans('fields.alphabets_family_unspecified') }}</option>
                        <option value="Signed Language">{{ trans('fields.alphabets_family_SignedLanguage') }}</option>
                        <option value="Pacific">{{ trans('fields.alphabets_family_Pacific') }}</option>
                        <option value="Handsigns">{{ trans('fields.alphabets_family_Handsigns') }}</option>
                    </select></label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_type') }}
                    <select name="type">
                        <option value="unspecified">{{ trans('fields.alphabets_type_unspecified') }}</option>
                        <option value="alphabet">{{ trans('fields.alphabets_type_alphabet') }}</option>
                        <option value="syllabary">{{ trans('fields.alphabets_type_syllabary') }}</option>
                        <option value="abugida">{{ trans('fields.alphabets_type_abugida') }}</option>
                        <option value="abjad">{{ trans('fields.alphabets_type_abjad') }}</option>
                        <option value="logo_syllabary">{{ trans('fields.alphabets_type_logo_syllabary') }}</option>
                        <option value="featural">{{ trans('fields.alphabets_type_featural') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_white_space') }}
                    <select>
                        <option value="unspecified">unspecified</option>
                        <option value="between words">between words</option>
                        <option value="discretionary">discretionary</option>
                        <option value="none">none</option>
                        <option value="between phrases">between phrases</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_status') }}
                    <select name="status">
                        <option value="Current">{{ trans('fields.alphabets_status_current') }}</option>
                        <option value="Historical">{{ trans('fields.alphabets_status_historical') }}</option>
                        <option value="Fictional">{{ trans('fields.alphabets_status_fictional') }}</option>
                        <option value="Unclear">{{ trans('fields.alphabets_status_unclear') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_baseline') }}
                    <select>
                        <option value="bottom">{{ trans('fields.alphabets_baseline_bottom') }}</option>
                        <option value="unspecified">{{ trans('fields.alphabets_baseline_unspecified') }}</option>
                        <option value="hanging">{{ trans('fields.alphabets_baseline_hanging') }}</option>
                        <option value="centered">{{ trans('fields.alphabets_baseline_centered') }}</option>
                        <option value="vertical">{{ trans('fields.alphabets_baseline_vertical') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_ligatures') }}
                    <select name="ligatures">
                        <option value="unspecified">unspecified</option>
                        <option value="none">none</option>
                        <option value="required">required</option>
                        <option value="optional">optional</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_direction') }}
                    <select name="direction">
                        <option value="LTR">{{ trans('fields.alphabets_direction_ltr') }}</option>
                        <option value="RTL">{{ trans('fields.alphabets_direction_rtl') }}</option>
                    </select>
                </label>
                <label class="medium-4 columns">{{ trans('fields.alphabets_sample_img') }} <input type="file" name="sample_img" /></label>
                <label class="medium-6 columns">{{ trans('fields.alphabets_direction_notes') }} <textarea name="direction_notes"></textarea></label>
                <label class="medium-6 columns">{{ trans('fields.alphabets_sample') }} <textarea name="sample"></textarea></label>
                <label class="medium-12 columns">{{ trans('fields.alphabets_description') }} <textarea name="description"></textarea></label>
            </div>
        </div>
    </div>

    <div class="tabs-panel" id="field_descriptions">
        @include('layouts.swagger_descriptions', ['schema' => 'Alphabet'])
    </div>

</div>




