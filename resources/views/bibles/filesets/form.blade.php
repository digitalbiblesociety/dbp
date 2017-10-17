<div class="row">
    <div class="medium-6 columns card card-file">
        <input type="file" name="file" id="file" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
        <label for="file" class="centered"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose a file&hellip;</span></label>
    </div>
    <div class="medium-6 columns">
        @include('layouts.partials.datalist', ['name' => 'bible', 'list' => $bibles,])
        <label>Fileset ID<input type="text" name="id" value="{{ $fileset->id ?? old('id') }}" /></label>
        <label>Variation ID<input type="text" name="variation_id" value="{{ $fileset->variation_id ?? old('variation_id') }}" /></label>
        <label>Name <input type="text" name="name" value="{{ $fileset->name ?? old('name') }}" /></label>
        <label>Set Type <input type="text" name="set_type" value="{{ $fileset->set_type ?? old('set_type') }}"></label>
        <label>DBL Package <input type="radio" name="input_type"></label>
        <label>Audio Collection <input type="radio" name="input_type"></label>
        <input class="button expanded" type="submit">
    </div>
</div>