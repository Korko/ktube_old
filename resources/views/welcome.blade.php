@extends('layout')

@section('headers')
<link href="<< asset('/media/style/cover.css') >>" rel="stylesheet" type="text/css">
@endsection

@section('body')
<div class="site-wrapper">
    <div class="site-wrapper-inner">
        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">kTube</h3>
                    <nav>
                        <ul class="nav masthead-nav">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#">Features</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="inner cover">
                <h1 class="cover-heading">kTube</h1>
                <p class="lead">Easily watch videos from multiple sources.</p>
                <p class="lead">
                    <div class="col-lg-12 text-center" style="font-size:39pt;">
                        <a href="/auth/login/dailymotion" class="disabled"><img src="/media/images/Dailymotion.png" width="100" height="100" /></a>
                        <a href="/auth/login/vimeo" class="disabled"><img src="/media/images/Vimeo.png" width="100" height="100" /></a>
                        <a href="/auth/login/google"><img src="/media/images/Youtube.png" width="100" height="100" /></a>
                    </div>
                </p>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection