@extends('layouts.app')


@section('content')


    @include('layouts.partials.banner', [
        'title'     => 'Log Viewer',
        'subtitle'  => '',
        'breadcrumbs' => $files
    ])

<div class="container">


            @if ($logs === null)
                <div>
                    Log file >50M, please download it.
                </div>
            @else
                <table id="table-log" class="table">
                    <thead>
                    <tr>
                        <th>Level</th>
                        <th>Context</th>
                        <th>Date</th>
                        <th>Content</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($logs as $key => $log)
                        <tr data-display="stack{{$key}}">
                            <td class="{{$log['level_class']}}">{{$log['level']}}</td>
                            <td class="text">{{$log['context']}}</td>
                            <td class="date">{{$log['date']}}</td>
                            <td class="text">
                                @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs"
                                                       data-display="stack{{$key}}"><span
                                            class="glyphicon glyphicon-search"></span></a>@endif
                                {{$log['text']}}
                                @if (isset($log['in_file'])) <br/>{{$log['in_file']}}@endif
                                @if ($log['stack'])
                                    <div class="stack" id="stack{{$key}}"
                                         style="display: none; white-space: pre-wrap;">{{ trim($log['stack']) }}
                                    </div>@endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @endif
            <div>
            </div>


</div>
@endsection

@section('footer')
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>
	$(document).ready(function () {
		$('.table-container tr').on('click', function () {
			$('#' + $(this).data('display')).toggle();
		});
		$('#table-log').DataTable({
			"order": [1, 'desc'],
			"stateSave": true,
			"stateSaveCallback": function (settings, data) {
				window.localStorage.setItem("datatable", JSON.stringify(data));
			},
			"stateLoadCallback": function (settings) {
				var data = JSON.parse(window.localStorage.getItem("datatable"));
				if (data) data.start = 0;
				return data;
			}
		});
		$('#delete-log, #delete-all-log').click(function () {
			return confirm('Are you sure?');
		});
	});
</script>
@endsection