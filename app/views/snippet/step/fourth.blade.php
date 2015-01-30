<div ng-controller="FourthStep" ng-show="stepNum > 3">
    <div class="step-question">
        <label class="label-header">Do you want to add annotation?</label>
        <input id="yesAnnotation" type="radio" name="annotation" ng-model="annotation.exist" value="yes">
        <label for="yesAnnotation" class="label-answer">Yes</label>
        <input id="noAnnotation" type="radio" name="annotation" ng-model="annotation.exist" value="no" ng-change="toFifthStep()">
        <label for="noAnnotation" class="label-answer">No</label>
    </div>
    <div ng-show="annotation.exist" class="step step-fourth">
        <label class="label-header">Audio settings:</label>
        <div class="setting-row">
            <label class="label-column">Form</label>
            <select ng-model="annotation.form">
                <option value="rectangle">Rectangle</option>
                <option value="ellipse">Ellipse</option>
            </select>
        </div>
        <div class="column">
            <div class="setting-row">
                <label class="label-column">Background</label>
                <input type="text" ng-model="annotation.backGround">
            </div>
            <div class="setting-row">
                <label class="label-column">Height</label>
                <input type="text" ng-model="annotation.height">
            </div>
            <div class="setting-row">
                <label class="label-column">Width</label>
                <input type="text" ng-model="annotation.width">
            </div>
            <div class="setting-row">
                <label class="label-column">Font size</label>
                <input type="text" ng-model="annotation.fontSize">
            </div>
        </div>
        <div class="column">
            <div class="setting-row">
                <label class="label-column">Transparency<span class="tooltip" title="Background transparency. Range from 0 to 1.">?</span></label>
                <input type="text" ng-model="annotation.transparency">
            </div>
            <div class="setting-row">
                <label class="label-column">Coordinate x</label>
                <input type="text" ng-model="annotation.x">
            </div>
            <div class="setting-row">
                <label class="label-column">Coordinate y</label>
                <input type="text" ng-model="annotation.y">
            </div>
            <div class="setting-row">
                <label class="label-column">Font color</label>
                <input type="text" ng-model="annotation.color">
            </div>
        </div>
        <div class="setting-row">
            <label class="label-column">Starts</label>
            @include('snippet.templates.selectTime', array('model' => 'annotation.start', 'options' => 'videoTime'))
        </div>
        <div class="setting-row">
            <label class="label-column">Ends</label>
            @include('snippet.templates.selectTime', array('model' => 'annotation.end', 'options' => 'videoTime'))
        </div>
        <div class="setting-row">
            <label class="label-column">Text</label>
            <textarea class="annotation-text" ng-model="annotation.text"></textarea>
        </div>
        <button ng-click="resetAnnotationForm()" class="btn-purple">Reset form</button>
        <button ng-click="addAnnotation()" class="btn-purple">Add annotation</button>
        <button ng-click="deleteAnnotation()" class="btn-purple" ng-show="annotationForDelete">Delete</button>
        <div class="bottom-right">
            <button ng-click="updateAnnotation()" ng-show="annotationForDelete" class="btn-gray">Update</button>
            <button ng-click="toFifthStep()" ng-show="stepNum < 5" class="btn-green">Next</button>
        </div>
    </div>
</div>