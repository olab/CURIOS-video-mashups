<?php

class VideoSettings extends Eloquent {
	protected $table = 'video_settings';

    protected $fillable = ['code', 'start_time', 'end_time', 'volume'];

    public $timestamps = false;

    public static function createEntry($code, $startTime, $endTime, $volume)
    {
        return VideoSettings::create([
            'code'       => $code,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            'volume'     => $volume
        ])->id;
    }

    public static function updateEntry($id, $code, $startTime, $endTime, $volume)
    {
        VideoSettings::where('id', '=', $id)->update([
            'code'          => $code,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'volume'          => $volume
        ]);
    }

    public static function getIdBySlug($slug)
    {
        return base64_decode($slug);
    }
}