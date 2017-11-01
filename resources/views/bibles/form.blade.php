{{-- Title Group --}}

<div class="row">
    <div class="medium-3 columns">
        <label>Abbreviation <input name="id" type="text" placeholder="Abbreviation" value="{{ (isset($bible->id)) ? $bible->id : old('id') }}"></label>
        <label>Year Published <input type="number" name="date" min="1900" max="{{ date('Y') }}" step="1" placeholder="Date" value="{{  (isset($bible->date)) ? $bible->date : old('date') }}" /></label>
        <label>Language <input type="text" name="iso" list="languages" aria-describedby="languagesHelpText" value="{{  (isset($bible->language)) ? $bible->language->iso : old('iso') }}" required></label>
        <label>Script
            <select name="script">
                @foreach($alphabets as $alphabet)
                    <option
                        @isset($bible)
                            @if($alphabet->script == $bible->script) selected @endif
                        @endisset
                    value="{{ $alphabet->script }}">{{ $alphabet->script }}</option>
                @endforeach
            </select>
        </label>
        <label>Derived <input name="derived" value="{{ (isset($bible->derived)) ?  $bible->derived : old('derived') }}" type="text" placeholder="Derived"></label>
        <label>Scope
            <select name="portions">
                <option @isset($bible) @if($bible->scrope == "FBA") selected @endif @endisset value="FBA">Bible & Apocrypha</option>
                <option @isset($bible) @if($bible->scrope == "FB") selected @endif @endisset value="FB">Full Bible</option>
                <option @isset($bible) @if($bible->scrope == "NTP") selected @endif @endisset value="NTP">New Testament & Portions</option>
                <option @isset($bible) @if($bible->scrope == "NT") selected @endif @endisset value="NT">Portions</option>
            </select>
        </label>
        <label>Copyright
            <select name="copyright">
                <option @isset($bible) @if($bible->copyright == "PD") selected @endif @endisset value="PD">Public Domain</option>
                <option @isset($bible) @if($bible->copyright == "CC BY-SA") selected @endif @endisset value="CC">Creative Commons BY-SA</option>
                <option @isset($bible) @if($bible->copyright == "CC BY-SA") selected @endif @endisset value="CC">Creative Commons BY-ND</option>
                <option @isset($bible) @if($bible->copyright == "CC BY-NC-DN") selected @endif @endisset value="CC">Creative Commons BY-NC-DN</option>
                <option @isset($bible) @if($bible->copyright == "CR") selected @endif @endisset value="CR">All rights reserved</option>
                <option @isset($bible) @if($bible->copyright == "") selected @endif @endisset value="CR">Other</option>
            </select>
        </label>
        <label>Copyright Description
            <textarea name="copyright_description">@isset($bible) {{ $bible->copyright_description ?? ""}} @endisset</textarea>
        </label>
        <label>Translators <small>(Comma Separated)</small><textarea name="translators">@isset($bible) @if($bible->translators) {{ $bible->translators }} @endif @endisset</textarea></label>
        <label>Notes <textarea name="description">@isset($bible) {{ $bible->description }} @endisset</textarea></label>
        <label>In Progress <input name="in_progress" type="checkbox" /></label>
        <button class="button" type="submit">Save Bible</button>
    </div>

    <div class="medium-9 columns">

<fieldset class="text-center callout">
    <legend>Titles</legend>

    <div class="title-group">
	    <?php $translation_key = 0 ?>
        @isset($bible)
            @foreach($bible->translations as $key => $translation)
                <div class="clonedInput row" data-type="title">
                        <label class="medium-2 columns">Clone/Remove
                            <div class="button-group expanded columns">
                                <a tabindex="0" class="clone button expanded">+</a>
                                <a tabindex="0" class="remove button expanded alert">-</a>
                            </div>
                        </label>
                        <label class="medium-4 columns">Language <input type="text" name="translations[{{ $key }}][iso]" value="{{ $translation->language->iso }}" list="languages" aria-describedby="languagesHelpText" required>
                            <span class="form-error">All Titles Require a specific Language</span>
                        </label>
                    <label class="medium-4 columns">Title <input id="title" type="text" name="translations[{{ $key }}][name]" value="{{ $translation->name }}" placeholder="Title"></label>
                    <label class="medium-2 columns">Vernacular <br><input type="radio" name="translations[{{ $key }}][vernacular]" placeholder="Vernacular" @if($translation->vernacular) checked @endif></label>
                    <label class="medium-8 columns medium-centered">Description <textarea name="translations[{{ $key }}][description]">{{ $translation->description }}</textarea></label>
                </div>
                <?php $translation_key = $key + 1; ?>
            @endforeach
        @endisset

            <div class="clonedInput row" data-type="title">
                <label class="medium-2 columns">Clone/Remove
                    <div class="button-group expanded columns">
                        <a tabindex="0" class="clone button expanded">+</a>
                        <a tabindex="0" class="remove button expanded alert">-</a>
                    </div>
                </label>
                <label class="medium-4 columns">Language <input type="text" name="translations[{{ $translation_key }}][iso]" value="" list="languages" aria-describedby="languagesHelpText" required>
                    <span class="form-error">All Titles Require a specific Language</span>
                </label>
                <label class="medium-4 columns">Title <input id="title" type="text" name="translations[{{ $translation_key }}][name]" placeholder="Title"></label>
                <label class="medium-2 columns">Vernacular <br><input type="radio" name="translations[{{ $translation_key }}][vernacular]" placeholder="Vernacular"></label>
                <label class="medium-8 columns medium-centered">Description <textarea name="translations[{{ $translation_key }}][description]"></textarea></label>
            </div>
            <datalist id="languages">
                @foreach($languages as $language)
                    <option value="{{ $language->iso }}">{{ $language->name }}</option>
                @endforeach
            </datalist>

    </div>
    <div class="row"><p class="help-text text-center" id="languagesHelpText">If the Language you are seeking does not show up in the dropdown please see if there's an <a href="/languages">alternative name</a> before <a href="/languages/create">creating a new one</a>.</p></div>
</fieldset>

{{-- Organization Group --}}
<fieldset class="text-center callout">
    <legend>Organizations</legend>
    <div class="organization-group">
        <datalist id="organizations">
            @foreach($organizations as $organization)
                <option value="{{ $organization->organization_id }}">{{ $organization->name }}</option>
            @endforeach
        </datalist>

        @isset($bible)
		    <?php $organization_key = 0 ?>
            @foreach($bible->organizations as $key => $organization)
        <div class="row clonedInput" data-type="organization">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <label class="medium-5 columns">Organization
                <input type="text" name="organizations[{{ $key }}][organization_id]" list="organizations" aria-describedby="organizationsHelpText" required>
            </label>
            <label class="medium-5 columns">Organization
                <select name="organizations[{{ $key }}][relationship_type]">
                    <option  value="publisher">Publisher</option>
                    <option  value="translator">Translator</option>
                    <option  value="sponsor">Sponsor</option>
                    <option  value="contributor">Contributor</option>
                </select>
            </label>
        </div>
            <?php $translation_key = $key + 1; ?>
            @endforeach
        @endisset

            <div class="row clonedInput" data-type="organization">
                <label class="medium-2 columns">Clone/Remove
                    <div class="button-group expanded columns">
                        <a tabindex="0" class="clone button expanded">+</a>
                        <a tabindex="0" class="remove button expanded alert">-</a>
                    </div>
                </label>
                <label class="medium-5 columns">Organization
                    <input type="text" name="organizations[{{ $translation_key }}][organization_id]" list="organizations" aria-describedby="organizationsHelpText" required>
                </label>
                <label class="medium-5 columns">Organization
                    <select name="organizations[{{ $translation_key }}][relationship_type]">
                        <option  value="publisher">Publisher</option>
                        <option  value="translator">Translator</option>
                        <option  value="sponsor">Sponsor</option>
                        <option  value="contributor">Contributor</option>
                    </select>
                </label>
            </div>

    </div>
    <p class="help-text text-center" id="organizationsHelpText">If the Organization you are seeking does not show up in the dropdown please see if there's an <a href="/organizations">alternative name</a> before <a href="/organizations/create">creating a new one</a>.</p>
</fieldset>

<fieldset class="text-center callout">
    <legend>Links</legend>
    <div class="row link-group">
        <div class="clonedInput" data-type="link">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <div class="medium-3 columns"><label>type<input type="text" name="links[1][type]" placeholder="Type"></label></div>
            <div class="medium-3 columns"><label>title<input type="text" name="links[1][title]" placeholder="Title"></label></div>
            <div class="medium-2 columns"><label>links<input type="text" name="links[1][url]" placeholder="URL"></label></div>
            <div class="medium-2 columns"><label>providers<input type="text" name="links[1][provider]" placeholder="providers"></label></div>
        </div>
    </div>
</fieldset>

<fieldset class="text-center callout">
    <legend>Equivalents</legend>
    <div class="equivalent-group">
        <div class="row clonedInput" data-type="equivalent">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <label class="medium-2 columns">Organizations <input type="text" name="equivalents[1][organization_id]" list="organizations">
            <datalist id="organizations">
                @foreach($organizations as $organization)
                    <option value="{{ $organization->name }}" />
                @endforeach
            </datalist></label>
            <label class="medium-3 columns">Code <input type="text" name="equivalents[1][equivalent_id]" placeholder="Code"></label>
            <label class="medium-3 columns">Type <input type="text" name="equivalents[1][type]" placeholder="Type"></label>
            <label class="medium-2 columns">Note <input type="text" name="equivalents[1][note]" placeholder="Type"></label>
        </div>
    </div>
</fieldset>

    </div>

</div>