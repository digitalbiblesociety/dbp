@extends('layouts.app')

@section('head')
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    <script type="text/javascript">
        window.onload = function() {
            CanvasJS.addColorSet("progressShades",
                [
                    "#DDDD9D",
                    "#FF6138",
                    "#BEEB9F",
                    "#79BD8F"
                ]);
            $("#chartContainer").CanvasJSChart({
                title: {
                    text: "Progress for DBP v2 as of Today",
                    fontSize: 24
                },
                colorSet: "progressShades",
                axisY: {
                    title: "Products in %"
                },
                legend :{
                    verticalAlign: "center",
                    horizontalAlign: "right"
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        toolTipContent: "{label} <br/> {y} %",
                        indexLabel: "{y} %",
                        dataPoints: [
                            { label: "Omitted Count",      y: {{ round($progress->omitted_count_percentage) }}, legendText: "Omitted"},
                            { label: "Uncompleted Count",  y: {{ round($progress->uncompleted_count_percentage) }}, legendText: "Uncompleted"  },
                            { label: "Static Count",       y: {{ round($progress->static_count_percentage) }},  legendText: "Static" },
                            { label: "Supported Count",    y: {{ round($progress->supported_count_percentage) }},  legendText: "Supported"}
                        ]
                    }
                ]
            });
        }
    </script>
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
    </style>
@endsection

@section('content')
    <div class="medium-4 columns centered">
        <div id="chartContainer" style="width: 100%; height: 300px"></div>
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