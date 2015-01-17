<div ng-controller="SecondStep" ng-show="stepNum > 1" class="step">
    <label class="label-column">Video starts</label>
    @include('snippet.templates.selectTime', array('model' => 'player.start', 'options' => 'videoTime'))
    <br>
    <label class="label-column">Video ends</label>
    @include('snippet.templates.selectTime', array('model' => 'player.end', 'options' => 'videoTime'))
    <br>
    <button ng-click="updateVideo()">Update</button>
    <button ng-click="toThirdStep()" class="next-step" ng-show="stepNum < 3">Next</button>
</div>