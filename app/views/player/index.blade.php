<!doctype html>
<html ng-app="player">
    <head>
        <meta charset="UTF-8">
        <title>Player</title>
        {{HTML::style('css/main.css')}}
        {{HTML::script('js/angular/angular.min.js')}}
        {{HTML::script('js/screen.js')}}
    </head>
    <body ng-controller="Main">

        {{Form::open(['route' => 'player.saveSettings'])}}
            <div>
                {{Form::label('screen-id', 'Your entries:')}}
                <select ng-model="selectedPlayer" ng-options="playerOption.name for playerOption in players" ng-change="changePlayerSettings(selectedPlayer.id)"></select>
                <a id="watchResult" ng-hide="player.id == 'new'" target="_blank" href="/player/embed?slug=%playerId%">Watch result</a>
            </div>

            <div id="youtube"
                style="height: @{{ player.height }}px; width: @{{ player.width }}px;"
                ng-controller="VideoScreen"
                ng-mousedown="focusIn($event)"
                ng-mousemove="dragFocusElement($event)"
                ng-mouseup="focusOut()"
                ng-mouseleave="focusOut()">
                <div style="position: absolute; top: 0; left: 0; width: 100px; height: 100px; background-color: greenyellow;">popup text</div>
                <div style="position: absolute; top: 0; left: 100px; width: 100px; height: 100px; background-color: yellow;">popup text</div>
                <iframe height="315" src="//www.youtube.com/embed/iS0wuN_6wyw" frameborder="0"></iframe>
            </div>

            <svg id="screen"
                ng-attr-height="@{{ player.height }}"
                ng-attr-width="@{{ player.width }}"
                ng-controller="Screen"
                ng-mousedown="clickOnScreen($event)"
                ng-mousemove="dragPopup($event)"
                ng-mouseup="deleteDragPopup()"
                ng-mouseleave="deleteDragPopup()">
            </svg>

            @include('player.settings.player')
            @include('player.settings.popup')
            @include('player.settings.audio')

        {{Form::close()}}

        <label ng-hide="player.id == 'new'">
            <button>Generate link</button>
            <br>
            <textarea readonly><iframe src="http://videoplayer/player/embed?slug=NQ==" width="220px" height="220px"></iframe></textarea>
        </label>
    </body>
</html>