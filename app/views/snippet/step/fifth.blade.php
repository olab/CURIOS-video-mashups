<div ng-controller="FifthStep" ng-show="stepNum > 4" class="step">
    <button ng-click="generate()">Generate code</button>
    <div ng-show="wasGenerated">
        Code to copy/paste in your site
        <br>
        <textarea style="width: 99%;" id="resultCode"></textarea>
        <br>
        <button ng-click="outputResult()">Watch result</button>
    </div>
</div>