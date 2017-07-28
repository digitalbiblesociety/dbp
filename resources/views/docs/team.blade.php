@extends('layouts.app')

@section('head')
    <style>
        #page-banner {
            background-color: #222;
            padding:100px;
            margin-bottom: 50px;
        }


        #logo {
            margin:0 auto;
            display: block;
            width:100px;
            height: auto;
            overflow: visible;
        }
    </style>
@endsection

@section('content')

<section id="page-banner">
<div class="row">
<svg version="1.1" id="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="75 0 75 71">
    <path fill="#D1EAF1" fill-opacity="0" stroke="#f8f8f8" d="M121.5,23c-7-10-18.666-17.833-35-23.5 c15.334,22.667,12.666,44.166-8,64.5C98.5,57.334,112.834,43.666,121.5,23" class="fan_0"></path>
    <path fill="#A5CBE2" fill-opacity="0" stroke="#f8f8f8" d="M138.5,34c-6.666-13.333-15.834-23.666-27.5-31 c0,29.334-10.934,49.666-32.8,61c26-1.666,46.066-11.666,60.2-30" class="fan_1"></path>
    <path fill="#3F87C1" fill-opacity="0" stroke="#f8f8f8" d="M147,40c-2.666-9.333-7.666-19-15-29 c-5.666,31.334-23.334,49-53,53C113.666,62.666,136.334,54.666,147,40" class="fan_2"></path>
    <path fill="#2468B9" fill-opacity="0" stroke="#f8f8f8" d="M150,59c0-3.867-0.5-7.367-1.5-10.5 c-14.666,10-38.066,15.166-70.2,15.5c20,0.334,43.934-1.334,71.8-5" class="fan_3"></path>
    <path fill="#7AA4D6" fill-opacity="0" stroke="#f8f8f8" d="M81,65c-3.314,0-6,1.343-6,3 c0,1.398,1.914,2.568,4.5,2.902V71H142v-0.041c4.327-0.217,7.673-1.355,7.968-2.759H150V65H81z" class="bottom_0"></path>
    <path fill="#9BC0DD" fill-opacity="0" stroke="#f8f8f8" d="M133.3,68.5A1.5,1.5 0,1,1 136.3,68.5A1.5,1.5 0,1,1 133.3,68.5" class="bottom_1"></path>
    <path fill="#9BC0DD" fill-opacity="0" stroke="#f8f8f8" width="19.5" height="2" d="M110.8 67.8 L130.3 67.8 L130.3 69.8 L110.8 69.8 Z" class="bottom_2"></path>
    <style>

        .fan_0,
        .fan_1,
        .fan_2,
        .fan_3,
        .bottom_0,
        .bottom_1,
        .bottom_2 {animation:fan_draw 700ms linear 0ms forwards}

        .fan_0        {stroke-dasharray:177 179;stroke-dashoffset:178}
        .fan_1        {stroke-dasharray:185 187;stroke-dashoffset:186}
        .fan_2        {stroke-dasharray:189 191;stroke-dashoffset:190}
        .fan_3        {stroke-dasharray:156 158;stroke-dashoffset:157}
        .bottom_0     {stroke-dasharray:157 159;stroke-dashoffset:158}
        .bottom_1     {stroke-dasharray:10 12;stroke-dashoffset:11}
        .bottom_2     {stroke-dasharray:43 45;stroke-dashoffset:44}

        @keyframes fan_draw {
                100% {
                    stroke-dashoffset:0;
                    fill-opacity: 1;
                    stroke-opacity: 0;

                }
            }
    </style>
</svg>
</div>
</section>


<div class="row">
    <p>
        The Koinos project is developed by the <a href="https://dbs.org">Digital Bible Society</a> for <a href="https://faithcomesbyhearing.org">Faith Comes By Hearing</a>.
        The team pulls in people from a number of organizations:
        @foreach($teammates as $teammate)
            <div class="teammate">
                <span class="name">{{ $teammate->name }}</span>
                <span class="role">{{ $teammate->role }}</span>
                <span class="organization">{{ $teammate->organization }}</span>
            </div>
        @endforeach

    </p>
</div>


@endsection