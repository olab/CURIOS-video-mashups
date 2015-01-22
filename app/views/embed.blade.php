<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Player</title>
        {{HTML::style('css/embed.css')}}
    </head>
    <body>
        <div id="screen">
            <div id="player"></div>
        </div>

        <audio id="audio">
        </audio>

        <script>
            var $screen = document.getElementById('screen'),
                $audio = document.getElementById('audio'),
                pathToAudio = '{{ URL::to('/audio/'); }}',
                audioActive = false,
                audioIsPlayed = false,
                videoIsPlayed = false,
                video = {{ $videoJSON }},
                audio = {{ $audioJSON }},
                annotations = {{ $annotationsJSON }},
                currentVideoTime = video.start_time,
                previousVideoTime = 0,
                audioStartTime = video.start_time + audio.start_time,
                audioDelayConst = 0.5;

            // function which run every second
            setInterval(function(){
                previousVideoTime = currentVideoTime;
                currentVideoTime = player.getCurrentTime();

                var timeTravel = currentVideoTime - previousVideoTime;

                if (timeTravel < 0 || timeTravel > 1.5) {
                    $audio.currentTime = currentVideoTime - audioStartTime;
                    console.log('manually change time');
                }

                if (currentVideoTime >= audioStartTime - audioDelayConst){
                    audioActive = true;
                    if (videoIsPlayed && ! audioIsPlayed) {
                        player.setVolume(video.volume);
                        onAudioStateChange(1);
                    }
                } else {
                    audioActive = false;
                    onAudioStateChange(2);
                }
            }, 1000);

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
                        controls: 1,
                        rel: 0,
                        showinfo: '0'
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            }

            function onPlayerReady(event) {
                player.setVolume(100);
                //event.target.playVideo();
            }

            var done = false;
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !done) {
                  setTimeout(stopVideo, 600000);
                  done = true;
                }

                if (event.data == 1) {
                    videoIsPlayed = true;
                } else if (event.data == 2) {
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

            function onAudioStateChange(status) {
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
                    newElement = document.createElement('div');

                newElement.style.position = 'absolute';
                newElement.style.backgroundColor = annotation.backGround;
                newElement.style.color = annotation.color;
                newElement.style.fontSize = annotation.fontSize + 'px';
                newElement.style.borderRadius = (annotation.form == 'rectangle') ? 0 : '100%';
                newElement.style.height = annotation.height + 'px';
                newElement.style.width = annotation.width + 'px';
                newElement.style.opacity = 1 - annotation.transparency;
                newElement.style.top = annotation.y + 'px';
                newElement.style.left = annotation.x + 'px';

                newElement.innerHTML = annotation.text;

                $screen.appendChild(newElement);
            }
            // ----- end annotation block ----- //
        </script>
    </body>
</html>