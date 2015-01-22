<?php

class VideoAnnotation extends Eloquent {
	protected $table = 'video_annotation';

    protected $fillable = ['video_id', 'annotation_id'];

    public $timestamps = false;
}