<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Player</title>
    {{HTML::style('css/embed.css')}}
    {{HTML::style('css/common.css')}}
</head>
<body>
<div id="screen">
    <div id="player"></div>
</div>

<audio id="audio"></audio>

<script>
    var
            YT_VIDEO_PLAY = 1,
            YT_VIDEO_STOP = 2,
            $screen = document.getElementById('screen'),
            $audio = document.getElementById('audio'),
            pathToAudio = '{{ URL::to('/audio/'); }}',
            audioActive = false,
            audioIsPlayed = false,
            videoIsPlayed = false,
            video = {{ $videoJSON }},
            audio = {{ $audioJSON }},
            annotations = {{ $annotationsJSON }},
            htmlAnnotations = [],
            currentVideoTime = video.start_time,
            previousVideoTime = 0,
            audioStartTime = parseInt(video.start_time) + parseInt(audio.start_time),
            audioEndTime = parseInt(video.start_time) + parseInt(audio.end_time),
            audioDelayConst = 0.5;

    // function which run every second
    setInterval(function () {
        previousVideoTime = currentVideoTime;
        currentVideoTime = player.getCurrentTime();

        var timeTravel = currentVideoTime - previousVideoTime;

        if (timeTravel < 0 || timeTravel > 1.5) {
            $audio.currentTime = currentVideoTime - audioStartTime;
            console.log('manually change time');
        }

        if (
                (currentVideoTime >= (audioStartTime - audioDelayConst)) &&
                (currentVideoTime <= (audioEndTime - audioDelayConst))
        ) {
            audioActive = true;
            if (videoIsPlayed && !audioIsPlayed) {
                player.setVolume(video.volume);
                onAudioStateChange();
            }
        } else {
            audioActive = false;
            onAudioStateChange();
        }

        annotationsEvent(currentVideoTime);
    }, 1000);

    function annotationsEvent(currentVideoTime) {
        annotations.forEach(function (annotation, idNum) {
            if (annotation.start_time <= currentVideoTime && annotation.end_time >= currentVideoTime) {
                htmlAnnotations[idNum].style.display = 'block';
            } else {
                htmlAnnotations[idNum].style.display = 'none';
            }
        });
    }

    // This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // This function creates an <iframe> (and YouTube player) after the API code downloads.
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: 360,
            width: 480,
            videoId: video.code,
            playerVars: {
                start: video.start_time,
                end: video.end_time,
                theme: 'light',
                controls: 1
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        event.target.unMute();
        event.target.setVolume(video.volume);

        ADL.XAPIYoutubeStatements.onPlayerReady(event);
    }

    function onPlayerStateChange(event) {

        if (event.data == YT_VIDEO_PLAY) {
            videoIsPlayed = true;
        } else if (event.data == YT_VIDEO_STOP) {
            videoIsPlayed = false;
        }

        onAudioStateChange(event.data);

        ADL.XAPIYoutubeStatements.onStateChange(event);
    }

    function stopVideo() {
        player.stopVideo();
    }

    // ----- audio block ----- //
    if (audio) {
        $audio.innerHTML = '<source src="' + pathToAudio + "/" + audio.path + '" type="audio/mpeg">';
        $audio.load();
        $audio.volume = audio.volume / 100;
    }

    function onAudioStateChange() {
        if (videoIsPlayed && audioActive) {
            audioIsPlayed = true;
            $audio.play();
        } else if (!videoIsPlayed || !audioActive) {
            audioIsPlayed = false;
            $audio.pause();
        }
    }
    // ----- end audio block ----- //

    // ----- annotation block ----- //
    for (var i = 0; i < annotations.length; i++) {
        var annotation = annotations[i],
                newElement = document.createElement('div'),
                isRect = (annotation.form == 'rectangle');

        newElement.id = 'annotation' + i;
        newElement.className = 'annotation';
        newElement.style.display = 'none';
        newElement.style.position = 'absolute';
        newElement.style.backgroundColor = '#' + annotation.backGround;
        newElement.style.color = '#' + annotation.color;
        newElement.style.fontSize = annotation.fontSize + 'px';
        newElement.style.borderRadius = isRect ? 0 : '100%';
        newElement.style.padding = isRect ? 0 : (annotation.height / 4) + 'px';
        newElement.style.height = annotation.height + 'px';
        newElement.style.width = annotation.width + 'px';
        newElement.style.opacity = 1 - annotation.transparency / 100;
        newElement.style.top = annotation.y + 'px';
        newElement.style.left = annotation.x + 'px';

        newElement.innerHTML = annotation.text;

        $screen.appendChild(newElement);
        htmlAnnotations.push(newElement)
    }
    // ----- end annotation block ----- //


    // ----- xAPI Statements ----- //

    (function (ADL) {
        XAPIYoutubeStatements = function () {

            var actor = {"mbox": "", "name": ""};
            var videoActivity = {"id":"https://www.youtube.com/watch?v=" + video.code, "definition":{"name": {"en-US":video.code}} };

            this.changeConfig = function (options) {
                actor = options.actor;
                videoActivity = options.videoActivity;
            };

            this.onPlayerReady = function (event) {
                var message = "yt: player ready";
                console.log(message);
                xAPIonPlayerReady(event);
            };

            this.onStateChange = function (event) {
                var curTime = player.getCurrentTime().toString();
                var ISOTime = "PT" + curTime.slice(0, curTime.indexOf(".") + 3) + "S";
                var stmt = null;
                var e = "";
                switch (event.data) {
                    case -1:
                        e = "unstarted";
                        console.log("yt: " + e);
                        break;
                    case 0:
                        e = "ended";
                        console.log("yt: " + e);
                        stmt = completeVideo(ISOTime);
                        break;
                    case 1:
                        e = "playing";
                        console.log("yt: " + e);
                        stmt = playVideo(ISOTime);
                        break;
                    case 2:
                        e = "paused";
                        console.log("yt: " + e);
                        stmt = pauseVideo(ISOTime);
                        break;
                    case 3:
                        e = "buffering";
                        console.log("yt: " + e);
                        break;
                    case 5:
                        e = "cued";
                        console.log("yt: " + e);
                        break;
                    default:
                }
                ADL.XAPIYoutubeStatements.onXAPIEvent(stmt);
            };

            this.onXAPIEvent = function (stmt) {
                window.parent.postMessage({type: 'xAPIStatement', statement: stmt}, '*');
            };

            function xAPIonPlayerReady(event)
            {

            }

            function buildStatement(stmt) {
                stmt.actor = actor;
                stmt.object = videoActivity;
                stmt.timestamp = (new Date()).toISOString();
                return stmt;
            }

            function playVideo(ISOTime) {
                var stmt = {};

                if (ISOTime == "PT0S") {
                    stmt.verb = ADL.verbs.launched;
                } else {
                    stmt.verb = ADL.verbs.resumed;
                    stmt.result = {"extensions": {"resultExt:resumed": ISOTime}};
                }
                return buildStatement(stmt);
            }

            function pauseVideo(ISOTime) {
                var stmt = {};

                stmt.verb = ADL.verbs.suspended;
                stmt.result = {"extensions": {"resultExt:paused": ISOTime}};

                return buildStatement(stmt);
            }

            function completeVideo(ISOTime) {
                var stmt = {};

                stmt.verb = ADL.verbs.completed;
                stmt.result = {"duration": ISOTime, "completion": true};

                return buildStatement(stmt);
            }

        };

        ADL.XAPIYoutubeStatements = new XAPIYoutubeStatements();

    }(window.ADL = window.ADL || {}));


    //IE use "attachEvent" and "onmessage"
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

    eventer(messageEvent, receiveMessage);

    window.parent.postMessage({type: 'videoServiceLoaded'}, '*');

    function receiveMessage(event)
    {
        var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.

        console.log(event);
        console.log(origin);

        var dataKey = event.message ? "message" : "data";
        var data = event[dataKey];

        switch (data.type){
            case 'changeConfig':
                ADL.XAPIYoutubeStatements.changeConfig({
                    "actor":  data.actor
                });
                break;

            default:
                return;
                break;
        }
    }

</script>
</body>
</html>