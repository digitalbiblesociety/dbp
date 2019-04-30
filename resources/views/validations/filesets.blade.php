@extends('layouts.app')

@section('content')

    @include('validations.validate-nav')

    <small class="has-text-centered mb20">To customize the returned filesets apply the ?days={number} param to the url</small>

    @if($filesets->count() !== 0)
        <table>
            <thead>
                <th>
                    <td>Date</td>
                    <td>Bible Id</td>
                    <td>Hash Id</td>
                    <td>Dam Id</td>
                    <td>Language Id</td>
                    <td>Language Name</td>
                    <td>Media Type</td>
                </th>
            </thead>
            <tbody>
            @foreach($filesets as $fileset)
                <tr>
                    <td>{{ $fileset->date }}</td>
                    <td>{{ $fileset->bible_id }}</td>
                    <td>{{ $fileset->hash_id }}</td>
                    <td>{{ $fileset->dam_id }}</td>
                    <td>{{ $fileset->language_id }}</td>
                    <td>{{ $fileset->language_name }}</td>
                    <td>{{ $fileset->media_type }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <section>
            No Filesets created Or updated in the last {{ $days }} Days
        </section>
    @endif


@endsection