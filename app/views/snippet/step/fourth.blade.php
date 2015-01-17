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
        <label class="label-column">Transparency<span class="tooltip" title="Background transparency. Range from 0 to 1.">?</span></label>
        <input type="text" ng-model="annotation.transparency">
        <br>
        <label class="label-column">Height</label>
        <input type="text" ng-model="annotation.height">
        <br>
        <label class="label-column">Width</label>
        <input type="text" ng-model="annotation.width">
        <br>
        <label class="label-column">Coordinate x</label>
        <input type="text" ng-model="annotation.x">
        <br>
        <label class="label-column">Coordinate y</label>
        <input type="text" ng-model="annotation.y">
        <br>
        <label class="label-column">Font size</label>
        <input type="text" ng-model="annotation.fontSize">
        <br>
        <label class="label-column">Font color</label>
        <input type="text" ng-model="annotation.color">
        <br>
        <label class="label-column">Annotation starts</label>
        @include('snippet.templates.selectTime', array('model' => 'annotation.start', 'options' => 'videoTime'))
        <br>
        <label class="label-column">Annotation ends</label>
        @include('snippet.templates.selectTime', array('model' => 'annotation.end', 'options' => 'videoTime'))
        <br>
        <label class="label-column">Annotation text</label>
        <input type="text" ng-model="annotation.text">
    </div>
    <button ng-click="resetAnnotationForm()">Reset form</button>
    <button ng-click="addAnnotation()">Add annotation</button>
    <button ng-click="updateAnnotation()" ng-show="annotationForDelete">Update</button>
    <button ng-click="deleteAnnotation()" ng-show="annotationForDelete">Delete</button>
    <button ng-click="toFifthStep()" class="next-step" ng-show="stepNum < 5">Next</button>
</div>