<div ng-controller="SecondStep" ng-show="stepNum > 1" class="step step-second">
    <label class="label-header">Video settings:</label>
    <div class="setting-row">
        <label class="label-column">Starts</label>
        @include('snippet.templates.selectTime', array('model' => 'player.start', 'options' => 'videoTime'))
    </div>
    <div class="setting-row">
        <label class="label-column">Ends</label>
        @include('snippet.templates.selectTime', array('model' => 'player.end', 'options' => 'videoTime'))
    </div>

    <div class="bottom-right">
        <button ng-click="updateVideo()" class="btn-gray">Update</button>
        <button ng-click="toThirdStep()" class="btn-green" ng-show="stepNum < 3">Next</button>
    </div>
</div>