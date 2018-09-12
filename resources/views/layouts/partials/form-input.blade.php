 <div class="column is-6-desktop">
        <label class="label" for="{{ $name }}">{{ trans('dashboard.bibles_'.$name) }}</label>
        <div class="input-group">
            @switch($type)
                @case("text")
                    <input class="input" type="text" name="{{ $name }}" id="{{ $name }}" value="{{  }}">
                @break
                @case("v-select")
                    <v-select label="{{ $name }}" :options='{!! str_replace("'","", $object->toJson()) !!}'></v-select>
                @break
            @endswitch
            <div class="input-group-append">
                <label class="input-group-text" for="{{ $name }}"><i class="fa fa-fw" aria-hidden="true"></i></label>
            </div>
        </div>
        @if($errors->has($name)) <span class="help-block"><strong>{{ $errors->first($name) }}</strong></span> @endif
</div>