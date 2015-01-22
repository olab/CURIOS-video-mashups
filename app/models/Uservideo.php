<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserVideo extends Eloquent
{
	protected $table = 'user_video';

    protected $fillable = ['user_id', 'video_id'];

    public $timestamps = false;
}
