<div ng-controller="ThirdStep" ng-show="stepNum > 2" class="step">
    <label>Do you want to use audio?</label>
    <label for="yesAudio">Yes</label>
    <input id="yesAudio" type="radio" name="audio" ng-model="audio.exist" value="yes">
    <label for="noAudio">No</label>
    <input id="noAudio" type="radio" name="audio" ng-model="audio.exist" value="no" ng-change="toFourthStep()">

    <div ng-show="audio.exist">
        <audio ng-show="audio.uploaded" id="audioFileUploaded" controls>
          <source id="srcToMp3" src="" type="audio/mpeg">
        </audio>
        <br>
        <input type="file" id="audioFile">
        <span ng-model="audio.note">@{{ audio.note }}</span>
        <br>
        <div ng-show="audio.uploaded">
            <label class="label-column">Audio starts<span class="tooltip" title="Time added to video starts">?</span></label>
            @include('snippet.templates.selectTime', array('model' => 'audio.start', 'options' => 'audioTime'))
            <br>
            <label class="label-column">Audio ends</label>
            @include('snippet.templates.selectTime', array('model' => 'audio.end', 'options' => 'audioTime'))
            <br>
            <label for="audioVolume" class="label-column">Audio volume</label>
            <input id="audioVolume" type="text" ng-model="audio.volume">
            <br>
            <label for="videoVolume" class="label-column">Video volume</label>
            <input id="videoVolume" type="text" ng-model="player.volume">
            <br>
        </div>
        <button ng-click="uploadAudio()">Upload File</button>
        <button ng-click="updateAudio()" ng-show="audio.uploaded">Update</button>
        <button ng-click="toFourthStep()" class="next-step" ng-show="stepNum < 4">Next</button>
    </div>
</div>