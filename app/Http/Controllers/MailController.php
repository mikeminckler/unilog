<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\Eloquent\Collection;

use App\Contact;
use App\MessageType;
use App\School;
use App\EmailAddress;
use App\Attachment;
use App\Message;

use App\Email;

class MailController extends Controller {

	protected $contact;
	protected $message_type;
	protected $mail;
	protected $school;
	protected $email_address;

	public function __construct(Contact $contact, MessageType $message_type, School $school, EmailAddress $email_address) {
		$this->contact = $contact;
		$this->message_type = $message_type;
		$this->school = $school;
		$this->email_address = $email_address;
	}

	public function create(Request $request) {

		$schools = $this->school->get()->sortBy('school_name')->pluck('school_name', 'id')->toArray();
		$all_contacts = $this->contact->allStudents();
		$contacts = array();
		foreach ($all_contacts as $contact) {
			$contacts[$contact->id] = $contact->name.' '.$contact->surname;	
		}

        $students = new Collection;
        if ($request->input('message_id')) {
            $messages = Message::processIds($request->input('message_id'));

            foreach ($messages as $message) {
                $students->add($message->Contact);
            } 
            $students = $students->unique();
        }

        if ($students->count()) {
			$selected = array(); 
            foreach ($students as $student) {
                $selected[] = $student->id;
            }
            
			$second_email = false;
			$student_emails = "";
			foreach ($students as $student) {
				if ($second_email) {
					$student_emails .= ', '.$student->user_email;
				} else {
					$student_emails .= $student->user_email; 
					$second_email = true;
				}
			}
		} else {
			$selected = null;
			$student_emails = null;
		}

        /* 
        
		if ($request->input('student_checkbox')) {
			$selected = array(); 
			foreach ($request->input('student_checkbox') as $student_id => $foo) {
				$selected[] = $student_id;
			}

			$students = $this->contact->whereIn('id', $selected)->get();
			$second_email = false;
			$student_emails = "";
			foreach ($students as $student) {
				if ($second_email) {
					$student_emails .= ', '.$student->user_email;
				} else {
					$student_emails .= $student->user_email; 
					$second_email = true;
				}
			}


		} else {
			$selected = null;
			$student_emails = null;
		}

         */

		return view()->make('emails.create', compact('contacts', 'schools', 'selected', 'student_emails'));

	}

	public function send(Request $request) {

		$validator = Validator::make($request->all(), ['contact_id' => 'required', 
			'to' => 'required',
			'subject' => 'required',
			'body' => 'required']); 
		
		if ($validator->passes()) {

			if ($request->file('attachment')) {
				$file = $request->file('attachment');
				$destinationPath = 'uploads/'.str_random(8)."-".time();
				$filename = $file->getClientOriginalName();
				//$extension = $file->getClientOriginalExtension();
				$uploadSuccess = $request->file('attachment')->move($destinationPath, $filename);

				if ($uploadSuccess) {
					$attachment = new Attachment;
					$attachment->attachment_filename = $destinationPath."/".$filename;
					$attachment->save();
				}
			}

			foreach ($request->input('contact_id') as $contact_id) {

				if ($contact_id > 0) {

					$message_log = new Message;

					$message_log->contact_id = $contact_id;
					$message_log->user_id = auth()->user()->id;
					$message_log->message_type_id = $this->message_type->where('message_type_name', 'LIKE', '%Email%')->get()->first()->id;
					$message_log->school_id = $request->input('school_id');

					
					if (isset($attachment)) {
						$message_log->attachment_id = $attachment->id;
					}

                    /*
					$contents = "";

					$body = $request->input('body');

					if (isset($attachment)) {
						//$body .= "\n\nAttachment: ".$attachment->fileLink();
					}

					$body .= "\n\n____________________\n".auth()->user()->username."\nBrentwood University Counselling";

					$contents .= "To: ".$request->input('to')."\n";
					$contents .= "From: ".auth()->user()->email."\n";
					$contents .= "Subject: ".$request->input('subject')."\n\n";
					$contents .= "Message: ".$body;
                     */

					$message_log->contents = $request->input('body');
					
					$message_log->save();
				}
			}


			$body = preg_replace('/\n{2,}/', "</p><p>", trim($body));
			$body = preg_replace('/\n/', '<br>',$body);
			$body = "<p>{$body}</p>";

			$data = ['body' => $body];

			
			//$sent_to = $request->input('to');	

			if (strpos($request->input('to'), ',') !== false) {
				$to_array = explode(',', $request->input('to'));

				foreach ($to_array as $sent_to) {

                    if (isset($attachment)) {
                        Email::sendEmail(trim($request->input('to')), $request->input('subject'), $data, $attachment);
                    } else {
                        Email::sendEmail(trim($request->input('to')), $request->input('subject'), $data);
                    }

					$this->addEmailAddress($sent_to);
				}

			} else {

				if (isset($attachment)) {
                    Email::sendEmail(trim($request->input('to')), $request->input('subject'), $data, $attachment);
				} else {
                    Email::sendEmail(trim($request->input('to')), $request->input('subject'), $data);
				}

				$this->addEmailAddress($request->input('to'));
			}

			return redirect()->to('/')->with(['success' => 'Email to <span class="mono">'.$request->input('to').'</span> sent']);
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}
	}


	public function addEmailAddress($sent_to) {

			$sent_to = trim($sent_to);

			$student_check = $this->contact->where('user_email', $sent_to)->first();
			$school_check = $this->school->where('email', $sent_to)->first();
			$email_check = $this->email_address->where('email_address', $sent_to)->first();

			if (!$student_check && !$school_check && !$email_check) {
				$email_address = new EmailAddress;
				$email_address->email_address = $sent_to;
				$email_address->save();
			}

	}

}

?>
