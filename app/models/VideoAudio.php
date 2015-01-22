<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class VideoAudio extends Eloquent
{
	protected $table = 'video_audio';

    protected $fillable = ['video_id', 'audio_id'];

    public $timestamps = false;
}
