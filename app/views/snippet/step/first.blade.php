<div ng-controller="FirstStep" class="step">
    <label for="videoUrl" class="label-column">Enter video url</label>
    <input id="videoUrl" type="text" ng-model="player.originalUrl">
    <button ng-click="toSecondStep()" class="next-step" ng-show="stepNum < 2">Next</button>
</div>