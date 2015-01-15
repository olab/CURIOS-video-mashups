<div ng-controller="ThirdStep" ng-show="stepNum > 2" class="step">
    <label>Do you want to use audio?</label>
    <label for="yesAudio">Yes</label>
    <input id="yesAudio" type="radio" name="audio" ng-model="audio.exist" value="yes">
    <label for="noAudio">No</label>
    <input id="noAudio" type="radio" name="audio" ng-model="audio.exist" value="no" ng-change="toFourthStep()">

    <div ng-show="audio.exist">
        <audio controls>
          <source src="{{ URL::asset('audio/Carly Comando - Everyday.mp3')}}" type="audio/mpeg">
        </audio>
        <br>
        <input type="file">
        <br>
        <br>
        <label class="label-column">Audio starts</label>
        @include('snippet.templates.selectTime')
        <br>
        <label class="label-column">Audio ends</label>
        @include('snippet.templates.selectTime')
        <br>
        <label for="audioVolume" class="label-column">Audio volume</label>
        <input id="audioVolume" type="text" ng-model="audio.volume">
        <button ng-click="toFourthStep()" class="next-step" ng-show="stepNum < 4">Next</button>
    </div>
</div>