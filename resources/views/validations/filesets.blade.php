@extends('layouts.app')

@section('content')

    @include('validations.validate-nav')

    <small class="has-text-centered mb20">To customize the returned filesets apply the ?days={number} param to the url</small>

    <div class="container">
    @if($filesets->count() !== 0)
        <table class="table" width="100%">
            <thead>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Bible Id</th>
                    <th>Hash Id</th>
                    <th>Fileset Id</th>
                    <th>Language Id</th>
                    <th>Media Type</th>
            </thead>
            <tbody>
            @foreach($filesets as $fileset)
                <tr>
                    <td>{{ $fileset->created_at->diffForHumans() }}</td>
                    <td>{{ $fileset->updated_at->diffForHumans() }}</td>
                    <td>{{ $fileset->bible->first()->id ?? '' }}</td>
                    <td>{{ $fileset->hash_id }}</td>
                    <td>{{ $fileset->id }}</td>
                    <td>{{ $fileset->bible->first()->language_id ?? '' }}</td>
                    <td>{{ $fileset->set_type_code }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <section>
            No Filesets created Or updated in the last {{ $days }} Days
        </section>
    @endif
    </div>


@endsection