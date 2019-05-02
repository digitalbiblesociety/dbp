@extends('layouts.app')

@section('head')
    <style>
        .table {
            font-size:12px;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Organization Resources'
    ])

        @include('validations.validate-nav')

        <table class="table" width="100%">
            <thead>
            <tr>
                <td>Org ID</td>
                <td>slug</td>
                <td>Name</td>
                <td>Logos</td>
                <td>Relationships</td>
                <td>Filesets</td>
                <td>Resources</td>
                <td>Total</td>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
                <tr>
                    <td>{{ $organization->id }}</td>
                    <td>{{ $organization->slug }}</td>
                    <td>{!! $organization->translations->pluck('name')->implode('<br>') !!}</td>
                    <td>{!! $organization->logos->pluck('url')->implode('<br>') !!}</td>
                    <td>{!! $organization->relationships->pluck('organization_parent_id')->implode('<br>') !!}</td>
                    <td>{{ $organization->filesets_count }}</td>
                    <td>{{ $organization->resources_count }}</td>
                    <td @if(($organization->filesets_count + $organization->resources_count) === 0) style="background-color:#ad2462;color:#FFF" @endif
                    >{{ $organization->filesets_count + $organization->links_count + $organization->resources_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


@endsection