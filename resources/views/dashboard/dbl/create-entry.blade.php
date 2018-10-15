@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Add new DBL entry',
        'subtitle'  => 'Be careful to check to see if it exists before this operation!',
        'breadcrumbs' => [
            '/'                     => 'Home',
            '/dashboard'            => 'Dashboard',
            '/dashboard/dbl'        => 'Digital Bible Library',
            '/dashboard/dbl/create' => 'Create Entry'
        ],
    ])

    <div class="container">

        <form action="{{ route('dashboard.dbl.store') }}" method="POST">
            {{ csrf_field() }}
            <label>Digital Bible Library ID <input class="input" name="dbl_id"></label>
            <button type="submit" class="button">Save</button>
        </form>

    </div>

@endsection