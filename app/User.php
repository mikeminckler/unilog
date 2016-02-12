<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;

class User extends Model implements AuthenticatableContract,
                                        AuthorizableContract,
                                        CanResetPasswordContract
{
        use Authenticatable, Authorizable, CanResetPassword;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	// set the fillable fields
	protected $fillable = ['username', 'email', 'password'];

	public $create_rules = array(
		'username' => 'required|min:6',
		'email' => 'required|email|unique:users',
		'password' => 'required|alpha_num|between:6,12|confirmed',
		'password_confirmation' => 'required|alpha_num|between:6,12'
	);

	public $update_rules_with_password = array(
		'username' => 'required|min:6',
		'email' => 'required|email',
		'password' => 'required|alpha_num|between:6,12|confirmed',
		'password_confirmation' => 'required|alpha_num|between:6,12'
	);

	public $update_rules = array(
                'username' => 'required|min:6',
                'email' => 'required|email'
        );

	public $login_rules = array(
		'email' => 'required',
		'password' => 'required'
	);

	public function roles() {

		return $this->belongsToMany('App\Role')->withTimestamps();

	}

	public function hasRole($name) {

		foreach ($this->roles as $role) {
			if ($role->role_name == $name) {
				return true;
			} else {
				return false;
			}
		}

	}


	public function assignRole($role) {
		return $this->roles()->attach($role);
	}

	public function removeRole($role) {
		return $this->roles()->detach($role);
	}

	function initials(){
		$nword = explode(" ",$this->username);
		$new_name = "";
		foreach($nword as $letter){
			$new_name .= $letter{0};
		}
		return strtoupper($new_name);
	} 


}
