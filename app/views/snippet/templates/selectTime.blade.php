<select ng-model="{{$model}}.h" ng-options="v for v in {{$options}}.hours"></select>
<span class="label-time">hr</span>
<select ng-model="{{$model}}.m" ng-options="v for v in {{$options}}.minutes"></select>
<span class="label-time">min</span>
<select ng-model="{{$model}}.s" ng-options="v for v in {{$options}}.seconds"></select>
<span class="label-time">sec</span>