<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Player</title>
        {{HTML::style('css/embed.css')}}
    </head>
    <body>
        <div id="player"></div>
        <audio id="audio"></audio>

        <script>
            // This code loads the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            // This function creates an <iframe> (and YouTube player) after the API code downloads.
            var player;
            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    height: '{{ $playerObj->height }}',
                    width: '{{ $playerObj->width }}',
                    videoId: '{{ $playerObj->code }}',
                    playerVars: {
                        start: '{{ $playerObj->start_time }}',
                        end: '{{ $playerObj->end_time }}',
                        theme: 'light',
                        controls: 0,
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
                event.target.setVolume('{{ $playerObj->sound_level }}');
                event.target.playVideo();
            }

            var done = false;
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !done) {
                  setTimeout(stopVideo, 600000);
                  done = true;
                }
                onAudioStateChange(event.data);
            }

            function stopVideo() {
                player.stopVideo();
            }

            // ----- audio block ----- //
            var audioData = JSON.parse('{{ $audioJSON }}');
            if (audioData) {
                var $audio = document.getElementById('audio');

                $audio.innerHTML = '<source src="' + audioData.src + '" type="audio/mpeg">';
                $audio.load();
                $audio.currentTime = audioData.start_time;
                $audio.volume = audioData.volume / 100;
            }
            function onAudioStateChange(status) {
                if (audioData) {
                    if (status == 1) $audio.play();
                    else if (status == 2) $audio.pause();
                }
            }
            // ----- end audio block ----- //
        </script>
    </body>
</html>