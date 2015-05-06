<!doctype html>
<html ng-app="player">
<head>
    <meta charset="UTF-8">
    <title>CURIOS video mashup service | LTI Manager</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    {{HTML::style('css/snippet.css')}}
    {{HTML::style('css/common.css')}}
    {{HTML::style('css/jquery-ui.min.css')}}
</head>
<body>
<h1>
    CURIOS video mashup service | LTI Manager > Create new consumer
    <a href="{{URL::to('/snippet')}}" id="btn-snippet">To snippet</a>
    <a href="{{URL::to('/lti')}}" id="btn-create-user">To LTI Manager</a>
</h1>

<div class="body-bl">
    @if($errors->any())
        <h4>{{$errors->first()}}</h4>
    @endif
    <form method="post" action="{{URL::to('/lti/insert-or-update')}}">
        <div class="setting-row">
            <label for="consumer_key" class="label-column">Consumer Key:</label>
            {{$consumer->consumer_key or 'automatically generated'}}
            <input type="hidden" name="consumer_key" value="{{$consumer->consumer_key}}">
        </div>
        <div class="setting-row">
            <label class="label-column">Active:</label>

            <input type="radio" name="enabled" id="enable" value="1" @if($consumer->enabled == 1) checked @endif>
            <label for="enabled" class="label-column">Enable</label>

            <input type="radio" name="enabled" id="disable" value="0" @if($consumer->enabled == 0) checked @endif>
            <label for="disable" class="label-column">Disable</label>
        </div>
        <div class="setting-row">
            <label for="name" class="label-column">Name:</label>
            <input name="name" value="{{$consumer->name}}">
        </div>
        <div class="setting-row">
            <label for="secret" class="label-column">Secret:</label>
            @if(!empty($consumer->secret))
                <input name="secret" value="{{$consumer->secret}}" style="width:250px" required>
            @else
                automatically generated
            @endif

        </div>
        <div class="setting-row">
            <label for="enable_from" class="label-column">Start date:</label>
            <input name="enable_from" id="enable_from" value="{{$consumer->enable_from}}">
        </div>
        <div class="setting-row">
            <label for="enable_until" class="label-column">End date:</label>
            <input name="enable_until" id="enable_until" value="{{$consumer->enable_until}}">
        </div>

        <input class="btn-green" type="submit" value="Save">
    </form>
</div>
{{HTML::script('js/jquery-1.11.2.min.js')}}
{{HTML::script('js/jquery-ui.min.js')}}
<script>
    $(document).ready(function(){
        $( "#enable_from" ).datepicker({"dateFormat": "yy-mm-dd"});
        $( "#enable_until" ).datepicker({"dateFormat": "yy-mm-dd"});
    });
</script>
</body>
</html>