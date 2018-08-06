<div class="form-group has-feedback row {{ $errors->has($name) ? ' has-error ' : '' }}">
    <label class="col-md-3 control-label" for="{{ $name }}">{{ trans('forms.create_label_'.$name) }}</label>
    <div class="col-md-9">
        <div class="input-group">

            @switch($type)
                @case("text")
                    {!! Form::text($name, $object[$name], ['id' => $name, 'class' => 'form-control', 'placeholder' => trans('fields.'.$name)]) !!}
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
</div>