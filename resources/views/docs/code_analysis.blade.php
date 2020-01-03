@extends('layouts.app')

@section('head')
    <style>
        h1 {
            font-size:20px;
            text-align: center;
        }

        h4 {
            text-align: center;
        }

        h1 small {
            font-size:smaller;
            display: block;
        }

        section {
            margin-bottom:25px;
            padding-bottom:25px;
            border-bottom: thin solid #ccc;
        }

        .stat {
            text-align: center;
            margin-bottom:35px;
        }

        .stat span:first-child{
            font-size:16px;
            color:#222;
            display: block;
        }

        .stat span:last-child {
            font-size:24px;
        }

    </style>
@endsection

@section('content')

    <h1>Code Analysis <small>(<?php echo date('F d Y H:i:s.', filemtime(storage_path('app/code_analysis.csv'))) ?>)</small></h1>
    <section class="row">
        <div class="medium-4 columns">
            <canvas id="myChart" width="400" height="300"></canvas>
        </div>
        <div class="medium-8 columns">
            <div class="stat medium-6 columns"><span>Lines of Code</span><span>{{ number_format($analysis['Lines of Code (LOC)']) }}</span></div>
            <div class="stat medium-6 columns"><span>Comment Lines of Code</span><span>{{ number_format($analysis['Comment Lines of Code (CLOC)']) }}</span></div>
            <div class="stat medium-6 columns"><span>Non-Comment Lines of Code</span><span>{{ number_format($analysis['Non-Comment Lines of Code (NCLOC)']) }}</span></div>
            <div class="stat medium-6 columns"><span>Logical Lines of Code</span><span>{{ number_format($analysis['Logical Lines of Code (LLOC)']) }}</span></div>
        </div>
    </section>
    <div class="row">
        <div class="stat medium-4 columns"><span>Directories</span><span>{{ $analysis['Directories'] }}</span></div>
        <div class="stat medium-4 columns"><span>Files</span><span>{{ $analysis['Files'] }}</span></div>
        <h4>Classes</h4>
        <div class="stat medium-4 columns"><span>Classes</span><span>{{ $analysis['Classes'] }}</span></div>
        <div class="stat medium-4 columns"><span>Abstract Classes</span><span>{{ $analysis['Abstract Classes'] }}</span></div>
        <div class="stat medium-4 columns"><span>Concrete Classes</span><span>{{ $analysis['Concrete Classes'] }}</span></div>
        <div class="stat medium-4 columns"><span>Classes Length (LLOC)</span><span>{{ $analysis['Classes Length (LLOC)'] }}</span></div>
    </div>
    <div class="row">
        <div class="stat medium-4 columns"><span>Cyclomatic Complexity / Lines of Code</span><span>{{ $analysis['Cyclomatic Complexity / Lines of Code'] }}</span></div>
    <div class="stat medium-4 columns"><span>Namespaces</span><span>{{ $analysis['Namespaces'] }}</span></div>
    <div class="stat medium-4 columns"><span>Interfaces</span><span>{{ $analysis['Interfaces'] }}</span></div>
    <div class="stat medium-4 columns"><span>Traits</span><span>{{ $analysis['Traits'] }}</span></div>
    <div class="stat medium-4 columns"><span>Methods</span><span>{{ $analysis['Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Non-Static Methods</span><span>{{ $analysis['Non-Static Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Static Methods</span><span>{{ $analysis['Static Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Public Methods</span><span>{{ $analysis['Public Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Non-Public Methods</span><span>{{ $analysis['Non-Public Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Cyclomatic Complexity / Number of Methods</span><span>{{ $analysis['Cyclomatic Complexity / Number of Methods'] }}</span></div>
    <div class="stat medium-4 columns"><span>Functions</span><span>{{ $analysis['Functions'] }}</span></div>
    <div class="stat medium-4 columns"><span>Named Functions</span><span>{{ $analysis['Named Functions'] }}</span></div>
    <div class="stat medium-4 columns"><span>Anonymous Functions</span><span>{{ $analysis['Anonymous Functions'] }}</span></div>
    <div class="stat medium-4 columns"><span>Functions Length (LLOC)</span><span>{{ $analysis['Functions Length (LLOC)'] }}</span></div>
    <div class="stat medium-4 columns"><span>Average Function Length (LLOC)</span><span>{{ $analysis['Average Function Length (LLOC)'] }}</span></div>
    <div class="stat medium-4 columns"><span>Constants</span><span>{{ $analysis['Constants'] }}</span></div>
    <div class="stat medium-4 columns"><span>Global Constants</span><span>{{ $analysis['Global Constants'] }}</span></div>
    <div class="stat medium-4 columns"><span>Class Constants</span><span>{{ $analysis['Class Constants'] }}</span></div>
    <div class="stat medium-4 columns"><span>Attribute Accesses</span><span>{{ $analysis['Attribute Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Non-Static Attribute Accesses</span><span>{{ $analysis['Non-Static Attribute Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Static Attribute Accesses</span><span>{{ $analysis['Static Attribute Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Method Calls</span><span>{{ $analysis['Method Calls'] }}</span></div>
    <div class="stat medium-4 columns"><span>Non-Static Method Calls</span><span>{{ $analysis['Non-Static Method Calls'] }}</span></div>
    <div class="stat medium-4 columns"><span>Static Method Calls</span><span>{{ $analysis['Static Method Calls'] }}</span></div>
    <div class="stat medium-4 columns"><span>Global Accesses</span><span>{{ $analysis['Global Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Global Variable Accesses</span><span>{{ $analysis['Global Variable Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Super-Global Variable Accesses</span><span>{{ $analysis['Super-Global Variable Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Global Constant Accesses</span><span>{{ $analysis['Global Constant Accesses'] }}</span></div>
    <div class="stat medium-4 columns"><span>Test Classes</span><span>{{ $analysis['Test Classes'] }}</span></div>
    <div class="stat medium-4 columns"><span>Test Methods</span><span>{{ $analysis['Test Methods'] }}</span></div>
    </div>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
		var ctx = document.getElementById("myChart").getContext('2d');
		// For a pie chart
		var myPieChart = new Chart(ctx,{
			type: 'pie',
			data: {
				datasets: [
					{
						data: [
                            {{ $analysis['Comment Lines of Code (CLOC)'] }},
                            {{ $analysis['Non-Comment Lines of Code (NCLOC)'] }},
                            {{ $analysis['Logical Lines of Code (LLOC)'] }}
                        ],
						backgroundColor: [
							'#ff6384',
							'#36a2eb',
							'#cc65fe',
						]
					}
                ],
				labels: ['Commented Lines of Code', 'Non-Comment Lines of Code', 'Logical Lines of Code'],
				backgroundColor: 'rgb(0, 99, 132)',
				borderColor: 'rgb(0, 99, 132)'
			},
			options: {
				color: [
					'rgb(0, 240, 0)',
					'rgb(0, 0, 240)',
					'rgb(240, 0, 0)',
				]
            }
		});
    </script>
@endsection