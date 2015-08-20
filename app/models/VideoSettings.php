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
        return self::urlsafe_b64decode($slug);
    }

    public static function urlsafe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public static function urlsafe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}