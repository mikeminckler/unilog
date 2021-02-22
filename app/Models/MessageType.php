<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageType extends Model {


	// set the fields in the db that we want to be assignable
	protected $fillable = ['message_type_name', 'hidden'];

	public function Messages() {
		return $this->hasMany('App\Message')->orderBy('updated_at', 'DESC');;
	}

	public $rules = array(
		'message_type_name' => 'required'
	);

}

?>
