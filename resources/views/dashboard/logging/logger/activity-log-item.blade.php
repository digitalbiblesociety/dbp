@extends(config('LaravelLogger.loggerBladeExtended'))

@section('template_title')
    @lang('dashboard.drilldown.title', ['id' => $activity->id])
@endsection

@if(config('LaravelLogger.enableBladeJsPlacement'))
    @section('template_linked_css')
        @include('partials.styles')
    @endsection
@else
    @include('partials.styles')
@endif

@php
    switch (config('LaravelLogger.bootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('LaravelLogger.bootstrapCardClasses')) ? '' : config('LaravelLogger.bootstrapCardClasses'));

    switch ($activity->userType) {
        case trans('dashboard.userTypes.registered'):
            $userTypeClass = 'success';
            break;

        case trans('dashboard.userTypes.crawler'):
            $userTypeClass = 'danger';
            break;

        case trans('dashboard.userTypes.guest'):
        default:
            $userTypeClass = 'warning';
            break;
    }

    switch (strtolower($activity->methodType)) {
        case 'get':
            $methodClass = 'info';
            break;

        case 'post':
            $methodClass = 'primary';
            break;

        case 'put':
            $methodClass = 'caution';
            break;

        case 'delete':
            $methodClass = 'danger';
            break;

        default:
            $methodClass = 'info';
            break;
    }

    $platform       = $userAgentDetails['platform'];
    $browser        = $userAgentDetails['browser'];
    $browserVersion = $userAgentDetails['version'];

    switch ($platform) {

        case 'Windows':
            $platformIcon = 'fa-windows';
            break;

        case 'iPad':
            $platformIcon = 'fa-';
            break;

        case 'iPhone':
            $platformIcon = 'fa-';
            break;

        case 'Macintosh':
            $platformIcon = 'fa-apple';
            break;

        case 'Android':
            $platformIcon = 'fa-android';
            break;

        case 'BlackBerry':
            $platformIcon = 'fa-';
            break;

        case 'Unix':
        case 'Linux':
            $platformIcon = 'fa-linux';
            break;

        default:
            $platformIcon = 'fa-';
            break;
    }

    switch ($browser) {

        case 'Chrome':
            $browserIcon  = 'fa-chrome';
            break;

        case 'Firefox':
            $browserIcon  = 'fa-';
            break;

        case 'Opera':
            $browserIcon  = 'fa-opera';
            break;

        case 'Safari':
            $browserIcon  = 'fa-safari';
            break;

        case 'Internet Explorer':
            $browserIcon  = 'fa-edge';
            break;

        default:
            $browserIcon  = 'fa-';
            break;
    }
@endphp

@section('content')
<div class="container-fluid">

    @if(config('LaravelLogger.enablePackageFlashMessageBlade'))
        @include('partials.form-status')
    @endif

    <div class="panel @if($isClearedEntry) panel-danger @else panel-default @endif">
        <div class="{{ $containerClass }} @if($isClearedEntry) panel-danger @else panel-default @endif">
        <div class="{{ $containerHeaderClass }} @if($isClearedEntry) bg-danger text-white @else @endif" >
            @lang('dashboard.drilldown.title', ['id' => $activity->id])
            <a href="@if($isClearedEntry) {{route('cleared')}} @else {{route('activity')}} @endif" class="btn @if($isClearedEntry) btn-default @else btn-info @endif btn-sm pull-right">
                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                @lang('dashboard.drilldown.buttons.back')
            </a>
        </div>
        <div class="{{ $containerBodyClass }}">
            <div class="row">
                <div class="col-xs-12 col-12">
                    <div class="row">

                        <div class="col-md-6 col-lg-4">
                            <ul class="list_group">
                                <li class="list_group-item @if($isClearedEntry) list_group-item-danger @else active @endif">
                                    @lang('dashboard.drilldown.title-details')
                                </li>
                                <li class="list_group-item">
                                    <dl class="dl-horizontal">
                                        <dt>@lang('dashboard.drilldown.list_group.labels.id')</dt>
                                        <dd>{{$activity->id}}</dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.description')</dt>
                                        <dd>{{$activity->description}}</dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.route')</dt>
                                        <dd>
                                            <a href="@if($activity->route != '/')/@endif{{$activity->route}}">
                                                {{$activity->route}}
                                            </a>
                                        </dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.agent')</dt>
                                        <dd>
                                            <i class="fa {{ $platformIcon }} fa-fw" aria-hidden="true">
                                                <span class="sr-only">
                                                    {{ $platform }}
                                                </span>
                                            </i>
                                            <i class="fa {{ $browserIcon }} fa-fw" aria-hidden="true">
                                                <span class="sr-only">
                                                    {{ $browser }}
                                                </span>
                                            </i>
                                            <sup>
                                                <small>
                                                    {{ $browserVersion }}
                                                </small>
                                            </sup>
                                        </dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.locale')</dt>
                                        <dd>
                                            {{ $langDetails }}
                                        </dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.referer')</dt>
                                        <dd>
                                            <a href="{{ $activity->referer }}">
                                                {{ $activity->referer }}
                                            </a>
                                        </dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.methodType')</dt>
                                        <dd>
                                            <span class="badge badge-{{$methodClass}}">
                                                {{ $activity->methodType }}
                                            </span>
                                        </dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.timePassed')</dt>
                                        <dd>{{$timePassed}}</dd>

                                        <dt>@lang('dashboard.drilldown.list_group.labels.createdAt')</dt>
                                        <dd>{{$activity->created_at}}</dd>

                                    </dl>
                                </li>
                            </ul>
                            <br />
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <ul class="list_group">
                                <li class="list_group-item @if($isClearedEntry) list_group-item-danger @else active @endif">
                                    @lang('dashboard.drilldown.title-ip-details')
                                </li>
                                <li class="list_group-item">
                                    <dl class="dl-horizontal">
                                        <dt>@lang('dashboard.drilldown.list_group.labels.ip')</dt>
                                        <dd>{{$activity->ipAddress}}</dd>
                                        @if($ipAddressDetails)
                                            @foreach($ipAddressDetails as $ipAddressDetailKey => $ipAddressDetailValue)
                                                <dt>{{$ipAddressDetailKey}}</dt>
                                                <dd>{{$ipAddressDetailValue}}</dd>
                                            @endforeach
                                        @else
                                            <p class="text-center disabled">
                                                <br />
                                                Additional Ip Address Data Not Available.
                                            </p>
                                        @endif
                                    </dl>
                                </li>
                            </ul>

                            <br />
                        </div>

                        <div class="col-md-12 col-lg-4">
                            <ul class="list_group">
                                <li class="list_group-item @if($isClearedEntry) list_group-item-danger @else active @endif">
                                    @lang('dashboard.drilldown.title-user-details')
                                </li>
                                <li class="list_group-item">
                                    <dl class="dl-horizontal">
                                        <dt>@lang('dashboard.drilldown.list_group.labels.userType')</dt>
                                        <dd>
                                            <span class="badge badge-{{$userTypeClass}}">
                                                {{$activity->userType}}
                                            </span>
                                        </dd>

                                        @if($userDetails)

                                            <dt>@lang('dashboard.drilldown.list_group.labels.userId')</dt>
                                            <dd>{{$userDetails->id}}</dd>

                                            @if(config('LaravelLogger.rolesEnabled'))

                                                <dt>@lang('dashboard.drilldown.labels.userRoles')</dt>

                                                  @foreach ($userDetails->roles as $user_role)

                                                    @if ($user_role->name == 'User')
                                                      @php $labelClass = 'primary' @endphp

                                                    @elseif ($user_role->name == 'Admin')
                                                      @php $labelClass = 'warning' @endphp

                                                    @elseif ($user_role->name == 'Unverified')
                                                      @php $labelClass = 'danger' @endphp

                                                    @else
                                                      @php $labelClass = 'default' @endphp

                                                    @endif

                                                    <dd>
                                                        <span class="badge badge-{{$labelClass}}">
                                                            {{ $user_role->name }} - @lang('dashboard.drilldown.labels.userLevel') {{ $user_role->level }}
                                                        </span>
                                                    </dd>

                                                  @endforeach

                                            @endif


                                            <dt>@lang('dashboard.drilldown.list_group.labels.userName')</dt>
                                            <dd>{{$userDetails->name}}</dd>

                                            <dt>@lang('dashboard.drilldown.list_group.labels.userEmail')</dt>
                                            <dd>
                                                <a href="mailto:{{$userDetails->email}}">
                                                    {{$userDetails->email}}
                                                </a>
                                            </dd>

                                            @if($userDetails->last_name || $userDetails->first_name)
                                                <dt>@lang('dashboard.drilldown.list_group.labels.userFulltName')</dt>
                                                <dd>{{$userDetails->last_name}}, {{$userDetails->first_name}}</dd>
                                            @endif

                                            @if($userDetails->signup_ip_address)
                                                <dt>@lang('dashboard.drilldown.list_group.labels.userSignupIp')</dt>
                                                <dd>{{$userDetails->signup_ip_address}}</dd>
                                            @endif

                                            <dt>@lang('dashboard.drilldown.list_group.labels.userCreatedAt')</dt>
                                            <dd>{{$userDetails->created_at}}</dd>

                                            <dt>@lang('dashboard.drilldown.list_group.labels.userUpdatedAt')</dt>
                                            <dd>{{$userDetails->updated_at}}</dd>

                                        @endif

                                    </dl>
                                </li>
                            </ul>

                            <br />
                        </div>

                    </div>
                </div>
            </div>

            @if(!$isClearedEntry)
                <div class="row">
                    <div class="col-xs-12 col-12">
                        <ul class="list_group">
                            <li class="list_group-item list_group-item-info">
                                @lang('dashboard.drilldown.title-user-activity')
                                <span class="badge">
                                    {{ $totalUserActivities }} @lang('dashboard.dashboard.subtitle')
                                </span>
                            </li>
                            <li class="list_group-item">
                                @include('logger.partials.activity-table', ['activities' => $userActivities])
                            </li>
                        </ul>
                        <br />
                    </div>
                </div>
            @endif

        </div>
    </div>
  </div>
@endsection

@if(config('LaravelLogger.enableBladeJsPlacement'))
    @section('footer_scripts')
@endif

    @if(config('LaravelLogger.enablejQueryCDN'))
        <script type="text/javascript" src="{{ config('LaravelLogger.JQueryCDN') }}"></script>
    @endif

    @if(config('LaravelLogger.enableBootstrapJsCDN'))
        <script type="text/javascript" src="{{ config('LaravelLogger.bootstrapJsCDN') }}"></script>
    @endif

    @if(config('LaravelLogger.enablePopperJsCDN'))
        <script type="text/javascript" src="{{ config('LaravelLogger.popperJsCDN') }}"></script>
    @endif

    @if(config('LaravelLogger.loggerDatatables'))
        @if (count($activities) > 10)
            @include('scripts.datatables')
        @endif
    @endif

@if(config('LaravelLogger.enableBladeJsPlacement'))
    @endsection
@endif
