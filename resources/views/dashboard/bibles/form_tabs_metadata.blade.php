
<div class="columns is-multiline">

    <div class="column is-4-desktop">
        <label class="label" for="id">{{ trans('dashboard.bibles_id') }}</label>
        <input class="input" type="text" name="id" value="{{ $bible->id ?? old('id') }}" required>
        @if($errors->has('id')) <span class="help-block"><strong>{{ $errors->first('id') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="iso">Languages</label>
        <v-select name="iso" label="name" :selected='{!! str_replace("'","", $language_current->toJson()) !!}' :options='{!! str_replace("'","", $languages->toJson()) !!}'></v-select>
        @if($errors->has('iso')) <span class="help-block"><strong>{{ $errors->first('iso') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="copyright">{{ trans('dashboard.bibles_copyright') }}</label>
        <input class="input" type="text" name="copyright" value="{{ $bible->copyright ?? old('copyright') }}">
        @if($errors->has('copyright')) <span class="help-block"><strong>{{ $errors->first('copyright') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="date">{{ trans('dashboard.bibles_date') }}</label>
        <input class="input" type="number" min="1000" max="{{ date('Y') }}" name="date" value="{{ $bible->date ?? old('date') }}">
        @if($errors->has('date')) <span class="help-block"><strong>{{ $errors->first('date') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="numeral_system_id">{{ trans('dashboard.bibles_numeral_system_id') }}</label>
        <v-select name="numeral_system_id" :selected='{!! str_replace("'","", $bible->numeral_system_id) !!}' :options='{!! str_replace("'","", $bibles->pluck('numeral_system_id')->unique()->toJson()) !!}'></v-select>
        @if($errors->has('numeral_system_id')) <span class="help-block"><strong>{{ $errors->first('numeral_system_id') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="scope">{{ trans('dashboard.bibles_scope') }}</label>
        <div class="select">
        <select name="scope">
            <option @if($bible->scope === "NT" ) selected @endif value="NT">New Testament</option>
            <option @if($bible->scope === "FBA" ) selected @endif value="C">Full Bible with Apocrypha</option>
            <option @if($bible->scope === "NTOTP" ) selected @endif value="NTOTP">New Testament, Old Testament Portion</option>
            <option @if($bible->scope === "NTP" ) selected @endif value="NTP">New Testament Portion</option>
            <option @if($bible->scope === "NTPOTP" ) selected @endif value="NTPOTP">new Testament Portion, Old Testament Portion</option>
            <option @if($bible->scope === "OT" ) selected @endif value="OT">Old Testament</option>
            <option @if($bible->scope === "OTNTP" ) selected @endif value="OTNTP">Old Testament, New Testament Portion</option>
            <option @if($bible->scope === "OTP" ) selected @endif value="OTP">Old Testament Portion</option>
            <option @if($bible->scope === "P" ) selected @endif value="P">Portion</option>
            <option @if($bible->scope === "S" ) selected @endif value="S">Stories</option>
        </select>
        </div>
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="date">{{ trans('dashboard.bibles_versification') }}</label>
        <div class="select">
            <select name="versification">
                @foreach($bibles->pluck('versification')->unique() as $versification)
                    @if($versification != '')
                        <option @if((optional($bible)->$versification == $versification) ?? (old('versification') == $versification)) selected @endif value="{{ $versification }}">{{ $versification }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        @if($errors->has('versification')) <span class="help-block"><strong>{{ $errors->first('versification') }}</strong></span> @endif
    </div>

    <div class="column is-4-desktop">
        <label class="label" for="priority">{{ trans('forms.create_label_'.'priority') }}</label>
        <input class="input" type="number" name="priority" value="{{ $bible->priority ?? old('priority') }}">
        <small id="priorityHelpBlock" class="has-text-grey">
            Determines on some systems the sort order of Bibles within the same language group.
            A higher `priority` will result in that Bible appearing nearer the top of the list.
            The scale runs 0-10, ties will be ordered alphabetically by the bible title.
        </small>
        @if($errors->has('priority')) <span class="help-block"><strong>{{ $errors->first('priority') }}</strong></span> @endif
    </div>

</div>