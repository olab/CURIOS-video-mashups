<fieldset class="settings" ng-controller="PlayerSettings">
    <legend>Player settings:</legend>
    {{Form::label('playerName', 'Name:', ['class' => 'ps-label'])}}
    {{Form::text('playerName', '', ['ng-model' => 'player.name'])}}
    <br>
    {{Form::label('height', 'Height:', ['class' => 'ps-label'])}}
    {{Form::text('height', '', ['ng-model' => 'player.height'])}}
    <br>
    {{Form::label('width', 'Width:', ['class' => 'ps-label'])}}
    {{Form::text('width', '', ['ng-model' => 'player.width'])}}
    <br>
    {{Form::label('startTime', 'Start time:', ['class' => 'ps-label'])}}
    {{Form::text('startTime', '', ['ng-model' => 'player.startTime'])}}
    <br>
    {{Form::label('endTime', 'End time:', ['class' => 'ps-label'])}}
    {{Form::text('endTime', '', ['ng-model' => 'player.endTime'])}}
    <br>
    {{Form::label('soundLevel', 'Sound level:', ['class' => 'ps-label'])}}
    {{Form::text('soundLevel', '', ['ng-model' => 'player.soundLevel'])}}
    <br>
    <button type="button" ng-click="updatePlayerSettings()">@{{ player.id != 'new' ? 'Update' : 'Save'}}</button>
    <button type="button" ng-click="deletePlayerSettings()" ng-show="player.id != 'new'">Delete</button>
</fieldset>