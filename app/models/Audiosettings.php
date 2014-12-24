<?php

class AudioSettings extends Eloquent {
	protected $table = 'audio_settings';

    protected $fillable = ['src', 'start_time', 'end_time', 'volume'];

    public $timestamps = false;

    public static function createEntry($playerId, $src, $startTime, $endTime, $volume)
    {
        $audioId = AudioSettings::create([
            'src'           => $src,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'volume'        => $volume,
        ])->id;

        PlayerAudio::create([
            'player_id'     => $playerId,
            'audio_id'      => $audioId,
        ]);

        return $audioId;
    }

    public static function updateEntry($id, $src, $startTime, $endTime, $volume)
    {
        $entry = AudioSettings::find($id);
        if ($src) $entry->src = $src;
        $entry->start_time = $startTime;
        $entry->end_time   = $endTime;
        $entry->volume     = $volume;
        $entry->save();
    }

    public static function deleteEntry($id)
    {
        $user = AudioSettings::find($id);
        unlink(public_path().$user->src);
        $user->delete();
    }
}