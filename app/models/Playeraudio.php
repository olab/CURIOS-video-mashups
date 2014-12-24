<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PlayerAudio extends Eloquent
{
	protected $table = 'player_audio';

    protected $fillable = ['player_id', 'audio_id'];

    public $timestamps = false;
}
