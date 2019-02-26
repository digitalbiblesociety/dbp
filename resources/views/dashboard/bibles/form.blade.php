@php

    if($type == 'POST') $route = route('dashboard.bibles.store');
    if($type == 'PUT')  $route = route('dashboard.bibles.update', ['id' => $bible->id]);

@endphp

<form class="box" action="{{ $route }}" method="POST">
    {!! csrf_field() !!}
    @if($type == 'PUT')
        @method('PUT')
    @endif

    <tabs animation="slide" :only-fade="false">
        <tab-pane label="{{ trans('dashboard.metadata') }}" data-shortcut="m">
            @include('dashboard.bibles.form_tabs_metadata')
        </tab-pane>
        <tab-pane label="{{ trans('dashboard.links') }}" data-shortcut="l">
            <form-bible-links data-links='{!! str_replace("'","", $bible->links) !!}'></form-bible-links>
        </tab-pane>
        <tab-pane label="{{ trans('dashboard.translations') }}" data-shortcut="t">
            <form-bible-translations data-translations='{!! str_replace("'","", $bible->translations->toJson()) !!}'></form-bible-translations>
        </tab-pane>
        <tab-pane label="{{ trans('dashboard.filesets') }}" data-shortcut="f"></tab-pane>
        <tab-pane label="{{ trans('dashboard.books') }}" data-shortcut="b">
            <form-bible-books data-books='{!! str_replace("'","", $books->toJson()) !!}'></form-bible-books>
        </tab-pane>
    </tabs>

    <div id="organizations" role="tabpanel" aria-labelledby="organizations-tab">
        {{--
        <form-bible-organizations
                data-allOrganization="{!! $organizations->toJson() !!}"
                data-organizations='{!! str_replace("'","", $bible->organizations->toJson()) !!}'>
        </form-bible-organizations>
        --}}
    </div>
    <div class="columns">
        <div class="is-offset-10 is-2-desktop column">
            <button type="submit" class="button is-primary align-right">Save</button>
        </div>
    </div>
</form>
{{--
<div class="has-text-grey">
    Shortcuts:
    <small>
        <b>Metadata Tab:</b> CTRL+M<br>
        <b>Links Tab:</b> CTRL+L<br>
        <b>Translations Tab:</b> CTRL+T<br>
        <b>Filesets Tab:</b> CTRL+F<br>
        <b>Books Tab:</b> CTRL+B<br>
    </small>
</div>
--}}