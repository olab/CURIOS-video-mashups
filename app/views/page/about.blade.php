<!doctype html>
<html ng-app="player">
<head>
    <meta charset="UTF-8">
    <title>About CURIOS</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    {{HTML::style('css/snippet.css')}}
    {{HTML::style('css/common.css')}}
</head>
<body>
<div class="top-menu">
    About CURIOS
    <a href="{{URL::to('/snippet')}}" class="btn" id="btn-snippet">To snippet</a>
    <div class="clear"></div>
</div>

<div class="body-bl">
    <p>Curated Ubiquitous Rural Informed Online Services</p>
    <p>CURIOS is an initiative developed at the University of Calgary. Targeted at rural docs, CURIOS has 3 arms to the project:</p>
    <ul>
        <li>Filtered Feeds - curated social media with high relevance to rural medical education</li>
        <li>Video mashups - curated and annotated video snippets of existing material</li>
        <li>Webinar series - interactive series, combining aspects from the other two arms. </li>
    </ul>
    <p>You can learn more about CURIOS at <span style="text-decoration: underline;"><strong><a href="https://curios3.wordpress.com/" target="_blank">curios3.wordpress.com</a></strong></span> </p>
    <p>CURIOS Team:</p>
    <ul>
        <li>Dr David Topps</li>
        <li>Dr Lara Cooke</li>
        <li>Dr Heather Armson</li>
        <li>Dana Young</li>
    </ul>
    <p>CURIOS was funded by a grant from the Alberta Rural Physician Program. </p>
</div>
</body>
</html>