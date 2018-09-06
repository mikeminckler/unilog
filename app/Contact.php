<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {


	public function Messages() {
        return $this->hasMany('App\Message')->orderBy('updated_at', 'DESC');
    } 

	public function fullName() {
		return $this->name." ".$this->surname;
	}

    public function fullNameLastFirst() 
    {
		return $this->surname.", ".$this->name;
	}

	public function scopeSearch($query, $search) {

		$students = $this->where('user_type_id', '6')
		// Need to active students in MySchool with emails before we can use the 1.	
		//	->where('user_status', '1')
				->where(function($query) use ($search) {
					$query->where('name', 'LIKE', "%$search%")
					->orWhere('surname', 'LIKE', "%$search%");
			})->orderBy('surname');
		return $students;

	}

	public function allStudents() {

		return $this->where('user_type_id', '6')
			//->where('user_status', '1')
			->where('name', '!=', '')->orderBy('surname')->get();

	}

}

?>
