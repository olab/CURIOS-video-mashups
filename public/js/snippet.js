(function(){

    // This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function Main($scope){
        $scope.screenWidth = 480;
        $scope.screenHeight = 360;
        $scope.stepNum = 1;
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

        $scope.uploadAudio = function(){
            var file = document.getElementById('audioFile').files[0];

            if ((typeof file == "undefined") && ($scope.audio.note == '')) {
                $scope.audio.note = 'Please, choose file for upload';
            } else {
                $scope.audio.note = 'Upload...';
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
                    };
                };
            }
        };

        $scope.clickAudioFile = function(){
            document.getElementById('audioFile').click();
        };

        $scope.updateAudio = function(){
            $mainScope.playerApi.setVolume($mainScope.player.volume);

        }
    }

    function FourthStep($scope, $http){
        var $mainScope = $scope.$parent;
        $scope.addAnnotation = function(){
            var editableScreen = document.getElementById('editableScreen');
            var newAnnotation = document.createElement('div');

            newAnnotation.className = 'annotation';
            newAnnotation.dataset.id = $mainScope.annotations.length;
            newAnnotation.style.position = 'absolute';
            newAnnotation.style.top = $mainScope.annotation.y + 'px';
            newAnnotation.style.left = $mainScope.annotation.x + 'px';
            newAnnotation.style.width = $mainScope.annotation.width + 'px';
            newAnnotation.style.height = $mainScope.annotation.height + 'px';
            newAnnotation.style.background = $mainScope.annotation.backGround;
            newAnnotation.style.color = $mainScope.annotation.color;
            newAnnotation.style.fontSize = $mainScope.annotation.fontSize + 'px';
            newAnnotation.style.opacity = 1 - $mainScope.annotation.transparency;
            newAnnotation.innerHTML = $mainScope.annotation.text;

            if ($mainScope.annotation.form == 'ellipse') {
                newAnnotation.style.borderRadius = '100%';
            }

            editableScreen.appendChild(newAnnotation);

            var annotationClone = cloneObj($mainScope.annotation);
            $mainScope.annotations.push(annotationClone);
        };

        $scope.updateAnnotation = function(){
            $mainScope.annotationForDelete.style.top = $mainScope.annotation.y + 'px';
            $mainScope.annotationForDelete.style.left = $mainScope.annotation.x + 'px';
            $mainScope.annotationForDelete.style.width = $mainScope.annotation.width + 'px';
            $mainScope.annotationForDelete.style.height = $mainScope.annotation.height + 'px';
            $mainScope.annotationForDelete.style.background = $mainScope.annotation.backGround;
            $mainScope.annotationForDelete.style.color = $mainScope.annotation.color;
            $mainScope.annotationForDelete.style.fontSize = $mainScope.annotation.fontSize;
            $mainScope.annotationForDelete.style.opacity = 1 - $mainScope.annotation.transparency;
            $mainScope.annotationForDelete.innerHTML = $mainScope.annotation.text;

            if ($mainScope.annotation.form == 'ellipse') {
                $mainScope.annotationForDelete.style.borderRadius = '100%';
            } else {
                $mainScope.annotationForDelete.style.borderRadius = '0';
            }

            $mainScope.annotations[$mainScope.annotationForDelete.dataset.id] = cloneObj($mainScope.annotation);
        };

        $scope.resetAnnotationForm = function(){
            $mainScope.annotationForDelete = false;
            $mainScope.annotation = getDefaultAnnotation();
        };

        $scope.deleteAnnotation = function(){
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
            resultScreen.appendChild(embed);
        };

        $scope.generate = function () {
            var $allData = {
                player: $mainScope.player,
                audio: $mainScope.audio,
                annotations: $mainScope.annotations
            };

            $http.post('generate', {
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

    function getDefaultAnnotation(){
        return {
            exist: '',
            form: 'rectangle',
            backGround: 'gray',
            x: 0,
            y: 0,
            height: 100,
            width: 100,
            start: {h: 0, m: 0, s: 0},
            end: {h: 0, m: 0, s: 0},
            text: '',
            transparency: 0,
            fontSize: 12,
            color: 'black'
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
            $mainScope.annotation.backGround = focusedPopup.style.background;
            $mainScope.annotation.color = focusedPopup.style.color;
            $mainScope.annotation.fontSize = focusedPopup.style.fontSize;
            $mainScope.annotation.transparency = 1 - focusedPopup.style.opacity;
            $mainScope.annotation.text = focusedPopup.innerHTML;
            $mainScope.annotation.form = (focusedPopup.style.borderRadius) ? 'ellipse' : 'rectangle';

            startX = $event.screenX - parseInt(focusedPopup.style.left);
            startY = $event.screenY - parseInt(focusedPopup.style.top);
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