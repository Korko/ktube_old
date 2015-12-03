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
<a href="https://github.com/Korko/ktube" title="Fork me on GitHub"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/52760788cde945287fbb584134c4cbc2bc36f904/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f77686974655f6666666666662e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png"></a>
@endsection
