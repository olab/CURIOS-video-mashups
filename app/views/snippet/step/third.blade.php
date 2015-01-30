<div ng-controller="ThirdStep" ng-show="stepNum > 2">
    <div class="step-question">
        <label class="label-header">Do you want to use audio?</label>
        <input id="yesAudio" type="radio" name="audio" ng-model="audio.exist" value="yes">
        <label for="yesAudio" class="label-answer">Yes</label>
        <input id="noAudio" type="radio" name="audio" ng-model="audio.exist" value="no" ng-change="toFourthStep()">
        <label for="noAudio" class="label-answer">No</label>
    </div>

    <div ng-show="audio.exist" class="step step-three">
        <button ng-click="uploadAudio()" class="btn-gray">Upload File</button>
        <button class="btn-gray" ng-click="clickAudioFile()">Choose file</button>
        <input type="file" id="audioFile">
        <div ng-model="audio.note" class="audio-note">@{{ audio.note }}</div>
        <audio ng-show="audio.uploaded" id="audioFileUploaded" controls>
            <source id="srcToMp3" src="" type="audio/mpeg">
        </audio>
        <div ng-show="audio.uploaded" class="audio-settings">
            <label class="label-header">Audio settings:</label>
            <div class="setting-row">
                <label class="label-column">Audio starts<span class="tooltip" title="Time added to video starts">?</span></label>
                @include('snippet.templates.selectTime', array('model' => 'audio.start', 'options' => 'audioTime'))
            </div>
            <div class="setting-row">
                <label class="label-column">Audio ends</label>
                @include('snippet.templates.selectTime', array('model' => 'audio.end', 'options' => 'audioTime'))
            </div>
            <div class="setting-row">
                <label for="audioVolume" class="label-column">Audio volume</label>
                <input id="audioVolume" type="text" ng-model="audio.volume">
            </div>
            <div class="setting-row">
                <label for="videoVolume" class="label-column">Video volume</label>
                <input id="videoVolume" type="text" ng-model="player.volume">
            </div>
        </div>
        <div class="bottom-right">
            <button ng-click="updateAudio()" ng-show="audio.uploaded" class="btn-gray">Update</button>
            <button ng-click="toFourthStep()" ng-show="stepNum < 4" class="btn-green">Next</button>
        </div>
    </div>
</div>