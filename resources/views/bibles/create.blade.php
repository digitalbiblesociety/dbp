@extends('layouts.app')

@section('head')
<style>
    .tabs-content {
        border:none;
    }

</style>
@endsection

@section('content')

    <h1 class="text-center">Create Bible</h1>

    <div class="row">
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Single Upload</a></li>
            <li class="tabs-title"><a href="#panel2">Bulk Upload</a></li>
            <li class="tabs-title"><a href="#field_descriptions">Field Descriptions</a></li>
        </ul>
    </div>

    <div class="tabs-content row" data-tabs-content="example-tabs">
        <div class="tabs-panel is-active" id="panel1">

            <form action="/bibles" method="POST" data-abide novalidate>
                {{ csrf_field() }}
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

        </div>
        <div class="tabs-panel" id="panel2">
            <form action="/bibles/create" method="POST">
                <input type="hidden" name="bulk" />
                <div class="medium-4 columns">
                    <select name="bulk-type">
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                    </select>
                    <input type="submit" class="button">
                </div>
                <div class="medium-8 columns">
                <textarea id="body" name="body" rows="5"></textarea>
                </div>
            </form>

        </div>
        <div class="tabs-panel" id="field_descriptions">
            @include('layouts.swagger_descriptions', ['schema' => 'Bible'])
        </div>
    </div>



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