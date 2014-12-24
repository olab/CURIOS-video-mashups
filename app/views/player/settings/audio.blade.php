<fieldset class="settings" ng-controller="AudioSettings">
    <legend>Audio settings:</legend>
    <div id="audioNote"></div>
    <audio id="audioPlayer" style="display: none;" controls></audio>
    {{Form::label('audioFile', 'Audio:', ['class' => 'ps-label'])}}
    {{Form::file('audioFile')}}
    <br>
    {{Form::label('audioStartTime', 'Start time:', ['class' => 'ps-label'])}}
    {{Form::text('audioStartTime', '', ['ng-model' => 'audio.audioStartTime'])}}
    <br>
    {{Form::label('audioEndTime', 'End time:', ['class' => 'ps-label'])}}
    {{Form::text('audioEndTime', '', ['ng-model' => 'audio.audioEndTime'])}}
    <br>
    {{Form::label('audioVolume', 'Value:', ['class' => 'ps-label'])}}
    {{Form::text('audioVolume', '', ['ng-model' => 'audio.audioVolume'])}}
    <br>
    <button type="button" ng-click="audioUpdate()">Update</button>
    <button type="button" ng-click="audioDelete()" ng-hide="audioPlayerObj.innerHTML == ''">Delete</button>
</fieldset>