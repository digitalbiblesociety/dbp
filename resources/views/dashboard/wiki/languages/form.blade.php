{{ csrf_field() }}
<div class="container">
    <div class="columns is-multiline">
        <div class="column is-4 box">
            <label>iso <input class="input" type="text" name="iso" minlength="3" maxlength="3" value="{{ $language->iso ?? old('iso') }}" /></label>
            <label>Glotto id <input class="input" type="text" name="glotto_id" minlength="8" maxlength="8" value="{{ $language->glotto_id ?? old('glotto_id') }}" /></label>
        </div>
        <!-- Include language_code Creator -->
        <div class="column is-4">
            <label>name <input required class="input" type="text" name="name" maxlength="191" value="{{ $language->name ?? old('name') }}" /></label>
        </div>
        <!-- Include language_translations Creator -->
        <div class="column is-4">
            <label>maps <input class="input" type="text" name="maps" maxlength="191" value="{{ $language->maps ?? old('maps') }}" /></label>
        </div>
        <div class="column is-4">
            <label>development <textarea class="textarea" name="development">{{ $language->level ?? old('level') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>use <textarea class="textarea" type="text" name="use">{{ $language->use ?? old('use') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>Population <input class="input" type="number" name="population" value="{{ $language->population ?? old('population') }}" /></label>
            <label>Population Notes <textarea class="textarea" type="text" name="population_notes">{{ $language->population_notes ?? old('population_notes') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>notes <textarea class="textarea" type="text" name="notes">{{ $language->notes ?? old('notes') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>typology <textarea class="textarea" type="text" name="typology">{{ $language->typology ?? old('typology') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>writing <textarea class="textarea" type="text" name="writing">{{ $language->writing ?? old('writing') }}</textarea></label>
        </div>
        <div class="column is-4">
            <label>latitude <input class="input" type="number" step="0.01" placeholder="00.000000" name="latitude" value="{{ $language->latitude ?? old('latitude') }}" /></label>
        </div>
        <div class="column is-4">
            <label>longitude <input class="input" type="number" step="0.01" placeholder="00.000000" name="longitude" value="{{ $language->longitude ?? old('longitude') }}" /></label>
        </div>
        <div class="column is-4">
            <select class="select">
                <option @if(isset($language)) @if($language->status_id === '0') selected @endif @endif value="0">International</option>
                <option @if(isset($language)) @if($language->status_id === '1') selected @endif @endif value="1">National</option>
                <option @if(isset($language)) @if($language->status_id === '2') selected @endif @endif value="2">Provincial</option>
                <option @if(isset($language)) @if($language->status_id === '3') selected @endif @endif value="3">Wider</option>
                <option @if(isset($language)) @if($language->status_id === '4') selected @endif @endif value="4">Educational</option>
                <option @if(isset($language)) @if($language->status_id === '5') selected @endif @endif value="5">Developing</option>
                <option @if(isset($language)) @if($language->status_id === '6a') selected @endif @endif value="6a">Vigorous</option>
                <option @if(isset($language)) @if($language->status_id === '6b') selected @endif @endif value="6b">Threatened</option>
                <option @if(isset($language)) @if($language->status_id === '7') selected @endif @endif value="7">Shifting</option>
                <option @if(isset($language)) @if($language->status_id === '8a') selected @endif @endif value="8a">Moribund</option>
                <option @if(isset($language)) @if($language->status_id === '8b') selected @endif @endif value="8b">Nearly Extinct</option>
                <option @if(isset($language)) @if($language->status_id === '9') selected @endif @endif value="9">Dormant</option>
                <option @if(isset($language)) @if($language->status_id === '10') selected @endif @endif value="10">Extinct</option>
            </select>
            <label>status <input class="input" type="text" name="status_id" value="{{ $language->status_id ?? old('status_id') }}" /></label>
        </div>
        <div class="column is-4">
            <input type="submit" class="button">
        </div>
    </div>
</div>