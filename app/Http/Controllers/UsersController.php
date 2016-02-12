<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Hash;

use App\User;
use App\Role;

class UsersController extends Controller {

	protected $user;
	public function __construct(User $user) {
		$this->user = $user;
	}

	public function index() {

		$users = $this->user->whereHidden('0')->orderBy('username')->get();
		return view()->make('users.index', compact('users'));

	}


	public function create() {

		return view()->make('users.create');

	}

	public function store(Request $request, $id = null) {

		if (isset($id)) { 
			if ($request->input('password')) {
				$validator = Validator::make($request->only('username', 'email', 'password', 'password_confirmation'), $this->user->update_rules_with_password);
			} else {
				$validator = Validator::make($request->only('username', 'email', 'password', 'password_confirmation'), $this->user->update_rules);
			}
		} else {
			$validator = Validator::make($request->only('username', 'email', 'password', 'password_confirmation'), $this->user->create_rules);
		}

		if ($validator->passes()) {

			if (isset($id)) {
				$user = $this->user->find($id);
			} else {
				$user = new User;
			}
			$user->username = $request->input('username');
			$user->email = $request->input('email');
			if ($request->input('password')) {
				$user->password = Hash::make($request->input('password'));
			}

			$user->save();

			$role = Role::where('role_name', 'admin')->first();
			if ($request->input('admin')) {
				$user->roles()->attach($role);
			} else {
				if ($user->hasRole('admin')) {
					$user->roles()->detach($role);
				}
			}

			return redirect()->to('users')->with(['success' => 'Saved '.$user->username]);
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}

	}
	
	public function show($id = null) {
		
		if (isset($id)) {
			$user = User::find($id);
		} else {
			$user = auth()->user();
		}

		return view()->make('users.show', compact('user'));

	}

}

?>
