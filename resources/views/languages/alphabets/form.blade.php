<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="fields">
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
        <div class="field-definitions row">
            <table class="unstriped">
                <thead>
                <tr>
                    <td>Name</td>
                    <td>Title</td>
                    <td>Type</td>
                    <td>Length</td>
                    <td>Required</td>
                    <td>Unique</td>
                    <td>Description</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>script</td>
                    <td>The Script Code</td>
                    <td>alpha-numeric string</td>
                    <td>exactly 4 characters</td>
                    <td><span class="tag required">required</span></td>
                    <td><span class="tag unique">unique</span></td>
                    <td>This should match exactly the codes from the <a href="http://www.unicode.org/iso15924/iso15924-codes.html">ISO 15924 Registration Authority.</a> Any Custom codes will be punishable by immediate execution by stork squad.</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>The name field</td>
                    <td>alpha-numeric string</td>
                    <td>1-191 characters</td>
                    <td><span class="tag required">required</span></td>
                    <td><span class="tag unique">unique</span></td>
                    <td>Pretty standard stuff, just needs to be unique.</td>
                </tr>
                <tr>
                    <td>unicode_pdf</td>
                    <td>The url for the unicode PDF</td>
                    <td>alpha-numeric string</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td><span class="tag unique">unique</span></td>
                    <td>Link to a reference PDF for the specific writing system</td>
                </tr>
                <tr>
                    <td>family</td>
                    <td>The Alphabet Family</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>The Group of Alphabets that this Alphabet belongs to.</td>
                </tr>
                <tr>
                    <td>type</td>
                    <td>The Alphabet Type</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>The Type of Alphabets that this Alphabet is.</td>
                </tr>
                <tr>
                    <td>white_space</td>
                    <td>White Space</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>The importance whitespace has to this alphabet</td>
                </tr>
                <tr>
                    <td>open_type_tag</td>
                    <td>The OpenType font format Code</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td><a href="https://www.microsoft.com/typography/otspec/otover.htm">The OpenType font format</a> is an extension of the TrueType font format, adding support for PostScript font data. The OpenType font format was developed jointly by Microsoft and Adobe.</td>
                </tr>
                <tr>
                    <td>complex_positioning</td>
                    <td>The OpenType font format Code</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires some complex positioning</td>
                </tr>
                <tr>
                    <td>requires_font</td>
                    <td>This alphabet requires a font</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>The majority of devices will require a font to display this alphabet correctly</td>
                </tr>
                <tr>
                    <td>unicode</td>
                    <td>This font is Unicode</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires some complex positioning</td>
                </tr>
                <tr>
                    <td>diacritics</td>
                    <td>Diacritics</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires diacritics</td>
                </tr>
                <tr>
                    <td>contextual_forms</td>
                    <td>Contextual Forms</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires Contextual Forms</td>
                </tr>
                <tr>
                    <td>reordering</td>
                    <td>Reordering</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires Reordering</td>
                </tr>
                <tr>
                    <td>case</td>
                    <td>Case</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires capitalization</td>
                </tr>
                <tr>
                    <td>split_graphs</td>
                    <td>Split Graphs</td>
                    <td>Boolean</td>
                    <td>1 or 0, integer</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet requires split graphs</td>
                </tr>
                <tr>
                    <td>status</td>
                    <td>Status of the alphabet</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet's current status</td>
                </tr>
                <tr>
                    <td>baseline</td>
                    <td>Baseline of the alphabet</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>This alphabet's current baseline either bottom, hanging, centered, or vertical</td>
                </tr>
                <tr>
                    <td>ligatures</td>
                    <td>Ligatures</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>Alphabet Requires Ligatures</td>
                </tr>
                <tr>
                    <td>direction</td>
                    <td>Direction</td>
                    <td>String</td>
                    <td>1-3 char</td>
                    <td></td>
                    <td></td>
                    <td>The Alphabet's direction either LTR or RTL</td>
                </tr>
                <tr>
                    <td>direction_notes</td>
                    <td>Direction Notes</td>
                    <td>TEXT</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Notes about Alphabet's direction</td>
                </tr>
                <tr>
                    <td>Sample</td>
                    <td>Sample</td>
                    <td>TEXT</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>A sample of text in the Alphabet's vernacular</td>
                </tr>
                <tr>
                    <td>sample_img</td>
                    <td>URL to image</td>
                    <td>String</td>
                    <td>1-191 characters</td>
                    <td></td>
                    <td></td>
                    <td>Path to sample image containing the alphabet</td>
                </tr>
                <tr>
                    <td>description</td>
                    <td>Description</td>
                    <td>TEXT</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>History of the writing system and a short description of it.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>




