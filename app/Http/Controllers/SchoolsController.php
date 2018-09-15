<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\School;
use Validator;

class SchoolsController extends Controller {

	protected $school;
	public function __construct(School $school) {
		$this->school = $school;
	}
	

	public function index() {

		$schools = $this->school->whereHidden('0')->orderBy('school_name')->get();
		return view()->make('schools.index', compact('schools'));

	}

	public function create() {

		return view()->make('schools.create');

	}


	public function store(Request $request, $id = null) {

		$validator = Validator::make($request->all(), $this->school->rules);

		if ($validator->passes()) {

			if (isset($id)) {
				$school = $this->school->find($id);
			} else {
				$school = new School;
			}

            $input = $request->all();

			$school->school_name = $request->input('school_name');
			$school->contact_name = array_get($input, 'contact_name') ?: '';
			$school->phone = array_get($input, 'phone') ?: '';
			$school->email = array_get($input, 'email') ?: '';
			$school->website = array_get($input, 'website') ?: '';
			$school->application_url = array_get($input, 'application_url') ?: '';
			$school->save();

			return redirect()->to('schools')->with(['success' => 'School <span class="mono">'.$school->school_name.'</span> saved']);
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}
		

	}


	public function show($id) {

		$school = $this->school->find($id);

		return view()->make('schools.show', compact('school'));

	}

}

?>
