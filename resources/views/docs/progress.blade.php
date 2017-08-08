@extends('layouts.app')

@section('head')
    <style>
        .function-card {
            position: relative;
            background-color:#fafafa;
            border:thin solid #ccc;
            padding:1rem;
            margin-bottom:1rem;
        }
        .function-card:before {
            position: absolute;
            content:"";
            width:30px;
            height:30px;
            top:0;
            right:0;
            color:#FFF;
            text-align: center;
            line-height:30px;
        }
        .function-card.supported:before {
            background-color:#79BD8F;
            content:"✔";
        }
        .function-card.uncompleted:before {
            background-color:#FF6138;
            content:"X";
            font-weight:bold;
        }
        .function-card.static:before {
            background-color:#BEEB9F;
            content:"-";
            font-weight:bold;
        }
        .function-card.omitted:before {
            background-color:#DDDD9D;
            content:"*";
            font-weight:bold;
        }

        .function-card a[href=""] {
            opacity: .5;
            color:#888;
        }
    </style>
@endsection

@section('content')
    <div class="medium-4 columns centered">
        <canvas id="chartContainer" style="width: 100%; height: 300px"></canvas>
    </div>

    @foreach($progress as $catagory => $items)
    <div class="row">
        <h2>{{ $catagory }}</h2>
        @foreach($items as $title => $item)
            <div class="medium-3 columns">
                <div class="function-card {{ $item->status }}">
                    <h4>{{ $title }}</h4>
                    <p>{{ $item->description }}</p>
                    <div class="row text-center">
                        <div class="medium-6 columns"><a href="{{ $item->url }}">Documentation</a></div>
                        <div class="medium-6 columns"><a href="{{ $item->url_demo }}">Example</a></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach

@endsection

@section('footer')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
    <script>
        var canvas = document.getElementById("chartContainer");
        var ctx = canvas.getContext('2d');

        // Global Options:
        Chart.defaults.global.defaultFontColor = 'black';
        Chart.defaults.global.defaultFontSize = 16;

        var data = {
            labels: [
                "omitted",
                "uncompleted",
                "static",
                "supported"
            ],
            datasets: [
                {
                    fill: true,
                    backgroundColor: [
                        '#DDDD9D',
                        '#FF6138',
                        '#BEEB9F',
                        '#79BD8F'
                    ],
                    data: [
                        {{ round($progress->omitted_count_percentage) }},
                        {{ round($progress->uncompleted_count_percentage) }},
                        {{ round($progress->static_count_percentage) }},
                        {{ round($progress->supported_count_percentage) }}
                    ]
                }
            ]
        };

        // Chart declaration:
        var myBarChart = new Chart(ctx, {
            type: 'pie',
            data: data
        });
    </script>
@endsection