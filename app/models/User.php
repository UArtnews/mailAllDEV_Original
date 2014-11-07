<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    public $guarded = array('id');

    public function permissions(){
        return $this->hasMany('UserPermission', 'user_id', 'id');
    }

    public function hasPermission($instanceId, $node){
        dd($instanceId . ':' . $node);
        if($this->isSuperAdmin()) {
            return true;
        }elseif(UserPermission::where('user_id', $this->id)->where('instance_id', $instanceId)->where('node', $node)->count() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function isSuperAdmin(){
        if(UserPermission::where('user_id', $this->id)->where('instance_id', 0)->where('node', 'superAdmin')->count() > 0);
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

}