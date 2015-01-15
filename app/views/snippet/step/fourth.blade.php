<div ng-controller="FourthStep" ng-show="stepNum > 3" class="step">
    <div>
        <label class="label-column">Form</label>
        <select ng-model="annotation.form">
            <option value="rectangle">Rectangle</option>
            <option value="ellipse">Ellipse</option>
        </select>
        <br>
        <label class="label-column">Background</label>
        <input type="text" ng-model="annotation.backGround">
        <br>
        <label class="label-column">Height</label>
        <input type="text" ng-model="annotation.height">
        <br>
        <label class="label-column">Width</label>
        <input type="text" ng-model="annotation.width">
        <br>
        <label class="label-column">Annotation starts</label>
        <input type="text" ng-model="annotation.start">
        <br>
        <label class="label-column">Annotation ends</label>
        <input type="text" ng-model="annotation.end">
        <br>
        <label class="label-column">Annotation text</label>
        <input type="text" ng-model="annotation.text">
    </div>
    <button ng-click="addAnnotation()">Add annotation</button>
    <button ng-click="toFifthStep()" class="next-step" ng-show="stepNum < 5">Next</button>
</div>