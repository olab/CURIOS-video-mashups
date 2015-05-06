<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $fillable = ['email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token', 'consumer_key', 'created_by_lti');

    public static function getByConsumerKey($consumer_key)
    {
        if(empty($consumer_key)) return false;
        $result =  self::where('consumer_key', '=', $consumer_key)->first();

        if(empty($result->id)) return null;
        return $result;
    }

    public static function getByEmail($email)
    {
        if(empty($email)) return false;
        $result =  self::where('email', '=', $email)->first();

        if(empty($result->id)) return null;
        return $result;
    }

}
