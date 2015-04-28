(function(){
    // This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function Main($scope, $http){
        $scope.screenWidth = 480;
        $scope.screenHeight = 360;
        $scope.stepNum = 1;
        $scope.slugToUpload = '==MQ';
        $scope.player = {
            id: 0,
            idBase64: '',
            originalUrl: 'https://www.youtube.com/watch?v=iS0wuN_6wyw',
            videoCode: '',
            start: {h: 0, m: 0, s: 0},
            end: {h: 0, m: 0, s: 0},
            volume: 100
        };
        $scope.playerApi = {};
        $scope.audio = {
            path: '',
            exist: '',
            start: {h: 0, m: 0, s: 0},
            end: {h: 0, m: 0, s: 0},
            volume: 100,
            note: '',
            uploaded: 0
        };
        $scope.annotation = getDefaultAnnotation();
        $scope.annotationExist = '';
        $scope.annotationForDelete = false;
        $scope.annotations = [];

        $scope.videoTime = {
            hours: [],
            minutes: [],
            seconds: []
        };
        $scope.audioTime = {
            hours: [],
            minutes: [],
            seconds: []
        };

        $scope.wasGenerated = false;

        $scope.setVideoTimeRange = function(){
            $scope.$apply(function() {
                var seconds = $scope.playerApi.getDuration() - 1;// minus 0 sec
                setTimeRange(seconds, $scope.videoTime, $scope.player.end);
            });
        };

        $scope.setAudioRange = function(){
            $scope.$apply(function() {
                var audio = document.getElementById('audioFileUploaded');
                setTimeRange(audio.duration, $scope.audioTime, $scope.audio.end);
            });
        };

        function setTimeRange(seconds, rangeObj, endTimeObj){
            var minutes = seconds / 60,
                hours = minutes / 60;

            endTimeObj.h = Math.floor(hours);
            endTimeObj.m = Math.floor(minutes) % 60;
            endTimeObj.s = Math.floor(seconds) % 60;

            seconds = (seconds < 60) ? seconds : 60;
            minutes = (minutes < 60) ? minutes : 60;

            for (var h = 0; h < hours; h++) {
                rangeObj.hours.push(h);
            }

            for (var m = 0; m < minutes; m++) {
                rangeObj.minutes.push(m);
            }
            for (var s = 0; s < seconds; s++) {
                rangeObj.seconds.push(s);
            }
        }

        function setTime(seconds, obj){
            obj.s = seconds % 60;
            obj.m = Math.floor((seconds % 3600) / 60);
            obj.h = Math.floor(seconds / 3600);
        }

        $scope.uploadBySlug = function(){
            var slug = $scope.slugToUpload;
            $http.post('player/uploadBySlug', {
                slug: slug
            }).success(function(data){

                function setVideo(data, slug){

                    $scope.player.id = data.id;
                    $scope.player.idBase64 = slug;
                    $scope.player.originalUrl = 'https://www.youtube.com/watch?v=' + data.code;
                    $scope.player.videoCode = data.code;
                    $scope.player.volume = data.volume;
                    $scope.playerApi = createPlayer($scope);

                    setTimeout(function(){
                        $scope.$apply(function(){
                            $scope.playerApi.setVolume(data.volume);
                            setTime(data.start_time, $scope.player.start); // set player start time
                            setTime(data.end_time, $scope.player.end); // set player end time
                        });
                    }, 1000);
                }

                function setAudio(data){
                    if (data) {
                        var src = '/audio/' + data.path;
                        $scope.audio.uploaded = 1;
                        $scope.audio.note = data.path;
                        $scope.audio.path = src;
                        $scope.audio.volume = data.volume;
                        $scope.audio.exist = 'yes';

                        var srcToMp3 = document.getElementById('srcToMp3');
                        srcToMp3.src = src;
                        srcToMp3.parentNode.load();
                        srcToMp3.parentNode.onloadeddata = function(){
                            $scope.setAudioRange();
                            this.currentTime = data.start_time;
                            setTime(data.start_time, $scope.audio.start); // set audio start time
                            setTime(data.end_time, $scope.audio.end); // set audio end time
                        };
                    } else {
                        $scope.audio.exist = '';
                    }
                }

                function setAnnotation(data){
                    $scope.annotationExist = data.length ? 'yes' : 'no';
                    for(var i = 0; i < data.length; i++){
                        addAnnotationToScreen(
                            i,
                            data[i].y,
                            data[i].x,
                            data[i].width,
                            data[i].height,
                            data[i].backGround,
                            data[i].color,
                            data[i].fontSize,
                            data[i].transparency,
                            data[i].text,
                            data[i].form
                        );
                    }
                    $scope.annotations.push(data[i]);
                }

                if (data.error){
                    $scope.slugToUpload = data.error;
                } else {
                    setVideo(data.playerInfo, slug);
                    setAudio(data.audioInfo);
                    setAnnotation(data.annotationInfo);

                    $scope.stepNum = 5;
                }
            });
        }
    }

    function FirstStep($scope){
        var $mainScope = $scope.$parent;
        $scope.toSecondStep = function(){
            $mainScope.stepNum = 2;
            $mainScope.player.videoCode = getYoutubeCode($mainScope.player.originalUrl);
            $mainScope.playerApi = createPlayer($scope);
        };
    }

    function SecondStep($scope, $http){
        var $mainScope = $scope.$parent;
        $scope.toThirdStep = function(){
            $mainScope.stepNum = 3;
        };

        $scope.updateVideo = function(){
            var time = $mainScope.player.start,
                startTime = ((time.h * 60) + time.m) * 60 + time.s;
            $mainScope.playerApi.seekTo(startTime);
        }
    }

    function ThirdStep($scope, $http){
        var $mainScope = $scope.$parent;

        $scope.toFourthStep = function(){
            $mainScope.stepNum = 4;

            if ($mainScope.audio.exist == 'no') {
                console.log('reset audio form, when click no');
            }
        };

        $scope.clickAudioFile = function(){
            var audioFile = document.getElementById('audioFile');
            audioFile.click();
            audioFile.onchange = function(){
                var audioFileLoader = document.getElementById('audioFileLoader');
                audioFileLoader.style.display = 'block';

                var file = audioFile.files[0];

                $scope.audio.uploaded = 1;

                var data = new FormData();
                data.append('file', file);

                var request = new XMLHttpRequest();
                request.open('POST', 'player/ajaxUploadAudio');
                request.send(data);

                request.onload = function () {
                    data = angular.fromJson(request.response);
                    $mainScope.audio.path = data.src;
                    $mainScope.audio.note = file.name;

                    var srcToMp3 = document.getElementById('srcToMp3');
                    srcToMp3.src = data.src;
                    srcToMp3.parentNode.load();

                    srcToMp3.parentNode.onloadeddata = function(){
                        $scope.setAudioRange();
                        audioFileLoader.style.display = 'none';
                    };
                };
            };
        };

        $scope.updateAudio = function(){
            $mainScope.playerApi.setVolume($mainScope.player.volume);
        }
    }

    function FourthStep($scope, $http){
        var $mainScope = $scope.$parent;
        $scope.addAnnotation = function(){
            addAnnotationToScreen(
                $mainScope.annotations.length,
                $mainScope.annotation.y,
                $mainScope.annotation.x,
                $mainScope.annotation.width,
                $mainScope.annotation.height,
                $mainScope.annotation.backGround,
                $mainScope.annotation.color,
                $mainScope.annotation.fontSize,
                $mainScope.annotation.transparency,
                $mainScope.annotation.text,
                $mainScope.annotation.form
            );

            var annotationClone = cloneObj($mainScope.annotation);
            $mainScope.annotations.push(annotationClone);
        };

        $scope.updateAnnotation = function(){
            $mainScope.annotationForDelete.style.top = $mainScope.annotation.y + 'px';
            $mainScope.annotationForDelete.style.left = $mainScope.annotation.x + 'px';
            $mainScope.annotationForDelete.style.width = $mainScope.annotation.width + 'px';
            $mainScope.annotationForDelete.style.height = $mainScope.annotation.height + 'px';
            $mainScope.annotationForDelete.style.background = '#' + $mainScope.annotation.backGround;
            $mainScope.annotationForDelete.style.color = '#' + $mainScope.annotation.color;
            $mainScope.annotationForDelete.style.fontSize = $mainScope.annotation.fontSize;
            $mainScope.annotationForDelete.style.opacity = 1 - $mainScope.annotation.transparency / 100;
            $mainScope.annotationForDelete.innerHTML = $mainScope.annotation.text;

            if ($mainScope.annotation.form == 'ellipse') {
                $mainScope.annotationForDelete.style.borderRadius = '100%';
                $mainScope.annotationForDelete.style.padding = $mainScope.annotation.height / 4 + 'px';
            } else {
                $mainScope.annotationForDelete.style.padding = 0;
            }

            $mainScope.annotations[$mainScope.annotationForDelete.dataset.id] = cloneObj($mainScope.annotation);
        };

        $scope.resetAnnotationForm = function(){
            $mainScope.annotationForDelete = false;
            $mainScope.annotation = getDefaultAnnotation();
            document.getElementById('annotationBg').style.backgroundColor = "rgb(0, 0, 0)";
            document.getElementById('annotationColor').style.backgroundColor = "rgb(255, 255, 255)";
        };

        $scope.deleteAnnotation = function(){
            var removeId = $mainScope.annotationForDelete.dataset.id;
            $mainScope.annotations[removeId].save = false;
            $mainScope.annotationForDelete.remove();
            $mainScope.annotationForDelete = false;
        };

        $scope.toFifthStep = function(){
            $mainScope.stepNum = 5;
        };

        function cloneObj(obj){
            var clone = {};
            for (var property in obj) {
                if (typeof obj[property] === 'object') {
                    clone[property] = cloneObj(obj[property]);
                } else {
                    clone[property] = obj[property];
                }
            }
            return clone;
        }
    }

    function FifthStep($scope, $http){
        var $mainScope = $scope.$parent;
        $scope.outputResult = function(){
            var resultScreen = document.getElementById('resultScreen');
            var embed = document.createElement('embed');

            embed.src = baseUrl + '/player/embed?slug=' + $mainScope.player.idBase64;
            embed.width = '480px';
            embed.height = '365px';
            resultScreen.style.display = 'block';
            resultScreen.innerHTML = '';
            resultScreen.appendChild(embed);
        };

        $scope.generate = function () {
            var $allData = {
                player: $mainScope.player,
                audio: $mainScope.audio,
                annotations: $mainScope.annotations
            };

            $http.post('player/generate', {
                json: angular.toJson($allData)
            }).success(function(data){
                $mainScope.wasGenerated = true;
                $mainScope.player.id = parseInt(data.id);
                $mainScope.player.idBase64 = data.idBase64;
                document.getElementById('resultCode').innerHTML =
                    '<embed src="' + baseUrl + '/player/embed?slug=' + data.idBase64 + '" width="480px" height="360px"></embed>';
            }).error(function(e){
                console.error(e.error.message);
            });
        }
    }

    function addAnnotationToScreen(id, top, left, width, height, background, color, fontSize, transparency, text, form){
        var editableScreen = document.getElementById('editableScreen');
        var newAnnotation = document.createElement('div');

        newAnnotation.className = 'annotation';
        newAnnotation.dataset.id = id;
        newAnnotation.style.position = 'absolute';
        newAnnotation.style.top = top + 'px';
        newAnnotation.style.left = left + 'px';
        newAnnotation.style.width = width + 'px';
        newAnnotation.style.height = height + 'px';
        newAnnotation.style.background = '#' + background;
        newAnnotation.style.color = '#' + color;
        newAnnotation.style.fontSize = fontSize;
        newAnnotation.style.opacity = 1 - transparency / 100;
        newAnnotation.innerHTML = text;

        if (form == 'ellipse') {
            newAnnotation.style.borderRadius = '100%';
            newAnnotation.style.padding = height / 4 + 'px';
        } else {
            newAnnotation.style.padding = 0;
        }

        editableScreen.appendChild(newAnnotation);
    }

    function getDefaultAnnotation(){
        return {
            form: 'rectangle',
            save: true,
            backGround: '000000',
            x: 0,
            y: 0,
            height: 100,
            width: 100,
            start: {h: 0, m: 0, s: 0},
            end: {h: 0, m: 0, s: 0},
            text: '',
            transparency: 0,
            fontSize: '12px',
            color: 'ffffff'
        }
    }

    function getYoutubeCode(url){
        var videoCode = url.split('v=')[1];
        var ampersandPosition = videoCode.indexOf('&');
        if(ampersandPosition != -1) {
            videoCode = videoCode.substring(0, ampersandPosition);
        }
        return videoCode;
    }

    function createPlayer($scope){
        // This function creates an <iframe> (and YouTube player) after the API code downloads.
        return new YT.Player('player', {
            height: $scope.screenHeight,
            width: $scope.screenWidth,
            videoId: $scope.player.videoCode,
            events: {
                onReady: $scope.setVideoTimeRange
            }
        });
    }

    function Screen($scope){
        var $mainScope = $scope.$parent,
            focusedPopup = false,
            startX = 0,
            startY = 0;

        $scope.focus = function($event){
            focusedPopup = $event.target;
            $mainScope.annotationForDelete = focusedPopup;

            $mainScope.annotation.x = parseInt(focusedPopup.style.left);
            $mainScope.annotation.y = parseInt(focusedPopup.style.top);
            $mainScope.annotation.width = parseInt(focusedPopup.style.width);
            $mainScope.annotation.height = parseInt(focusedPopup.style.height);
            $mainScope.annotation.backGround = rgb2hex(focusedPopup.style.background);
            $mainScope.annotation.color = rgb2hex(focusedPopup.style.color);
            $mainScope.annotation.fontSize = focusedPopup.style.fontSize;
            $mainScope.annotation.transparency = (1 - focusedPopup.style.opacity) * 100;
            $mainScope.annotation.text = focusedPopup.innerHTML;
            $mainScope.annotation.form = (focusedPopup.style.borderRadius) ? 'ellipse' : 'rectangle';

            startX = $event.screenX - parseInt(focusedPopup.style.left);
            startY = $event.screenY - parseInt(focusedPopup.style.top);

            function rgb2hex(rgb){
                rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
                return (rgb && rgb.length === 4) ?
                ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
            }
        };

        $scope.focusOut = function(){
            focusedPopup = false;
        };

        $scope.dragFocusElement = function($event){
            if(focusedPopup){
                var shiftX = -(startX - $event.screenX),
                    shiftY = -(startY - $event.screenY);

                $mainScope.annotation.x = shiftX;
                $mainScope.annotation.y = shiftY;

                if (0 < shiftX && shiftX < ($mainScope.screenWidth - focusedPopup.offsetWidth)) {
                    focusedPopup.style.left = shiftX + 'px';
                }
                if (0 < shiftY && shiftY < ($mainScope.screenHeight - focusedPopup.offsetHeight)) {
                    focusedPopup.style.top = shiftY + 'px';
                }
            }
        };
    }

    angular.module('player', [])
        .controller('Main',      Main)
        .controller('FirstStep', FirstStep)
        .controller('SecondStep', SecondStep)
        .controller('ThirdStep', ThirdStep)
        .controller('FourthStep', FourthStep)
        .controller('FifthStep', FifthStep)
        .controller('Screen', Screen);
})();