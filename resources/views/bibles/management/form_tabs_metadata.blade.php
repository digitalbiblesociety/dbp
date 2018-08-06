@php $fields = ['id','scope','derived','copyright','in_progress'] @endphp
@foreach($fields as $field)
    @include('layouts.partials.form-input', ['type' => 'text','object' => $bible,'errors' => $errors,'name' => $field])
@endforeach

<div class="form-group has-feedback row {{ $errors->has('date') ? ' has-error ' : '' }}">
    <label class="col-md-3 control-label" for="{{ 'date' }}">{{ trans('forms.create_label_'.'date') }}</label>
    <div class="col-md-9">
        <div class="input-group">
            {!! Form::number('date', $bible['date'], ['id' => 'priority', 'class' => 'form-control', 'placeholder' => trans('fields.'.'date'), 'min'=>1000,'aria-describedby' => 'dateHelpBlock']) !!}
            <div class="input-group-append">
                <label class="input-group-text" for="{{ 'date' }}"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i></label>
            </div>
        </div>
        <small id="dateHelpBlock" class="form-text text-muted">
            The Year the Bible was Originally Published (Any scope sets the default).
        </small>
        @if($errors->has('date')) <span class="help-block"><strong>{{ $errors->first('date') }}</strong></span> @endif
    </div>
</div>

<div class="form-group has-feedback row {{ $errors->has('priority') ? ' has-error ' : '' }}">
    <label class="col-md-3 control-label" for="{{ 'priority' }}">{{ trans('forms.create_label_'.'priority') }}</label>
    <div class="col-md-9">
        <div class="input-group">
            {!! Form::number('priority', $bible['priority'], ['id' => 'priority', 'class' => 'form-control', 'placeholder' => trans('fields.'.'priority'), 'min'=>0,'max'=>10,'aria-describedby' => 'priorityHelpBlock']) !!}
            <div class="input-group-append">
                <label class="input-group-text" for="{{ 'priority' }}"><i class="fa fa-fw fa-sort-amount-desc" aria-hidden="true"></i></label>
            </div>
        </div>
        <small id="priorityHelpBlock" class="form-text text-muted">
            Determines on some systems the sort order of Bibles within the same language group.
            A higher `priority` will result in that Bible appearing nearer the top of the list.
            The scale runs 0-10, ties will be ordered alphabetically by the bible title.
        </small>
        @if($errors->has('priority')) <span class="help-block"><strong>{{ $errors->first('priority') }}</strong></span> @endif
    </div>
</div>

<div class="form-group has-feedback row {{ $errors->has('script') ? ' has-error ' : '' }}">
    <label class="col-md-3 control-label" for="script">{{ trans('forms.create_label_script') }}</label>
    <div class="col-md-9">
        <v-select label="name" :value='{!! str_replace("'","", $alphabets->where('script',$bible->script)->first()->toJson()) !!}' :options='{!! str_replace("'","", $alphabets->toJson()) !!}'></v-select>
        @if($errors->has('script')) <span class="help-block"><strong>{{ $errors->first('script') }}</strong></span> @endif
    </div>
</div>

<div class="form-group has-feedback row {{ $errors->has('language_id') ? ' has-error ' : '' }}">
    <label class="col-md-3 control-label" for="language_id">{{ trans('forms.create_label_language_id') }}</label>
    <div class="col-md-9">
		<?php
		    $currentLanguage = $languages->where('id',$bible->language_id)->first();
		    if($currentLanguage) $currentLanguage = str_replace("'","", $currentLanguage->toJson());
		?>
        <v-select label="name" @if($currentLanguage) :value='{!! $currentLanguage !!}' @endif :options='{!! str_replace("'","", $languages->toJson()) !!}'></v-select>
        @if($errors->has('language_id')) <span class="help-block"><strong>{{ $errors->first('language_id') }}</strong></span> @endif
    </div>
</div>