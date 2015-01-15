<div ng-controller="SecondStep" ng-show="stepNum > 1" class="step">
    <label class="label-column">Video starts</label>
    @include('snippet.templates.selectTime')
    <br>
    <label class="label-column">Video ends</label>
    @include('snippet.templates.selectTime')
    <br>
    <label for="videoVolume" class="label-column">Video volume</label>
    <input id="videoVolume" type="text" ng-model="player.volume">
    <button ng-click="toThirdStep()" class="next-step" ng-show="stepNum < 3">Next</button>
</div>