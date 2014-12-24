<fieldset class="settings" ng-controller="PopupSettings">
    <legend>Popup settings:</legend>
    <label class="ps-label">Your popups:</label>
    <select ng-model="selectedPopups"></select>{{-- ng-options="playerOption.name for playerOption in players" ng-change="changePlayerSettings(selectedPlayer.id)"--}}
    <br>
    {{Form::label('popupName', 'Start time:', ['class' => 'ps-label'])}}
    {{Form::text('popupName', '', ['ng-model' => 'popup.name'])}}
    <br>
    <label class="ps-label" for="popupType">Type:</label>
    <select id="popupType">
        <option value="rect">Rect</option>
        <option value="other">Under construction</option>
    </select>
    <br>
    <label class="ps-label" for="popupColor">Color:</label>
    <input id="popupColor" type="color" ng-model="popup.color">
    <br>
    {{Form::label('popupHeight', 'Height:', ['class' => 'ps-label'])}}
    {{Form::text('popupHeight', '', ['ng-model' => 'popup.height'])}}
    <br>
    {{Form::label('popupWidth', 'Width:', ['class' => 'ps-label'])}}
    {{Form::text('popupWidth', '', ['ng-model' => 'popup.width'])}}
    <br>
    {{Form::label('popupStartTime', 'Start time:', ['class' => 'ps-label'])}}
    {{Form::text('popupStartTime', '', ['ng-model' => 'popup.startTime'])}}
    <br>
    {{Form::label('popupEndTime', 'End time:', ['class' => 'ps-label'])}}
    {{Form::text('popupEndTime', '', ['ng-model' => 'popup.endTime'])}}
    <br>
    {{Form::label('text', 'Text:', ['class' => 'ps-label'])}}
    {{Form::text('text', '', ['ng-model' => 'popup.text'])}}
    <br>
    <button type="button" ng-click="updatePopup()">@{{(selectedPopup) ? 'Update' : 'Save'}}</button>
    <button type="button" ng-click="addPopup()">Add</button>
    <button type="button" ng-click="deletePopup()" ng-show="selectedPopup">Delete</button>
</fieldset>