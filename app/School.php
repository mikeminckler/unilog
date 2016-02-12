<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model {

	protected $fillable = ['school_name', 'contact_name', 'phone', 'email', 'website', 'application_url', 'hidden'];
		
	public $rules = array(
		'school_name' => 'required',
		'email' => 'email'
	);

	public function Messages() {
                return $this->hasMany('App\Message')->orderBy('updated_at', 'DESC');
        } 

	public function scopeSearch($query, $search) {

		$schools = $this->where(function($query) use ($search) {
                                        $query->where('email', 'LIKE', "%$search%")
                                        ->orWhere('school_name', 'LIKE', "%$search%");
                        })->orderBy('school_name');


		return $schools;

	}
	
}

?>
