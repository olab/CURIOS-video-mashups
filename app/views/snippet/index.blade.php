<!doctype html>
<html ng-app="player">
    <head>
        <meta charset="UTF-8">
        <title>Player</title>
        {{HTML::style('css/snippet.css')}}
        {{HTML::script('js/angular/angular.min.js')}}
        {{HTML::script('js/snippet.js')}}
    </head>
    <body ng-controller="Main">
        <div class="settings-bl">
            @include('snippet.step.first')
            @include('snippet.step.second')
            @include('snippet.step.third')
            @include('snippet.step.fourth')
            @include('snippet.step.fifth')
        </div>

        <div class="result-bl">
            <div id="editableScreen"
                ng-controller="Screen"
                ng-mousedown="focus($event)"
                ng-mousemove="dragFocusElement($event)"
                ng-mouseup="focusOut()"
                ng-mouseleave="focusOut()">
                <div id="player"></div>
            </div>
            <div id="resultScreen"></div>
        </div>
        <script>
            var baseUrl = '{{URL::to('/')}}';
        </script>
    </body>
</html>