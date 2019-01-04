@extends('layouts.app')

@section('head')
    <title>Routing Information</title>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Routing Information',
        'breadcrumbs' => [
            '/'          => trans('about.home'),
            '/dashboard' => 'Dashboard',
            '#'          => 'Routing Information'
        ]
    ])

	<div class="container">
		<div class="row">

            <table class="table">
            	<thead>
            		<tr>
                        <th>URI</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Method</th>
            		</tr>
            	</thead>
            	<tbody>
                    @foreach ($routes as $route)
            			<tr>
                            <td>{{$route->uri}}</td>
                            <td>{{$route->getName()}}</td>
                            <td>{{$route->getPrefix()}}</td>
                            <td>{{$route->getActionMethod()}}</td>
            			</tr>
                    @endforeach
            	</tbody>
            </table>

		</div>
	</div>
@endsection
