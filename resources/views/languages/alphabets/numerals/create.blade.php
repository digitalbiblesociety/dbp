@extends('layouts.app')

@section('head')

    <style>
        .tag {
            padding:5px;
            font-size:12px;
            font-weight: normal;
            color:#FFF;
        }

        .tag.unique {
            background: #48B;
        }

        .tag.required {
            background-color: #844;
        }

        .field-definitions .card {
            min-height:250px;
        }

    </style>

@endsection

@section('content')

    <section class="row">
        <h3>Create Numbers</h3>
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#fields" aria-selected="true">Single Creation</a></li>
            <li class="tabs-title"><a data-tabs-target="field_descriptions" href="#field_descriptions">Field Descriptions</a></li>
        </ul>
    </section>

    <form action="/numbers" method="POST" class="row">
        {{ csrf_field() }}
        @include('languages.alphabets.numerals.form')
    </form>


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