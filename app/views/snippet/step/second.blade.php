<div ng-controller="SecondStep" ng-show="stepNum > 1" class="step">
    <label for="videoStart" class="label-column">Video starts</label>
    <input id="videoStart" type="text" ng-model="player.start">
    <br>
    <label for="videoEnd" class="label-column">Video ends</label>
    <input id="videoEnd" type="text" ng-model="player.end">
    <br>
    <label for="videoVolume" class="label-column">Video volume</label>
    <input id="videoVolume" type="text" ng-model="player.volume">
    <button ng-click="toThirdStep()" class="next-step" ng-show="stepNum < 3">Next</button>
</div>