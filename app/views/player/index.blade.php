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
            </div>

            <svg id="screen"
            ng-attr-height="@{{ player.height }}"
            ng-attr-width="@{{ player.width }}"
            ng-controller="Screen"
            ng-mousedown="clickOnScreen($event)"
            ng-mousemove="dragPopup($event)"
            ng-mouseup="deleteDragPopup()"
            ng-mouseleave="deleteDragPopup()">
                <g>
                    <rect height="100" width="100"></rect>
                    <text x="10" y="10" fill="white">Text</text>
                </g>
            </svg>

            @include('player.settings.player')
            @include('player.settings.popup')
            @include('player.settings.audio')

        {{Form::close()}}
    </body>
</html>