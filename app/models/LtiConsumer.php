<?php

class LtiConsumer extends Eloquent {

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lti_consumer';
    protected $primaryKey = 'consumer_key';
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        LtiConsumer::creating(function($consumer)
        {
            $not_unique = true;
            do{
                $consumer->consumer_key = $consumer->getRandomLetter().'-'.$consumer->getRandomString(6);
                $consumerByKey = LtiConsumer::getByConsumerKey($consumer->consumer_key);
                if(empty($consumerByKey)){
                    $not_unique = false;
                }
            }while($not_unique);
            $consumer->secret = $consumer->getRandomString(32);
            return true;
        });
    }

    public static function getByConsumerKey($consumer_key)
    {
        $result =  self::where('consumer_key', '=', $consumer_key)->first();

        if(empty($result->id)) return null;
        return $result;
    }

    public function dateRefactor($format = 'Y-m-d')
    {
        $this->enable_from = date($format, strtotime($this->enable_from));
        $this->enable_until = date($format, strtotime($this->enable_until));
    }

    public function getRandomLetter($length = 2) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $value = '';
        $charsLength = strlen($chars) - 1;
        for ($i = 1 ; $i <= $length; $i++) {
            $value .= $chars[rand(0, $charsLength)];
        }
        return $value;
    }

    public function getRandomString($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $value = '';
        $charsLength = strlen($chars) - 1;
        for ($i = 1 ; $i <= $length; $i++) {
            $value .= $chars[rand(0, $charsLength)];
        }
        return $value;
    }

}
