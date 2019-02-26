@extends('layouts.app')

@section('head')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Bible Metadata',
        'subtitle'  => ''
    ])

    <div class="columns is-centered has-text-centered">
    <section class="is-6 column">
            <table id="bibles-table">
                <thead>
                    <th>bible id</th>
                    <th>name</th>
                </thead>
                <tbody>
                @foreach($bibles as $bible)
                    <tr>
                        <td>{{ $bible->bible_id ?? '' }}</td>
                        <td><a href="/dashboard/bibles/{{ $bible->bible_id }}">{{ $bible->name ?? '' }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </section>

    <section class="is-3 column">
        <label>Or...<Br>
        <a href="{{ route('dashboard.bibles.create') }}" class="button is-primary">Create a New Bible</a></label>
    </section>
    </div>

@endsection


@section('footer')
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
	    $(document).ready(function() {
		    $('#bibles-table').DataTable();
	    });
    </script>
@endsection
