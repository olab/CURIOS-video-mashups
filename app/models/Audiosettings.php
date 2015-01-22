<?php

class AudioSettings extends Eloquent {
	protected $table = 'audio_settings';

    protected $fillable = ['path', 'start_time', 'end_time', 'volume'];

    public $timestamps = false;

    public static function createEntry($path, $startTime, $endTime, $volume)
    {
        return AudioSettings::create([
            'path'       => $path,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            'volume'     => $volume,
        ])->id;
    }

    public static function deleteEntry($id)
    {
        $audio = AudioSettings::find($id);
        unlink(public_path().$audio->path);
        $audio->delete();
    }

    public static function getAudioByVideoId($videoId)
    {
        $result = false;
        $videoAudioObj = VideoAudio::where('video_id', '=', $videoId)->first();

        if ($videoAudioObj) {
            $result = AudioSettings::find($videoAudioObj->audio_id);
        }

        return $result;
    }
}