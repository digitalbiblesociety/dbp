@extends('layouts.app')

@section('template_title')
    @lang('biblesmanagement.editing-bible', ['name' => $bible->name])
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;" class="border-bottom">
                            @lang('fields.bibles_edit', ['name' => $bible->name])
                            <div class="pull-right">
                                <a href="{{ url('/bibles/' . $bible->id) }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="@lang('fields.bibles_back')">
                                    <i class="fa fa-fw fa-reply" aria-hidden="true"></i>
                                    @lang('fields.bibles_back')
                                </a>
                            </div>
                        </div>
                        <ul class="nav nav-pills nav-fill mt-3">
                            <li class="nav-item"><a class="nav-link active" id="metadata-tab" data-toggle="tab" href="#metadata" role="tab" aria-controls="metadata" aria-selected="true">Home</a></li>
                            <li class="nav-item"><a class="nav-link" id="links-tab" data-toggle="tab" href="#links" role="tab" aria-controls="links" aria-selected="false">Links</a></li>
                            <li class="nav-item"><a class="nav-link" id="translations-tab" data-toggle="tab" href="#translations" role="tab" aria-controls="translations" aria-selected="false">Translations</a></li>
                            <li class="nav-item"><a class="nav-link" id="equivalents-tab" data-toggle="tab" href="#equivalents" role="tab" aria-controls="equivalents" aria-selected="false">Equivalents</a></li>
                            <li class="nav-item"><a class="nav-link" id="organizations-tab" data-toggle="tab" href="#organizations" role="tab" aria-controls="organizations" aria-selected="false">Organizations</a></li>
                            <li class="nav-item"><a class="nav-link" id="translators-tab" data-toggle="tab" href="#translators" role="tab" aria-controls="translators" aria-selected="false">Translators</a></li>
                            <li class="nav-item"><a class="nav-link" id="books-tab" data-toggle="tab" href="#books" role="tab" aria-controls="books" aria-selected="false">Books</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        {!! Form::open(array('route' => ['bibles.update', $bible->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="metadata" role="tabpanel" aria-labelledby="metadata-tab">
                                @include('bibles.management.form_tabs_metadata')
                            </div>
                            <div class="tab-pane fade" id="links" role="tabpanel" aria-labelledby="links-tab">
                                <form-bible-links data-links='{!! str_replace("'","", $bible->links->toJson()) !!}'></form-bible-links>
                            </div>
                            <div class="tab-pane fade" id="translations" role="tabpanel" aria-labelledby="translations-tab">
                                <form-bible-translations data-translations='{!! str_replace("'","", $bible->translations->toJson()) !!}'></form-bible-translations>
                            </div>
                            <div class="tab-pane fade" id="equivalents" role="tabpanel" aria-labelledby="equivalents-tab">
                                <h3>equivalents</h3>
                                <form-bible-equivalents data-translations='{!! str_replace("'","", $bible->equivalents->toJson()) !!}'></form-bible-equivalents>
                            </div>
                            <div class="tab-pane fade" id="organizations" role="tabpanel" aria-labelledby="organizations-tab">
                                <h3>organizations</h3>
                                <form-bible-organizations
                                        data-allOrganization="{!! $organizations->toJson() !!}"
                                        data-organizations='{!! str_replace("'","", $bible->organizations->toJson()) !!}'>
                                </form-bible-organizations>
                            </div>
                            <div class="tab-pane fade" id="translators" role="tabpanel" aria-labelledby="translators-tab">
                                <h3>translators</h3>
                                <form-bible-translators data-translations='{!! str_replace("'","", $bible->translators->toJson()) !!}'></form-bible-translators>
                            </div>
                            <div class="tab-pane fade" id="books" role="tabpanel" aria-labelledby="books-tab">
                                <h3>books</h3>

                                @foreach($books as $group)
                                    <h6 class="text-center m-b-5 font-weight-light">{{ $group->first()->book_group }}</h6>
                                    <div class="row">
                                    @foreach($group as $book)
                                    <div class="col-md-4">
                                        <input class="form-check-input" type="checkbox" value="" id="book_{{ $book->id }}">
                                        <label class="form-check-label" for="book_{{ $book->id }}">{{ $book->name }}</label>
                                    </div>
                                    @endforeach
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-3 mb-2 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_bible__modal_text_confirm_title'), 'data-message' => trans('modals.edit_bible__modal_text_confirm_message'))) !!}
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.modals.modal-save')
    @include('layouts.partials.modals.modal-delete')

@endsection