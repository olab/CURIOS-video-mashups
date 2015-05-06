<!doctype html>
<html ng-app="player">
<head>
    <meta charset="UTF-8">
    <title>CURIOS video mashup service | LTI Manager</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    {{HTML::style('css/snippet.css')}}
    {{HTML::style('css/common.css')}}
</head>
<body>
<h1>
    CURIOS video mashup service | LTI Manager
    <a href="{{URL::to('/snippet')}}" id="btn-snippet">To snippet</a>
    <a href="{{URL::to('/lti/create')}}" id="btn-create-user">Create new consumer</a>
</h1>

<div class="body-bl">
    @if($errors->any())
        <h4>{{$errors->first()}}</h4>
    @endif
    <div style="margin-bottom: 15px;"><b>Launch URL: {{URL::to('/snippet')}}</b></div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Consumer Key</th>
                <th>Enabled</th>
                <th>Enable from</th>
                <th>Enable until</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($consumers as $consumer)
            {{$consumer->dateRefactor();}}
        <tr>
            <td>{{$consumer->name}}</td>
            <td>{{$consumer->consumer_key}}</td>
            <td>@if($consumer->enabled == 1) Yes @else No @endif</td>
            <td>{{$consumer->enable_from}}</td>
            <td>{{$consumer->enable_until}}</td>
            <td>
                <div class="btn-group">
                    <a class="btn btn-info" href="{{URL::to('/lti/show/'.$consumer->consumer_key)}}">Edit</a>
                    or
                    <a href="{{URL::to('/lti/remove/'.$consumer->consumer_key)}}" class="btn btn-danger remove">Delete</a>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{HTML::script('js/jquery-1.11.2.min.js')}}
<script>
    $(document).ready(function(){
        function remove(){
            var button = $('.remove');
            button.on('click', function(e){
                if(!confirm('Please confirm deletion')){
                    e.preventDefault();
                }
            })
        }
        remove();
    });
</script>
</body>
</html>