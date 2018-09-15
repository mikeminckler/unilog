<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Message;
use App\MessageType;
use App\School;
use App\Contact;
use App\Attachment;

use Validator;

class MessagesController extends Controller {

	protected $message;
	protected $message_type;
	protected $school;
	protected $contact;
	protected $attachment;

	public function __construct(Message $message, MessageType $message_type, School $school, Contact $contact, Attachment $attachment) {
		$this->message = $message;
		$this->message_type = $message_type;
		$this->school = $school;
		$this->contact = $contact;
		$this->attachment = $attachment;
	}

	public function index() {

		if (auth()->guest()) {
			return redirect()->route('login');
		} else {
			$messages = $this->message->with('MessageType', 'School', 'Contact', 'Attachment')
				->where('updated_at', '>', session()->get('start_date'))
                                ->where('updated_at', '<', session()->get('end_date'))
                                ->whereHidden('0')
                                ->orderBy('updated_at', 'DESC')
				->limit(15)
                                ->paginate(15);
			return view()->make('home', compact('messages'));
		}
	}

	public function show($id) {

		$message = $this->message->find($id);
		$message_types = $this->message_type->orderBy('message_type_name')->get()->pluck('message_type_name', 'id')->toArray();
		$schools = $this->school->get()->sortBy('school_name')->pluck('school_name', 'id')->toArray();
		$all_contacts = $this->contact->allStudents();
		$contacts = array();
		foreach ($all_contacts as $contact) {
			$contacts[$contact->id] = $contact->name.' '.$contact->surname;	
		}

		return view()->make('messages.show', compact('message', 'message_types', 'schools', 'contacts'));

	}
	

	public function create(Request $request, $student_id = null) {

		$message_types = $this->message_type->orderBy('message_type_name')->get()->pluck('message_type_name', 'id')->toArray();
		$schools = $this->school->get()->sortBy('school_name')->pluck('school_name', 'id')->toArray();
		if (isset($student_id)) {
			$student = $this->contact->find($student_id);
		} else {
			$student = null;
		}

		$all_contacts = $this->contact->allStudents();
		$contacts = array();
		foreach ($all_contacts as $contact) {
			$contacts[$contact->id] = $contact->name.' '.$contact->surname;	
		}


        if ($request->input('message_id')) {
            $messages = Message::processIds($request->input('message_id'));
        } else {
			$selected = null;
        }

        $students = new Collection;
        $selected = array(); 

        if (isset($messages)) {
            foreach ($messages as $message) {
                $students->add($message->Contact);
                $selected[] = $message->Contact->id;
            } 
            $students = $students->unique();
        }

		return view()->make('messages.create', compact('message_types', 'schools', 'contacts', 'student', 'selected'));

	}

	public function store(Request $request, $id = null) {

		$validator = Validator::make($request->all(), $this->message->rules);

		if ($validator->passes()) {

			if (!isset($id)) {
				$save_attachment = true;
			} else if (isset($id)) {
				$attachment_check = $this->message->find($id);
				if (!$attachment_check->attachment_id) {
					$save_attachment = true;
				} else {
					$save_attachment = false;
				}
			} else {
				$save_attachment = false;
			}

			if ($save_attachment && $request->file('attachment')) {
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

					if (isset($id)) {
						$message = $this->message->find($id);
					} else {
						$message = new Message;
					}

                    if ($request->input('school_id')) {
                        $school_id = $request->input('school_id');
                    } else {
                        $school_id = 0;
                    }
					
					$message->contact_id = $contact_id;
					$message->user_id = auth()->user()->id;
					$message->message_type_id = $request->input('message_type_id');
					$message->school_id = $school_id;
					$message->contents = $request->input('contents');

				
					if (isset($attachment)) {
						$message->attachment_id = $attachment->id;
					}

					$message->save();
				}
			}

			return redirect()->to('/')->with(['success' => 'Log <span class="mono">'.sprintf('%04d', $message->id).'</span> saved']);
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}

	}


	public function downloadAttachment($id) {
		$attachment = $this->attachment->find($id);
		return response()->download($attachment->attachment_filename);
	}
	

	public function showStudentMessages($id) {

		$student = $this->contact->find($id);
		$title = $student->fullName();
		$messages = $student->Messages()
				->where('messages.updated_at', '>', session()->get('start_date'))
                                ->where('messages.updated_at', '<', session()->get('end_date'))
                                ->where('messages.hidden', '0')
                                ->orderBy('messages.updated_at', 'DESC')
                                ->get();

		$two_level = false;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));
	}

	public function showSchoolMessages($id) {

		$school = $this->school->find($id);
		$title = $school->school_name;
		$messages = $school->Messages()
				->where('messages.updated_at', '>', session()->get('start_date'))
                                ->where('messages.updated_at', '<', session()->get('end_date'))
                                ->where('messages.hidden', '0')
                                ->orderBy('messages.updated_at', 'DESC')
                                ->get();

		$two_level = false;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));

	}

	public function deleteAttachment(Request $request) {

		$message = $this->message->find($request->input('message_id'));
		$message->attachment_id = null;
		$message->save();
		return 'done';	

	}

	public function delete(Request $request) {

		$message = $this->message->find($request->input('message_id'));
		$message->hidden = true;
		$message->save();
		return 'done';	

	}


	public function showMessageTypesMessages($id) {

		$message_type = $this->message_type->find($id);
		$title = $message_type->message_type_name;
		$messages = $message_type->Messages()
				->where('messages.updated_at', '>', session()->get('start_date'))
                                ->where('messages.updated_at', '<', session()->get('end_date'))
                                ->where('messages.hidden', '0')
                                ->orderBy('messages.updated_at', 'DESC')
                                ->get();

		$two_level = false;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));

	}

	public function showSchoolMessageTypeMessages($school_id, $message_type_id) {

		$school = $this->school->find($school_id);
		$message_type = $this->message_type->find($message_type_id);
		$title = $school->school_name." - ".$message_type->message_type_name;

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
			->where('messages.updated_at', '<', session()->get('end_date'))
			->where('messages.hidden', '0')
			->whereHas('School', function($query) use($school_id, $message_type_id) {
				$query->where('id', $school_id);
			})->whereHas('MessageType', function($query) use ($school_id, $message_type_id) {
				$query->where('id', $message_type_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));
	}

	public function showMessageTypeSchoolMessages($message_type_id, $school_id) {

		$school = $this->school->find($school_id);
		$message_type = $this->message_type->find($message_type_id);
		$title = $message_type->message_type_name." - ".$school->school_name;

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
			->where('messages.updated_at', '<', session()->get('end_date'))
			->where('messages.hidden', '0')	
			->whereHas('School', function($query) use($school_id, $message_type_id) {
				$query->where('id', $school_id);
			})->whereHas('MessageType', function($query) use ($school_id, $message_type_id) {
				$query->where('id', $message_type_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));

	}

	public function showMessageTypeStudentMessages($message_type_id, $student_id) {

		$student = $this->contact->find($student_id);
		$message_type = $this->message_type->find($message_type_id);
		$title = $student->fullName()." - ".$message_type->message_type_name;

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
                        ->where('messages.updated_at', '<', session()->get('end_date'))
                        ->where('messages.hidden', '0') 
			->whereHas('Contact', function($query) use($student_id, $message_type_id) {
				$query->where('id', $student_id);
			})->whereHas('MessageType', function($query) use ($student_id, $message_type_id) {
				$query->where('id', $message_type_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));

	}

	public function showStudentMessageTypeMessages($student_id, $message_type_id) {

		$student = $this->contact->find($student_id);
		$message_type = $this->message_type->find($message_type_id);
		$title = $student->fullName()." - ".$message_type->message_type_name;

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
                        ->where('messages.updated_at', '<', session()->get('end_date'))
                        ->where('messages.hidden', '0') 
			->whereHas('Contact', function($query) use($student_id, $message_type_id) {
				$query->where('id', $student_id);
			})->whereHas('MessageType', function($query) use ($student_id, $message_type_id) {
				$query->where('id', $message_type_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));
	}


	public function showSchoolStudentMessages($school_id, $student_id) {

		$school = $this->school->find($school_id);
		$student = $this->contact->find($student_id);
		$title = $school->school_name." - ".$student->fullName();

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
                        ->where('messages.updated_at', '<', session()->get('end_date'))
                        ->where('messages.hidden', '0') 
			->whereHas('School', function($query) use($school_id, $student_id) {
				$query->where('id', $school_id);
			})->whereHas('Contact', function($query) use ($school_id, $student_id) {
				$query->where('id', $student_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));
	}

	public function showStudentSchoolMessages($student_id, $school_id) {

		$school = $this->school->find($school_id);
		$student = $this->contact->find($student_id);
		$title = $student->fullName()." - ".$school->school_name;

		$messages = $this->message
			->where('messages.updated_at', '>', session()->get('start_date'))
                        ->where('messages.updated_at', '<', session()->get('end_date'))
                        ->where('messages.hidden', '0') 
			->whereHas('School', function($query) use($school_id, $student_id) {
				$query->where('id', $school_id);
			})->whereHas('Contact', function($query) use ($school_id, $student_id) {
				$query->where('id', $student_id);
			})->orderBy('messages.updated_at', 'DESC')->get();

		$two_level = true;
		return view()->make('messages.view', compact('title', 'messages', 'two_level'));

	}

	public function exportCSV(Request $request) {

        //$messages = $this->message->where('hidden', '0')->get();

        if ($request->input('message_id')) {
            $messages = Message::processIds($request->input('message_id'));

            if ($messages->count()) {

                $filename = 'export-'.str_random(8).'.csv';
                $csv_file = storage_path('app/public/'.$filename);
                $file = fopen($csv_file, 'w');

                fputcsv($file, array('id', 'date', 'student', 'type', 'school', 'notes'));

                foreach ($messages as $message) {
                    fputcsv($file, array($message->id, $message->updated_at, $message->Contact->fullNameLastFirst(), $message->MessageType->message_type_name, optional($message->School)->school_name, $message->contents)); 
                }

                $file = fclose($file);

                $headers = array(
                        'Content-Type' => 'text/csv'
                );

                return Storage::disk('public')->download($filename, $filename, $headers);
            }
        } else {
            return redirect()->back()->withError('Please select some records');
        }
                    

    }

}

?>
