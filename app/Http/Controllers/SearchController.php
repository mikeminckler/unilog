<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Message;
use App\Contact;
use App\EmailAddress;
use App\School;

class SearchController extends Controller {

	protected $message;
	protected $contact;
	protected $email_address;
	protected $school;
	public function __construct(Message $message, Contact $contact, EmailAddress $email_address, School $school) {
		$this->message = $message;
		$this->contact = $contact;
		$this->email_address = $email_address;
		$this->school = $school;
	}
	

	public function search(Request $request) {

		$query = $request->input('q');

		if ($query) {
			$messages_array = $this->message->searchTerms($query);
		
			arsort($messages_array);

			$return = "";
			
			$messages = array();

			foreach ($messages_array as $message_id => $sort_order) {
				$message = $this->message->find($message_id);
				array_push($messages, $message);
			}

			$response = "<h1>Search Results</h1>";

			if (count($messages) > 0) {
				$response .= formatMessages($messages);
			} else {
				$response .= "There were no results";
			}
			return $response;
		}

	}

	public function student(Request $request) {

		$query = $request->input('term');

		$contacts = $this->contact->search($query)->get();

		$students = array();

		foreach ($contacts as $contact) {
			$student = array();
			$student['id'] = $contact->id;
			$student['value'] = $contact->name." ".$contact->surname;	
			$student['label'] = $contact->name." ".$contact->surname;	
			$student['email'] = $contact->user_email;
			$students[] = $student;
		}

		return $students; 

	}

	public function email(Request $request) {


		$query = $request->input('term');

		$results = array();

		$contacts = $this->contact->search($query)->get();
		$email_addresses = $this->email_address->search($query)->get();
		$schools = $this->school->search($query)->get();

		foreach ($contacts as $contact) {
                        $student = array();
                        $student['id'] = $contact->id;
                        $student['value'] = $contact->user_email;
                        $student['label'] = $contact->name." ".$contact->surname;
                        $results[] = $student;
                }

		foreach ($email_addresses as $email_address) {
			$email = array();
			$email['id'] = $email_address->id;
			$email['value'] = $email_address->email_address;
			$email['label'] = $email_address->email_address;
			$results[] = $email;
		}

		foreach ($schools as $school) {
			$school_array = array();
			$school_array['id'] = $school->id;
			$school_array['value'] = $school->email;
			$school_array['label'] = $school->school_name." - ".$school->email;
			$results[] = $school_array;
		}


		return $results;

	}


}

?>
