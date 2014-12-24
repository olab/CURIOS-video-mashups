(function(){
    var app = angular.module('player', []);

    app.controller('Main', function($scope, $http){
        $scope.player = {};

        $scope.audio = {
            audioId: 'new',
            audioStartTime: 0,
            audioEndTime: 0,
            audioVolume: 100
        };
        $scope.audioNoteObj = document.getElementById('audioNote');
        $scope.audioPlayerObj = document.getElementById('audioPlayer');

        $scope.players = [{
            id:'new',
            name: 'New player'
        }];
        $http.get('getPlayersJSON').success(function(data){
            $scope.players = $scope.players.concat(data);
        });

        $scope.selectedPlayer = $scope.players[0];
        $scope.selectedPopup = false;

        $scope.changePlayerSettings = function(id){
            $http.get('getPlayerSettingsJSON',{
                params:{
                    id: id
                }
            }).success(function(data){
                data = angular.fromJson(data);
                $scope.initPlayerSettings(id, data);
                $scope.initAudio(id);
            }).error(function(){
                console.error('fix changePlayerSettings');
            });
        };

        $scope.initPlayerSettings = function(id, data){
            var isNew = (id == 'new');

            $scope.player.id = id;
            $scope.player.name = isNew ? 'New player' : data.name;
            $scope.player.height = isNew ? '200' : data.height;
            $scope.player.width = isNew ? '200' : data.width;
            $scope.player.startTime = isNew ? '0' : data.start_time;
            $scope.player.endTime = isNew ? '5' : data.end_time;
            $scope.player.soundLevel = isNew ? '100' : data.sound_level;
        };

        $scope.initAudio = function(id){
            if (id == 'new') {
                $scope.resetAudio();
                return;
            }

            $http.get('jsonGetAudio',{
                params:{
                    id: id
                }
            }).success(function(data){
                if(data != 'false'){
                    $scope.audio.audioId = data.id;
                    $scope.audio.audioStartTime = data.start_time;
                    $scope.audio.audioEndTime = data.end_time;
                    $scope.audio.audioVolume = data.volume;

                    $scope.audioPlayerObj.innerHTML = '<source src="' + data.src + '" type="audio/mpeg">';
                    $scope.audioPlayerObj.load();
                    $scope.audioPlayerObj.currentTime = data.start_time;
                    $scope.audioPlayerObj.volume = data.volume / 100;
                    $scope.audioPlayerObj.style.display = 'block';

                    var srcArray = data.src.split("/");
                    $scope.audioNoteObj.innerHTML = srcArray[srcArray.length - 1];
                } else {
                    $scope.resetAudio();
                }
            });
        };

        $scope.resetAudio = function(){
            if ($scope.audioPlayerObj.innerHTML) {
                $scope.audioPlayerObj.pause();
            }
            $scope.audioNoteObj.innerHTML = '';
            $scope.audioPlayerObj.style.display = 'none';
            $scope.audioPlayerObj.innerHTML = '';
        };

        $scope.initPlayerSettings($scope.selectedPlayer.id);
        $scope.initAudio($scope.selectedPlayer.id);
    });

    app.controller('PlayerSettings', function($scope, $http){
        $scope.updatePlayerSettings = function(){
            var json = angular.toJson($scope.player);

            $http.post('updatePlayerJSON', {
                json: json
            }).success(function(id){
                if ($scope.player.id == 'new') {
                    id = parseInt(id);

                    var savedPlayer = {
                        id: id,
                        name: $scope.player.name
                    };
                    $scope.player.id = id;
                    $scope.players = $scope.players.push(savedPlayer);
                    $scope.$parent.selectedPlayer = savedPlayer;
                } else {
                    $scope.players.forEach(function(element){
                        if (element.id == id) {
                            element.name = $scope.player.name;
                        }
                    });
                }
            }).error(function(){
                console.error('fix updatePlayerSettings');
            });
        };

        $scope.deletePlayerSettings = function(){
            var id = $scope.player.id;
            $http.get('deletePlayerAJAX',{
                params:{
                    id: id
                }
            }).success(function(){
                $scope.players.forEach(function(element, index){
                    if (element.id == id) {
                        $scope.players.splice(index, 1);
                        $scope.$parent.selectedPlayer = $scope.players[0];
                        $scope.initPlayerSettings($scope.selectedPlayer.id);
                    }
                });
            });
        };
    });

    app.controller('AudioSettings', function($scope, $http){
        $scope.audioUpdate = function(){
            var file = document.getElementById('audioFile').files[0],
                oldFileName = $scope.audioNoteObj.innerHTML;

            if ((typeof file == "undefined") && ($scope.audioPlayerObj.innerHTML == '')) {
                $scope.audioNoteObj.innerHTML = 'Please, choose file for upload';
            } else {
                $scope.audioNoteObj.innerHTML = 'Upload...';

                var data = new FormData();
                data.append('playerId', $scope.player.id);
                data.append('startTime', $scope.audio.audioStartTime);
                data.append('endTime', $scope.audio.audioEndTime);
                data.append('volume', $scope.audio.audioVolume);
                data.append('audio', file);

                var request = new XMLHttpRequest();
                request.open('POST', 'jsonUpdateAudio');
                request.send(data);

                request.onload = function () {
                    data = angular.fromJson(request.response);
                    $scope.audio.audioId = data.id;

                    if (file) {
                        $scope.audioNoteObj.innerHTML = file.name;
                        $scope.audioPlayerObj.innerHTML = '<source src=' + data.src + ' type="audio/mpeg">';
                    } else {
                        $scope.audioNoteObj.innerHTML = oldFileName;
                    }
                };
            }
        };

        $scope.audioDelete = function(){
            $http.get('deleteAudioAJAX',{
                params:{
                    id: $scope.audio.audioId
                }
            }).success(function(){
                $scope.resetAudio();
            });
        };
    });

    app.controller('Screen', function($scope){
        var dragPopup = false,
            mouseTargetX = 0,
            mouseTargetY = 0;

        $scope.clickOnScreen = function($event){
            var target = $event.target,
                isPopup = (target.tagName != 'svg');

            if (target.tagName == 'rect' || target.tagName == 'text') {
                console.log(target.parentNode);
                target.parentNode.setAttribute('transform', 'translate(100,100)');
            }

            mouseTargetX = target.getAttribute('x') - $event.offsetX;
            mouseTargetY = target.getAttribute('y') - $event.offsetY;

            dragPopup = isPopup ? target : false;

            $scope.$parent.selectedPopup = dragPopup;
        };

        $scope.dragPopup = function($event){
            if (dragPopup){
                var screenX = $event.offsetX,
                    screenY = $event.offsetY;
                dragPopup.setAttribute('x', (screenX + mouseTargetX).toString());
                dragPopup.setAttribute('y', (screenY + mouseTargetY).toString());
            }
        };

        $scope.deleteDragPopup = function(){
            dragPopup = false;
        };
    });

    app.controller('PopupSettings', function($scope, $http){
        var screen = document.getElementById('screen');
        $scope.popup = {
            id: 'new',
            name: 'New',
            type: 'rect',
            width: 20,
            height: 20,
            color: 'black',
            startTime: 0,
            endTime: 0,
            text: 'popup text'
        };
        $scope.popups = [];
        $http.get('getPopupsJSON').success(function(data){
            $scope.popups = data;
        });

        $scope.addPopup = function(){
            var newElement = document.createElementNS("http://www.w3.org/2000/svg", 'rect');
            newElement.setAttribute('width', $scope.popup.width.toString());
            newElement.setAttribute('height', $scope.popup.height.toString());
            newElement.setAttribute('class', 'popup');
            newElement.setAttribute('x', '0');
            newElement.setAttribute('y', '0');
            newElement.setAttribute('data-content','bar');

            newElement.style.fill = $scope.popup.color;

            var newText = document.createElementNS("http://www.w3.org/2000/svg", 'text');
            newElement.style.color = 'white';
            newElement.style.fontSize = 10;
            newText.innerHTML = 'bla';

            screen.appendChild(newElement);
            screen.appendChild(newText);
        };

        $scope.deletePopup = function(){
            $scope.selectedPopup.remove();
            $scope.selectedPopup = false;
            //$http.get('deletePopupAJAX').success(function(data){
            //    $scope.popups = data;
            //});
        }
    });
})();