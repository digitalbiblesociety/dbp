<label>{{ $label }}
<input id="{{ $name }}" list="{{ $name }}s" autocomplete="on" type="text">
<input type="hidden" name="{{ $name }}" id="{{ $name }}-hidden" value="{{ $fileset->name ?? old('set_type') }}">
<datalist id="{{ $name }}s">
    @foreach($list as $id => $value)
        <option data-value="{{ $id }}">{{ $value }}</option>
    @endforeach
</datalist>
</label>