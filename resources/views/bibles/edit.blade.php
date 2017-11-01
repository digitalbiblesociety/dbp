@extends('layouts.app')

@section('head')
    <style>
        .tabs-content {
            border:none;
        }

    </style>
@endsection

@section('content')

    <h1 class="text-center">Edit <em>{{ $bible->currentTranslation->name ?? $bible->id }}</em></h1>

    <form action="/bibles/{{ $bible->id }}" method="POST" data-abide novalidate>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @if($errors->any())
            <div data-abide-error class="alert callout">
                <p><i class="fi-alert"></i> There are some errors in your form:</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('bibles.form')
    </form>

    @include('layouts.swagger_descriptions', ['schema' => 'Bible'])

@endsection

@section('footer')
    <script>
        var regex = /^(.+?)\[(\d+)]\[(.+?)]$/i;
        var cloneIndex = $(".clonedInput").length;

        function clone(event) {
            if (event.which === 13 || event.type === 'click') {
                var cloneType = $(this).parents(".clonedInput").attr("data-type");

                $(this).parents(".clonedInput").clone()
                    .appendTo("." + cloneType + "-group")
                    .on('click', '.clone', clone)
                    .on('click', '.remove', remove)
                    .find(':input')
                    .each(function() {
                        var name = this.name || "";
                        var match = name.match(regex) || [];
                        this.name = match[1] + "[" + (cloneIndex) + "][" + match[3] + "]";
                    });
                cloneIndex++;
            }
        }
        function remove(event) {
            if (event.which === 13 || event.type === 'click') {
                $(this).parents(".clonedInput").remove();
            }
        }

        $(".clone").on("click", clone);
        $(".remove").on("click", remove);
    </script>
@endsection