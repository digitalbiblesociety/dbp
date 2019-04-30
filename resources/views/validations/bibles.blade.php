@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Bible Resources'
    ])

    <div class="container">

    @include('validations.validate-nav')

    <table width="100%" class="table">
        <thead>
            <tr>
               <td>Bible ID</td>
               <td>Lang (unofficial)</td>
               <td>Titles</td>
               <td>Hash ID</td>
               <td>Fileset ID</td>
               <td>Type</td>
               <td>bucketID</td>
               <td>Copyright Info</td>
               <td>Organizations</td>
               <td>Access Groups</td>
            </tr>
        </thead>
        <tbody>
            @foreach($bibles as $bible)
                <tr @if(($bible->filesets->count() + $bible->links_count) === 0) style="background-color:#ad2462;color:#FFF" @endif>
                    <td rowspan="{{ $bible->filesets->count() }}">{{ $bible->id ?? '' }}</td>
                    <td rowspan="{{ $bible->filesets->count() }}">{{ $bible->language_id ?? '' }}</td>
                    <td rowspan="{{ $bible->filesets->count() }}">{{ $bible->translations->pluck('name')->implode(',') }}</td>
                    <td>{{ $bible->filesets->first()->hash_id ?? '' }}</td>
                    <td>{{ $bible->filesets->first()->id ?? '' }}</td>
                    <td>{{ $bible->filesets->first()->set_type_code ?? '' }}</td>
                    <td>{{ $bible->filesets->first()->asset_id ?? '' }}</td>
                    <td>{{ optional($bible->filesets->first()->copyright)->copyright ?? '' }}</td>
                    <td>{{ optional($bible->filesets->first()->organization)->pluck('slug')->implode(', ') ?? '' }}</td>
                    <td>{{ optional($bible->filesets->first()->permissions)->pluck('access_group_id')->implode(', ') }}</td>
                </tr>
                @foreach($bible->filesets as $fileset)

                    @if($loop->first)
                        @continue
                    @endif()

                    <tr>
                        <td>{{ $fileset->hash_id ?? '' }}</td>
                        <td>{{ $fileset->id ?? '' }}</td>
                        <td>{{ $fileset->set_type_code ?? '' }}</td>
                        <td>{{ $fileset->asset_id ?? '' }}</td>
                        <td>{{ optional($fileset->copyright)->copyright ?? '' }}</td>
                        <td>{{ optional($fileset->organization)->pluck('slug')->implode(', ') ?? '' }}</td>
                        <td>{{ optional($bible->filesets->first()->permissions)->pluck('access_group_id')->implode(', ') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    @foreach($bibles as $bible)
        @if(($bible->filesets_count + $bible->links_count) === 0)
            {{ $bible->id }},
        @endif
    @endforeach
    </div>

@endsection