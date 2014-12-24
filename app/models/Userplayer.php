<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserPlayer extends Eloquent
{
	protected $table = 'user_players';

    protected $fillable = ['user_id', 'player_id'];

    public $timestamps = false;
}
