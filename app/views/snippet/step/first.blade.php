<div ng-controller="FirstStep" class="step step-first">
    <label for="videoUrl" class="label-header">Enter video url</label>
    <input id="videoUrl" type="text" ng-model="player.originalUrl" onclick="this.select();">
    <button ng-click="toSecondStep()" class="btn-green" ng-show="stepNum < 2">Next</button>
</div>