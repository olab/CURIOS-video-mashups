<?php

class VideoAnnotation extends Eloquent {
	protected $table = 'video_annotation';

    protected $fillable = ['video_id', 'annotation_id'];

    public $timestamps = false;

    public static function getAnnotationByVideoId($videoId)
    {
        $result = false;
        $videoAnnotationObjects = VideoAnnotation::where('video_id', '=', $videoId)->get();

        foreach ($videoAnnotationObjects as $videoAnnotationObj) {
            $result[] = AnnotationSetting::find($videoAnnotationObj->annotation_id);
        }

        return $result;
    }
}