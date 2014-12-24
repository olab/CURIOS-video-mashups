<?php

class PlayerSettings extends Eloquent {
	protected $table = 'player_settings';

    protected $fillable = ['name', 'width', 'height', 'start_time', 'end_time', 'sound_level'];

    public $timestamps = false;

    public static function createEntry($name, $width, $height, $startTime, $endTime, $soundLevel)
    {
        $playerId = PlayerSettings::create([
            'name'          => $name,
            'width'         => $width,
            'height'        => $height,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'sound_level'   => $soundLevel,
        ])->id;

        UserPlayer::create([
            'user_id'   => Auth::id(),
            'player_id' => $playerId
        ]);
        return $playerId;
    }

    public static function updateEntry($id, $name, $width, $height, $startTime, $endTime, $soundLevel)
    {
        PlayerSettings::where('id', '=', $id)->update([
            'name'          => $name,
            'width'         => $width,
            'height'        => $height,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'sound_level'   => $soundLevel,
        ]);
    }
}