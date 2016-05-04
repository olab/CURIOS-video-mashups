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
            YOUTUBE_URL = 'https://www.youtube.com/',
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
            currentVideoTime = parseFloat(video.start_time),
            previousVideoTime = 0,
            audioStartTime = parseFloat(video.start_time) + parseFloat(audio.start_time),
            audioEndTime = parseFloat(video.start_time) + parseFloat(audio.end_time),
            audioDelayConst = 0.5;

    // function which run every second
    function run() {
        setInterval(function () {
            previousVideoTime = currentVideoTime;
            currentVideoTime = player.getCurrentTime();

            if (audio) {

                var timeTravel = currentVideoTime - previousVideoTime;

                if (timeTravel < 0 || timeTravel > 1.5) {
                    //time has been manually changed
                    $audio.currentTime = currentVideoTime - audioStartTime;
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
            }

            annotationsEvent(currentVideoTime);
        }, 1000);
    }

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
    tag.src = YOUTUBE_URL + 'iframe_api';
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
        run();
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

    var xAPIVerbs = {
        play: {
            'id': 'http://activitystrea.ms/schema/1.0/play',
            'display': {"en-US": 'play'}
        },
        resumed: {
            'id': 'http://adlnet.gov/expapi/verbs/resumed',
            'display': {"en-US": 'resumed'}
        },
        suspended: {
            'id': 'http://adlnet.gov/expapi/verbs/suspended',
            'display': {"en-US": 'suspended'}
        },
        completed: {
            'id': 'http://adlnet.gov/expapi/verbs/completed',
            'display': {"en-US": 'completed'}
        },
        seeked: {
            'id': 'http://w3id.org/xapi/medbiq/verbs/seeked',
            'display': {"en-US": 'seeked'}
        }
    };

    (function (ADL) {
        XAPIYoutubeStatements = function () {

            var actor = {"mbox": "", "name": ""};
            var object = {
                "id": YOUTUBE_URL + "watch?v=" + video.code,
                "definition": {"name": {"en-US": video.code}}
            };
            var lastPlayerState = null;
            var lastPlayerTime = null;

            this.changeConfig = function (options) {
                if (typeof options.actor != 'undefined') {
                    actor = options.actor;
                }

                if (typeof options.object != 'undefined') {
                    object = options.object;
                }
            };

            this.onPlayerReady = function (event) {
                var message = "yt: player ready";
                console.log(message);

                this.changeConfig({
                    "object": {
                        "id": YOUTUBE_URL + "watch?v=" + video.code,
                        "definition": {"name": {"en-US": player.getVideoData().title}}
                    }
                });

                trackPlayerTime();
            };

            this.onStateChange = function (event) {
                var currentTime = player.getCurrentTime().toString();
                var currentISOTime = "PT" + currentTime.slice(0, currentTime.indexOf(".") + 3) + "S";
                var stmt = null;
                var e = "";
                switch (event.data) {

                    case YT.PlayerState.UNSTARTED:
                        e = "unstarted";
                        console.log("yt: " + e);
                        break;

                    case YT.PlayerState.PLAYING:
                        e = "playing";
                        console.log("yt: " + e);
                        if (currentTime == video.start_time || currentTime == 0) {
                            stmt = videoPlayed(currentISOTime);
                        } else {
                            stmt = videoResumed(currentISOTime);
                        }
                        break;

                    case YT.PlayerState.PAUSED:
                        e = "paused";
                        console.log("yt: " + e);
                        stmt = videoPaused(currentISOTime);
                        break;

                    case YT.PlayerState.ENDED:
                        e = "ended";
                        console.log("yt: " + e);
                        stmt = videoEnded(currentISOTime);
                        break;

                    case YT.PlayerState.BUFFERING:
                        e = "buffering";
                        console.log("yt: " + e);
                        break;

                    case YT.PlayerState.CUED:
                        e = "cued";
                        console.log("yt: " + e);
                        break;
                }

                if (stmt !== null) {
                    this.onXAPIEvent(stmt);
                }

                lastPlayerState = event.data;
            };

            this.onXAPIEvent = function (stmt) {
                window.parent.postMessage({type: 'xAPIStatement', statement: stmt}, '*');
            };

            function trackPlayerTime() {
                setInterval(function () {
                    var currentPlayerTime = player.getCurrentTime().toString();

                    if (lastPlayerTime !== null) {
                        var timeTravel = currentPlayerTime - lastPlayerTime;

                        if (timeTravel < 0 || timeTravel > 1.5) {
                            //time has been manually changed
                            console.log('yt: time has been manually changed');
                            stmt = videoSkipped(lastPlayerTime, currentPlayerTime);
                            ADL.XAPIYoutubeStatements.onXAPIEvent(stmt);
                        }
                    }

                    lastPlayerTime = player.getCurrentTime().toString();
                }, 1000);
            }

            function buildStatement(stmt) {
                stmt.actor = actor;
                stmt.object = object;
                stmt.timestamp = (new Date()).toISOString();

                return stmt;
            }

            function videoPlayed(currentISOTime) {
                var stmt = {};
                stmt.verb = xAPIVerbs.play;
                stmt.context = {
                    "extensions": {
                        "http://demo.watershedlrs.com/tincan/extensions/start_point": currentISOTime
                    }
                };

                return buildStatement(stmt);
            }

            function videoResumed(ISOTime) {
                var stmt = {};
                stmt.verb = xAPIVerbs.resumed;
                stmt.result = {"extensions": {"resultExt:resumed": ISOTime}};

                return buildStatement(stmt);
            }

            function videoPaused(ISOTime) {
                var stmt = {};

                stmt.verb = xAPIVerbs.suspended;
                stmt.result = {"extensions": {"resultExt:paused": ISOTime}};

                return buildStatement(stmt);
            }

            function videoEnded(ISOTime) {
                var stmt = {};

                stmt.verb = xAPIVerbs.completed;
                stmt.result = {"duration": ISOTime, "completion": true};

                return buildStatement(stmt);
            }

            function videoSkipped(lastPlayerTime, currentPlayerTime) {
                var currentPlayerISOTime = "PT" + currentPlayerTime.slice(0, currentPlayerTime.indexOf(".") + 3) + "S";
                var lastPlayerISOTime = "PT" + lastPlayerTime.slice(0, lastPlayerTime.indexOf(".") + 3) + "S";
                var stmt = {};

                stmt.verb = xAPIVerbs.seeked;
                stmt.context = {
                    "extensions": {
                        "http://demo.watershedlrs.com/tincan/extensions/start_point": lastPlayerISOTime,
                        "http://demo.watershedlrs.com/tincan/extensions/end_point": currentPlayerISOTime
                    }
                };

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

    function receiveMessage(event) {
        var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.

        //console.log(event);
        //console.log(origin);

        var dataKey = event.message ? "message" : "data";
        var data = event[dataKey];

        switch (data.type) {
            case 'changeConfig':
                ADL.XAPIYoutubeStatements.changeConfig({
                    "actor": data.actor
                });
                break;

            default:
                return;
                break;
        }
    }

    // ----- end xAPI Statements ----- //

</script>
</body>
</html>