@extends('layouts.app')

@section('head')
    <style>
        [type="file"] {
            visibility: hidden;
        }

        .js .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .inputfile + label {
            max-width: 300px;
            text-align: center;
            font-size: 1.25rem;
            /* 20px */
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            display: block;
            overflow: hidden;
            padding: 5rem 1.25rem;
            /* 10px 20px */
        }

        .no-js .inputfile + label {
            display: none;
        }

        .inputfile:focus + label,
        .inputfile.has-focus + label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

        .inputfile + label * {
            /* pointer-events: none; */
            /* in case of FastClick lib use */
        }

        .inputfile + label svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
            fill: currentColor;
            margin-top: -0.25em;
            /* 4px */
            margin-right: 0.25em;
            /* 4px */
        }

        /* style 4 */

        .inputfile-4 + label {
            color: #00b09b;
        }

        .inputfile-4:focus + label,
        .inputfile-4.has-focus + label,
        .inputfile-4 + label:hover {
            color: #278b80;
        }

        .inputfile-4 + label figure {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #00b09b;
            display: block;
            padding: 20px;
            margin: 0 auto 10px;
        }

        .inputfile-4:focus + label figure,
        .inputfile-4.has-focus + label figure,
        .inputfile-4 + label:hover figure {
            background-color: #278b80;
        }

        .inputfile-4 + label svg {
            width: 100%;
            height: 100%;
            fill: #f1e5e6;
        }

        .card-file {
            background-color:#f1f1f1;
        }
    </style>
@endsection

@section('content')
    @include('layouts.partials.banner', ['title' => "Edit Fileset for ".$fileset->bible->currentTranslation->name])
    <form id="fileset" class="medium-8 columns centered" method="POST" action="/bibles/filesets" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @include('bibles.filesets.form')
    </form>

@endsection


@section('footer')
    <script>
        ;( function ( document, window, index )
        {
            var inputs = document.querySelectorAll( '.inputfile' );
            Array.prototype.forEach.call( inputs, function( input )
            {
                var label	 = input.nextElementSibling,
                    labelVal = label.innerHTML;

                input.addEventListener( 'change', function( e )
                {
                    var fileName = '';
                    if( this.files && this.files.length > 1 )
                        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                    else
                        fileName = e.target.value.split( '\\' ).pop();

                    if( fileName )
                        label.querySelector( 'span' ).innerHTML = fileName;
                    else
                        label.innerHTML = labelVal;
                });

                // Firefox bug fix
                input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
                input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
            });
        }( document, window, 0 ));
    </script>
@endsection