@extends('layouts.app')

@section('head')
<style>
    .clonedInput {
        padding:1rem;
        margin:1rem auto;
    }

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
        </ul>
    </div>

    <div class="tabs-content row" data-tabs-content="example-tabs">
        <div class="tabs-panel is-active" id="panel1">

            <form action="/bibles/create" method="POST">
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
    </div>



@endsection

@section('footer')
    <script>
        var regex = /^(.+?)(\d+)$/i;
        var cloneIndex = $(".clonedInput").length;
        function clone() {
            var cloneType = $(this).parents(".clonedInput").attr("data-type");
            $(this).parents(".clonedInput").clone()
                .appendTo("."+cloneType + "-group")
                .attr("data", "clonedInput" + cloneType +  cloneIndex)
                .find("*")
                .each(function() {
                    var id = this.id || "";
                    var match = id.match(regex) || [];
                    if (match.length == 3) {
                        this.id = match[1] + (cloneIndex);
                    }
                })
                .on('click', 'div.clone', clone)
                .on('click', 'div.remove', remove);
            cloneIndex++;
        }
        function remove() {
            $(this).parents(".clonedInput").remove();
        }
        $("div.clone").on("click", clone);
        $("div.remove").on("click", remove);
    </script>
@endsection