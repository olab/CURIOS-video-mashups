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

        <audio id="audio">
        </audio>

        <script>
            var YT_VIDEO_PLAY = 1,
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

            console.log(video.start_time);
            console.log(audio.start_time);
            console.log(audioStartTime);

            // function which run every second
            setInterval(function(){
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
                        ){
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

            function annotationsEvent(currentVideoTime){
                annotations.forEach(function(annotation, idNum){
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
            }

            function onPlayerStateChange(event) {

                if (event.data == YT_VIDEO_PLAY) {
                    videoIsPlayed = true;
                } else if (event.data == YT_VIDEO_STOP) {
                    videoIsPlayed = false;
                }

                onAudioStateChange(event.data);
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
                } else if (! videoIsPlayed || ! audioActive) {
                    audioIsPlayed = false;
                    $audio.pause();
                }
            }
            // ----- end audio block ----- //

            // ----- annotation block ----- //
            for(var i = 0; i < annotations.length; i++){
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
        </script>
    </body>
</html>