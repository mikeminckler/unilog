<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MessageType;
use Validator;

class MessageTypesController extends Controller {

	protected $message_type;
	public function __construct(MessageType $message_type) {
		$this->message_type = $message_type;
	}
	


	public function index() {

		// get all MessagTypes and send them to the view

		$message_types = $this->message_type->whereHidden('0')->orderBy('message_type_name')->get();

		return view()->make('message-types.index', compact('message_types'));

	}


	public function show($id) {

		$message_type = $this->message_type->find($id);

		return view()->make('message-types.show', compact('message_type'));

	}

	public function create() {

		// display the form to create a MessageType
		return view()->make('message-types.create');

	}


	public function store(Request $request, $id = null) {

		$validator = Validator::make($request->only('message_type_name'), $this->message_type->rules);

		if ($validator->passes()) {

			if (isset($id)) {
                                $message_type = $this->message_type->find($id);
                        } else {
                                $message_type = new MessageType;
                        }

			$message_type->message_type_name = $request->input('message_type_name');
			$message_type->save();

			return redirect()->to('message-types')->with(['success' => 'Log Type <span class="mono">'.$message_type->message_type_name.'</span> saved']);
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}

	}


}

?>
