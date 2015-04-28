<?php

class PlayerController extends \BaseController
{
	public function settings()
	{
		return View::make('player.index');
	}

    public function getPlayersJSON()
    {
        $data = DB::table('user_players')
            ->join('player_settings', 'user_players.player_id', '=', 'player_settings.id')
            ->orderBy('player_settings.name', 'asc')
            ->select('player_settings.id', 'player_settings.name')
            ->where('user_players.user_id', '=', Auth::id())
            ->get();
        exit(json_encode($data));
    }

    public function getPopupsJSON()
    {
        $data = DB::table('user_players')
            ->join('player_settings', 'user_players.player_id', '=', 'player_settings.id')
            ->orderBy('player_settings.name', 'asc')
            ->select('player_settings.id', 'player_settings.name')
            ->where('user_players.user_id', '=', Auth::id())
            ->get();
        exit(json_encode($data));
    }

    public function getPlayerSettingsJSON()
    {
        $playerSettings = PlayerSettings::find(Input::get('id'));
        exit(json_encode($playerSettings));
    }

    public function saveSettings()
    {
    }

    public function updatePlayerJSON()
    {
        $json = Input::get('json');
        $data = json_decode($json);

        if($data->id == 'new') {
            $data->id = PlayerSettings::createEntry(
                $data->name,
                $data->width,
                $data->height,
                $data->startTime,
                $data->endTime,
                $data->soundLevel,
                $data->code
            );
        } else {
            PlayerSettings::updateEntry(
                $data->id,
                $data->name,
                $data->width,
                $data->height,
                $data->startTime,
                $data->endTime,
                $data->soundLevel,
                $data->code
            );
        }
        exit(json_encode($data->id));
    }

    public function deletePlayerAJAX()
    {
        $id = Input::get('id');
        $user = PlayerSettings::find($id);
        $user->delete();
    }

    public function deleteAudioAJAX()
    {
        AudioSettings::deleteEntry(Input::get('id'));
    }

    public function jsonGetAudio()
    {
        $data = PlayerAudio::getAudioByPlayer(Input::get('id'));
        exit(json_encode($data));
    }

    public function jsonUpdateAudio()
    {
        $playerId = Input::get('playerId');

        if ($playerId != 'new') {
            $startTime  = Input::get('startTime');
            $endTime    = Input::get('endTime');
            $volume     = Input::get('volume');
            $audio      = Input::file('audio');
            $src        = '';
            $srcUrl     = '';

            if ($audio) {
                $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR;
                $audioName = $audio->getClientOriginalName();
                $audio->move($destinationPath, $audioName);
                $src = '/audio/' . $audioName;
                $srcUrl = asset($src);
            }

            $playerAudioObj = PlayerAudio::where('player_id', '=', $playerId)->get()->first();

            if ($playerAudioObj) {
                $audioId = $playerAudioObj->audio_id;
                AudioSettings::updateEntry(
                    $audioId,
                    $src,
                    $startTime,
                    $endTime,
                    $volume
                );
            } else {
                $audioId = AudioSettings::createEntry(
                    $playerId,
                    $src,
                    $startTime,
                    $endTime,
                    $volume
                );
            }

            exit(json_encode(array('id' => $audioId, 'src' => $srcUrl)));
        } else if ($playerId != 'new') {
            exit(json_encode('audio can be added only toward existing player'));
        } else {
            exit(json_encode('file not exist'));
        }
    }

    public function cabinet()
    {
        $users = DB::table('users')->get();
        return View::make('player.cabinet.index')
            ->with('users', $users);
    }

    public function updateProfile(){
        $user = Auth::user();
        return $this->saveProfile($user, URL::previous());
    }

    public function createProfile(){
        $user = new User();
        return $this->saveProfile($user, URL::previous());
    }

    public function saveProfile($user, $action)
    {
        $email = Input::get('user-name');
        $newPassword = Input::get('password');
        $newPasswordSubmit = Input::get('password-submit');
        $status = Input::get('status', 'superuser');
        $message = 'Wrong password submitted!';

        if ($newPassword == $newPasswordSubmit) {
            $user->email = $email;
            $user->password = Hash::make($newPassword);
            $user->status = $status;
            $user->save();
            $message = 'Done!';
        }

        return Redirect::to($action)->withInput()->withErrors([$message]);
    }

    public function createProfileView()
    {
        return View::make('player.cabinet.create');
    }

    public function editStatusProfiles()
    {
        $deleteId = Input::get('delete-user');
        if ($deleteId) {
            $user = User::find($deleteId);
            $user->delete();
        } else {
            $statuses = Input::get('status');
            foreach($statuses as $id => $status){
                $user = User::find($id);
                $user->status = $status;
                $user->save();
            }
        }

        return Redirect::back()->withErrors(['Done!']);
    }

    public function upload()
    {
        $slug = Input::get('slug');
        print_r($slug); die;
    }
}