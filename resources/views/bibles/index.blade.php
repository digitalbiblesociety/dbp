@extends('layouts.app')

@section('content')
    <h1 class="text-center">Languages</h1>
    <table>
        <thead></thead>
        <tbody>
        @foreach($languages as $language)

        @endforeach
        </tbody>
    </table>
@endsection