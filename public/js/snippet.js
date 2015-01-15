(function(){

    // This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function Main($scope){
        $scope.stepNum = 1;
        $scope.player = {
            originalUrl: 'https://www.youtube.com/watch?v=iS0wuN_6wyw',
            videoCode: '',
            start: 0,
            volume: 100
        };
        $scope.annotation = {
            form: 'rectangle',
            backGround: 'gray',
            x: 0,
            y: 0,
            height: 100,
            width: 100,
            start: 0,
            text: ''
        };
        $scope.audio = {
            exist: '',
            start: 0,
            volume: 100
        };
        $scope.playerApi = {};
    }

    function FirstStep($scope){
        $scope.toSecondStep = function(){
            var $mainScope = $scope.$parent;

            $mainScope.stepNum = 2;
            $mainScope.player.videoCode = getVideoCode($mainScope.player.originalUrl);
            $mainScope.playerApi = createPlayer($mainScope.player);
        }
    }

    function SecondStep($scope, $http){
        $scope.toThirdStep = function(){
            var $mainScope = $scope.$parent;

            $mainScope.stepNum = 3;
        }
    }

    function ThirdStep($scope, $http){
        $scope.toFourthStep = function(){
            var $mainScope = $scope.$parent;

            $mainScope.stepNum = 4;

            if ($mainScope.audio.exist == 'no') {
                console.log('reset audio form, when click no');
            }
        }
    }

    function FourthStep($scope, $http){
        var $mainScope = $scope.$parent;
        $scope.addAnnotation = function(){
            var editableScreen = document.getElementById('editableScreen');
            var newAnnotation = document.createElement('div');

            newAnnotation.className = 'annotation';
            newAnnotation.style.position = 'absolute';
            newAnnotation.style.top = $mainScope.annotation.x + 'px';
            newAnnotation.style.left = $mainScope.annotation.y + 'px';
            newAnnotation.style.width = $mainScope.annotation.width + 'px';
            newAnnotation.style.height = $mainScope.annotation.height + 'px';
            newAnnotation.style.background = $mainScope.annotation.backGround;
            newAnnotation.style.color = 'white';
            newAnnotation.innerHTML = $mainScope.annotation.text;
            if ($mainScope.annotation.form == 'ellipse') {
                newAnnotation.style.borderRadius = '100%';
            }

            editableScreen.appendChild(newAnnotation);
        };

        $scope.toFifthStep = function(){
            $mainScope.stepNum = 5;
        }
    }

    function FifthStep($scope, $http){
        $scope.outputResult = function(){
            console.log(1);
            var resultScreen = document.getElementById('resultScreen');
            var embed = document.createElement('embed');

            embed.src = 'http://videoplayer/player/embed?slug=NQ==';
            embed.width = '660px';
            embed.height = '485px';
            resultScreen.appendChild(embed);
        }
    }

    function getVideoCode(url){
        var videoCode = url.split('v=')[1];
        var ampersandPosition = videoCode.indexOf('&');
        if(ampersandPosition != -1) {
            videoCode = videoCode.substring(0, ampersandPosition);
        }
        return videoCode;
    }

    function createPlayer(obj){
        // This function creates an <iframe> (and YouTube player) after the API code downloads.
        return new YT.Player('player', {
            height: '480',
            width: '640',
            videoId: obj.videoCode
        });
    }

    function Screen($scope){
        var focusedPopup = false,
            startX = 0,
            startY = 0;

        $scope.focus = function($event){
            focusedPopup = $event.target;
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

                if (0 < shiftX && shiftX < (640 - focusedPopup.offsetWidth)) {
                    focusedPopup.style.left = shiftX + 'px';
                }
                if (0 < shiftY && shiftY < (480 - focusedPopup.offsetHeight)) {
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