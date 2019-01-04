@extends('layouts.app')

@section('head')
    <title>Inbox - Free Bulma template</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <style>
        html,body {
            font-family: 'Open Sans', serif;
            font-size: 14px;
            line-height: 1.5;
            height: 100%;
            background-color: #fff;
        }
        .nav.is-dark {
            background-color: #232B2D;
            color: #F6F7F7;
        }
        .nav.is-dark .nav-item a, .nav.is-dark a.nav-item {
            color: #F6F7F7;
        }
        .nav.is-dark .nav-item a.button.is-default {
            color: #F6F7F7;
            background-color: transparent;
            border-width: 2px;
        }
        .nav.menu {
            border-bottom: 1px solid #e1e1e1;
        }
        .nav.menu .nav-item .icon-btn {
            border: 3px solid #B7C6C9;
            border-radius: 90px;
            padding: 5px 7px;
            color: #B7C6C9;
        }
        .nav.menu .nav-item.is-active .icon-btn {
            color: #2EB398;
            border: 3px solid #2EB398;
        }
        .nav.menu .nav-item .icon-btn .fa {
            font-size: 20px;
            color: #B7C6C9;
        }
        .nav.menu .nav-item.is-active .icon-btn .fa {
            color: #2EB398;
        }
        .aside {
            display:block;
            background-color: #F9F9F9;
            border-right: 1px solid #DEDEDE;
        }
        .messages {
            display:block;
            background-color: #fff;
            border-right: 1px solid #DEDEDE;
        }
        .message {
            display:block;
            background-color: #fff;
        }
        .aside .compose {
            height: 95px;
            margin:0 -10px;
            padding: 25px 30px;
        }
        .aside .compose .button {
            color: #F6F7F7;
        }
        .aside .compose .button .compose {
            font-size: 14px;
            font-weight: 700;
        }
        .aside .main {
            padding: 40px;
            color: #6F7B7E;
        }
        .aside .title {
            color: #6F7B7E;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .aside .main .item {
            display: block;
            padding: 10px 0;
            color: #6F7B7E;
        }
        .aside .main .item.active {
            background-color: #F1F1F1;
            margin: 0 -50px;
            padding-left: 50px;
        }
        .aside .main .item:active,.aside .main .item:hover {
            background-color: #F2F2F2;
            margin: 0 -50px;
            padding-left: 50px;
        }
        .aside .main .icon {
            font-size: 19px;
            padding-right: 30px;
            color: #A0A0A0;
        }
        .aside .main .name {
            font-size: 15px;
            color: #5D5D5D;
            font-weight: 500;
        }
        .messages {
            padding: 40px 20px;
        }
        .message {
            padding: 40px 20px;
        }
        .messages .action-buttons {
            padding: 0;
            margin-top: -20px;
        }
        .message .action-buttons {
            padding: 0;
            margin-top: -5px;
        }
        .action-buttons .control.is-grouped {
            display: inline-block;
            margin-right: 30px;
        }
        .action-buttons .control.is-grouped:last-child {
            margin-right: 0;
        }
        .action-buttons .control.is-grouped .button:first-child {
            border-radius: 5px 0 0 5px;
        }
        .action-buttons .control.is-grouped .button:last-child {
            border-radius: 0 5px 5px 0;
        }
        .action-buttons .control.is-grouped .button {
            margin-right: -5px;
            border-radius: 0;
        }
        .pg {
            display: inline-block;
            top:10px;
        }
        .action-buttons .pg .title {
            display: block;
            margin-top: 0;
            padding-top: 0;
            margin-bottom: 3px;
            font-size:12px;
            color: #AAAAAA;
        }
        .action-buttons .pg a{
            font-size:12px;
            color: #AAAAAA;
            text-decoration: none;
        }
        .is-grouped .button {
            background-image: linear-gradient(#F8F8F8, #F1F1F1);
        }
        .is-grouped .button .fa {
            font-size: 15px;
            color: #AAAAAA;
        }
        .inbox-messages {
            margin-top:60px;
        }
        .message-preview {
            margin-top: 60px;
        }
        .inbox-messages .card {
            width: 100%;
        }
        .inbox-messages strong {
            color: #5D5D5D;
        }
        .inbox-messages .msg-check {
            padding: 0 20px;
        }
        .inbox-messages .msg-subject {
            padding: 10px 0;
            color: #5D5D5D;
        }
        .inbox-messages .msg-attachment {
            float:right;
        }
        .inbox-messages .msg-snippet {
            padding: 5px 20px 0px 5px;
        }
        .inbox-messages .msg-subject .fa {
            font-size: 14px;
            padding:3px 0;
        }
        .inbox-messages .msg-timestamp {
            float: right;
            padding: 0 20px;
            color: #5D5D5D;
        }
        .message-preview .avatar {
            display: inline-block;
        }
        .message-preview .top .address {
            display: inline-block;
            padding: 0 20px;
        }
        .avatar img {
            width: 40px;
            border-radius: 50px;
            border: 2px solid #999;
            padding: 2px;
        }
        .address .name {
            font-size: 16px;
            font-weight: bold;
        }
        .address .email {
            font-weight: bold;
            color: #B6C7D1;
        }
        .card.active {
            background-color:#F5F5F5;
        }
    </style>
@endsection

@section('content')

<div class="columns" id="mail-app">
    <div class="column is-4 messages hero is-fullheight" id="message-feed">
        <div class="action-buttons">
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-chevron-down"></i></a>
                <a class="button is-small"><i class="fa fa-refresh"></i></a>
            </div>
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-inbox"></i></a>
                <a class="button is-small"><i class="fa fa-exclamation-circle"></i></a>
                <a class="button is-small"><i class="fa fa-trash-o"></i></a>
            </div>
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-folder"></i></a>
                <a class="button is-small"><i class="fa fa-tag"></i></a>
            </div>
            <div class="control is-grouped pg">
                <div class="title">@{{ paginate.pointer.start }}-@{{ paginate.pointer.end }} of @{{ paginate.total }}</div>
                <a class="button is-link"><i class="fa fa-chevron-left"></i></a>
                <a class="button is-link"><i class="fa fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="inbox-messages" id="inbox-messages">
            <div v-for="(msg, index) in messages" class="card" v-bind:id="'msg-card-'+index" v-on:click="showMessage(msg,index)" v-bind:data-preview-id="index">
                <div class="card-content">
                    <div class="msg-header">
                        <span class="msg-from"><small>From: @{{ msg.from }}</small></span>
                        <span class="msg-timestamp"></span>
                        <span class="msg-attachment"><i class="fa fa-paperclip"></i></span>
                    </div>
                    <div class="msg-subject">
                        <span class="msg-subject"><strong id="fake-subject-1">@{{ msg.subject }}</strong></span>
                    </div>
                    <div class="msg-snippet">
                        <p id="fake-snippet-1">@{{ msg.snippet }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-6 message hero is-fullheight is-hidden" id="message-pane">
        <div class="action-buttons">
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-inbox"></i></a>
                <a class="button is-small"><i class="fa fa-exclamation-circle"></i></a>
                <a class="button is-small"><i class="fa fa-trash-o"></i></a>
            </div>
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-exclamation-circle"></i></a>
                <a class="button is-small"><i class="fa fa-trash-o"></i></a>
            </div>
            <div class="control is-grouped">
                <a class="button is-small"><i class="fa fa-folder"></i></a>
                <a class="button is-small"><i class="fa fa-tag"></i></a>
            </div>
        </div>
        <div class="box message-preview">
            <div class="top">
                <div class="avatar">
                    <img src="https://placehold.it/128x128">
                </div>
                <div class="address">
                    <div class="name">John Smith</div>
                    <div class="email">someone@gmail.com</div>
                </div>
                <hr>
                <div class="content">
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                <strong>Bulma Templates</strong> by <a href="https://github.com/dansup">Daniel Supernault</a>. The source code is licensed
                <a href="http://opensource.org/licenses/mit-license.php">MIT</a>.
            </p>
            <p>
                <a class="icon" href="https://github.com/dansup/bulma-templates">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>
@endsection