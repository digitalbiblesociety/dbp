{{-- Title Group --}}

<div class="row">
    <div class="medium-3 columns">
        <label>Abbreviation <input name="id" type="text" placeholder="Abbreviation"></label>
        <label>Year Published <input type="number" name="date" min="1900" max="{{ date('Y') }}" step="1" placeholder="Date" /></label>
        <label>Language <input type="text" name="iso" list="languages" aria-describedby="languagesHelpText" required>
            <datalist id="languages">
                @foreach($languages as $language)
                    <option value="{{ $language->iso }}">{{ $language->name }}</option>
                @endforeach
            </datalist>
        <label>Script
            <select name="script">
                @foreach($alphabets as $alphabet)
                    <option value="{{ $alphabet->script }}">{{ $alphabet->script }}</option>
                @endforeach
            </select>
        </label>
        <label>Derived <input name="derived" type="text" placeholder="Derived"></label>
        <label>Scope
            <select name="portions">
                <option value="FBA">Bible & Apocrypha</option>
                <option value="FB">Full Bible</option>
                <option value="NTP">New Testament & Portions</option>
                <option value="NT">Portions</option>
            </select>
        </label>
        <label>Copyright
            <select name="copyright">
                <option value="PD">Public Domain</option>
                <option value="CC">Creative Commons</option>
                <option value="CR">Copyright</option>
            </select>
        </label>
        <label>Translators <small>(Comma Separated)</small><textarea name="translators"></textarea></label>
        <label>Notes <textarea name="description"></textarea></label>
        <label>In Progress <input name="in_progress" type="checkbox" /></label>
        <button class="button" type="submit">Save Bible</button>
    </div>

    <div class="medium-9 columns">

<fieldset class="text-center callout">
    <legend>Titles</legend>
    <div class="title-group">
        <div class="clonedInput row" data-type="title">
                <label class="medium-2 columns">Clone/Remove
                    <div class="button-group expanded columns">
                        <a tabindex="0" class="clone button expanded">+</a>
                        <a tabindex="0" class="remove button expanded alert">-</a>
                    </div>
                </label>
                <label class="medium-4 columns">Language <input type="text" name="translations[1][iso]" list="languages" aria-describedby="languagesHelpText" required>
                    <datalist id="languages">
                        @foreach($languages as $language)
                            <option value="{{ $language->iso }}">{{ $language->name }}</option>
                        @endforeach
                    </datalist>
                    <span class="form-error">All Titles Require a specific Language</span>
                </label>
            <label class="medium-4 columns">Title <input id="title" type="text" name="translations[1][name]" placeholder="Title"></label>
            <label class="medium-2 columns">Vernacular <br><input type="radio" name="translations[1][vernacular]" placeholder="Vernacular"></label>
            <label class="medium-8 columns medium-centered">Description <textarea name="translations[1][description]"></textarea></label>
        </div>
    </div>
    <div class="row"><p class="help-text text-center" id="languagesHelpText">If the Language you are seeking does not show up in the dropdown please see if there's an <a href="/languages">alternative name</a> before <a href="/languages/create">creating a new one</a>.</p></div>
</fieldset>

{{-- Organization Group --}}
<fieldset class="text-center callout">
    <legend>Organizations</legend>
    <div class="organization-group">
        <div class="row clonedInput" data-type="organization">
            <label class="medium-2 columns">Clone/Remove
                <div class="button-group expanded columns">
                    <a tabindex="0" class="clone button expanded">+</a>
                    <a tabindex="0" class="remove button expanded alert">-</a>
                </div>
            </label>
            <label class="medium-5 columns">Organization
                <input type="text" name="organizations[1][organization_id]" list="organizations" aria-describedby="organizationsHelpText" required>
                <datalist id="organizations">
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->organization_id }}">{{ $organization->name }}</option>
                    @endforeach
                </datalist>
            </label>
            <label class="medium-5 columns">Organization
                <select name="organizations[1][relationship_type]">
                    <option value="publisher">Publisher</option>
                    <option value="translator">Translator</option>
                    <option value="sponsor">Sponsor</option>
                    <option value="contributor">Contributor</option>
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