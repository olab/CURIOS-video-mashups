<!doctype html>
<html ng-app="player">
<head>
    <meta charset="UTF-8">
    <title>Player</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    {{HTML::style('css/snippet.css')}}
    {{HTML::script('js/angular/angular.min.js')}}
    {{HTML::style('css/common.css')}}
</head>
<body>
<h1>
    CURIOS video mashup service | Create user
    <a href="{{URL::to('/player/cabinet')}}" id="btn-logout">Cabinet</a>
</h1>

<div class="body-bl">
    @if($errors->any())
        <h4>{{$errors->first()}}</h4>
    @endif
    <form class="step step-profile" action="<?php echo URL::to('/player/createProfile'); ?>" method="post">
        <div class="setting-row">
            <label for="user-name" class="label-column">Email:</label>
            <input id="user-name" type="text" name="user-name">
        </div>

        <div class="setting-row">
            <label for="password" class="label-column">Password:</label>
            <input id="password" type="password" name="password">
        </div>

        <div class="setting-row">
            <label for="password-submit" class="label-column">Password submit:</label>
            <input id="password-submit" type="password" name="password-submit">
        </div>

        <div class="setting-row">
            <label for="password-submit" class="label-column">Status:</label>

            {{-- activate by css, don't change id and place of input and label --}}
            <input id="ps-author" type="radio" name="status" value="author" checked>
            <label for="ps-author" class="btn-gray">Author</label>

            {{-- activate by css, don't change id and place of input and label --}}
            <input id="ps-super" type="radio" name="status" value="superuser">
            <label for="ps-super" class="btn-gray">SuperUser</label>
        </div>

        <input class="btn-green" type="submit" value="Create">
    </form>
</div>
</body>
</html>