@extends('layouts.app')

@section('head')
    <style>
        .card {
            display: block;
            text-align: center;
        }
        .card small {
            display: block;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <h1 class="text-center">Language Show</h1>
    <div class="row">
    <div class="medium-3 columns">
        <div class="card">
            <span class="card-title">Location & Population</span>
            <p><b>Latitude: </b><small>{{ $language->latitude }}</small></p>
            <p><b>Longitude: </b><small>{{ $language->longitude }}</small></p>
            <p><b>Location: </b> <a href="/countries/{{ $language->country_id }}">{{ $language->maps }}</a></p>
            <span>{{ $language->area }}</span>
            <span>{{ $language->population }}</span>
            <span>{{ $language->population_notes }}</span>
        </div>
    </div>
    <div class="medium-9 columns">
        <div class="card">
            <span class="card-title">Codes & Relations</span>
            <div class="medium-2 columns"><b>id:</b> <small>{{ $language->id }}</small></div>
            <div class="medium-2 columns"><b>glotto_id:</b> <small>{{ $language->glotto_id }}</small></div>
            <div class="medium-2 columns"><b>iso:</b> <small>{{ $language->iso }}</small></div>
            <div class="medium-2 columns"><b>family_pk:</b> <small>{{ $language->family_pk }}</small></div>
            <div class="medium-2 columns"><b>father_pk:</b> <small>{{ $language->father_pk }}</small></div>
            <div class="medium-2 columns"><b>child_dialect_count:</b> <small>{{ $language->child_dialect_count }}</small></div>
            <div class="medium-2 columns"><b>child_family_count:</b> <small>{{ $language->child_family_count }}</small></div>
            <div class="medium-2 columns"><b>child_language_count:</b> <small>{{ $language->child_language_count }}</small></div>
            <div class="medium-2 columns"><b>pk:</b> <small>{{ $language->pk }}</small></div>
        </div>
    </div>
    </div>


    <p><b>name:</b> <small>{{ $language->name }}</small></p>
    <p><b>level:</b> <small>{{ $language->level }}</small></p>
    <p><b>development:</b> <small>{{ $language->development }}</small></p>
    <p><b>use:</b> <small>{{ $language->use }}</small></p>
    <p><b>notes:</b> <small>{{ $language->notes }}</small></p>
    <p><b>typology:</b> <small>{{ $language->typology }}</small></p>
    <p><b>writing:</b> <small>{{ $language->writing }}</small></p>
    <p><b>description:</b> <small>{{ $language->description }}</small></p>
    <p><b>status:</b> <small>{{ $language->status }}</small></p>
    <p><b>scope:</b> <small>{{ $language->scope }}</small></p>
</div>
@endsection

@section('footer')

@endsection