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
    CURIOS video mashup service | Cabinet
    <a href="{{URL::to('/snippet')}}" id="btn-snippet">To snippet</a>
    <a href="{{URL::to('/player/createProfileView')}}" id="btn-create-user">Create new user</a>
</h1>

<div class="body-bl">
    @if($errors->any())
        <h4>{{$errors->first()}}</h4>
    @endif
    <form class="step step-profile" action="<?php echo URL::to('/player/saveProfile'); ?>" method="post">
        <div class="setting-row">
            <label for="user-name" class="label-column">Email:</label>
            <input id="user-name" type="text" name="user-name" value="<?php echo Auth::user()->email; ?>">
        </div>

        <div class="setting-row">
            <label for="new-password" class="label-column">New password:</label>
            <input id="new-password" type="password" name="password">
        </div>

        <div class="setting-row">
            <label for="new-password-submit" class="label-column">Password submit:</label>
            <input id="new-password-submit" type="password" name="password-submit">
        </div>

        <input class="btn-green" type="submit" value="Save">
    </form>
    <form class="step step-profiles-status" action="<?php echo URL::to('/player/editStatusProfiles'); ?>" method="post">
        @foreach($users as $user)
        <div class="setting-row">
            <label class="profiles-column">{{$user->email}}</label>

            {{-- activate by css, don't change id and place of input and label --}}
            <input id="ps-super-{{$user->id}}" type="radio" name="status[{{$user->id}}]" value="superuser" {{($user->status == 'superuser') ? 'checked' : ''}}>
            <label for="ps-super-{{$user->id}}" class="btn-gray">SuperUser</label>

            {{-- activate by css, don't change id and place of input and label --}}
            <input id="ps-author-{{$user->id}}" type="radio" name="status[{{$user->id}}]" value="author" {{($user->status == 'author') ? 'checked' : ''}}>
            <label for="ps-author-{{$user->id}}" class="btn-gray">Author</label>

            <button type="submit" name="delete-user" value="{{$user->id}}" class="btn-gray profile-delete">Delete</button>
        </div>
        @endforeach
        <input class="btn-green" type="submit" value="Save">
    </form>
</div>
</body>
</html>