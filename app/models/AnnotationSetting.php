<?php

class AnnotationSetting extends Eloquent {
	protected $table = 'annotation_settings';

    protected $fillable = ['form', 'backGround', 'x', 'y', 'height', 'width', 'start_time', 'end_time', 'text', 'transparency', 'fontSize', 'color'];

    public $timestamps = false;

    public static function createEntry($form, $backGround, $x, $y, $height, $width, $startTime, $endTime, $text, $transparency, $fontSize, $color)
    {
        return AnnotationSetting::create([
            'form' =>$form,
            'backGround' => $backGround,
            'x' =>$x,
            'y' => $y,
            'height' =>$height,
            'width' => $width,
            'start_time' => $startTime,
            'end_time' =>$endTime,
            'text' => $text,
            'transparency' => $transparency,
            'fontSize' => $fontSize,
            'color' =>$color
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

    public static function getAnnotationsByVideoId($videoId)
    {
        $result = [];
        $videoAnnotationsObj = VideoAnnotation::where('video_id', '=', $videoId)->get();

        foreach ($videoAnnotationsObj as $videoAnnotationObj) {
            $result[] = AnnotationSetting::find($videoAnnotationObj->annotation_id);
        }

        return $result;
    }
}