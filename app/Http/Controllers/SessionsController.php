<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\User;
use App\Message;
use Validator;

class SessionsController extends Controller {

	protected $user;
	protected $message;
	public function __construct(User $user, Message $message) {
		$this->user = $user;
		$this->message = $message;
	}

	public function index() {

		if (auth()->guest()) {
			return view()->make('login');
		} else {
			return redirect()->to('/');
		}

	}


	public function login(Request $request) {

	
		$validator = Validator::make($request->only('email', 'password'), $this->user->login_rules);

		if ($validator->passes()) {
			if (auth()->attempt($request->only('email', 'password'))) {
				$user = auth()->user();

				//$start_date = new DateTime('-1 year');
				//$end_date = new DateTime;

				if (date("m") <= 8) {
					$current_year = date("Y") - 1;
				} else {
					$current_year = date("Y");
				}

				$start_date = Carbon::create($current_year, 9, 1, 0, 0, 0);
				$end_date = Carbon::now()->endOfDay();

				session()->put('start_date', $start_date);
				session()->put('end_date', $end_date);

				return redirect()->route('home');
				//return view()->make('home', compact('user'))->with('success', 'You are now Logged in');
			} else {
				return redirect()->back()->with('error', 'Login Failed')->withInput();
			}
		} else {
			return redirect()->back()->withInput()->withErrors($validator->messages());
		}

	}


	public function logout() {
		auth()->logout();
		return redirect()->route('login');
	}

	public function startDate(Request $request) {
		$new_date = $request->input('date');
		session()->put('start_date', $new_date);
/*
		$messages = $this->message
				->where('updated_at', '>', session()->get('start_date'))
				->where('updated_at', '<', session()->get('end_date'))
				->whereHidden('0')
				->orderBy('updated_at', 'DESC')
				->get();

		$response = "";

		if (count($messages) > 0) {
			$response .= formatMessages($messages);
		} else {
			$response .= "There were no results";
		}
		return $response;
*/
	}

	public function endDate(Request $request) {

		$new_date = Carbon::createFromTimestamp(strtotime($request->get('date')))->endOfDay();
		session()->put('end_date', $new_date);
/*
		$messages = $this->message
				->where('updated_at', '>', session()->get('start_date'))
				->where('updated_at', '<', session()->get('end_date'))
				->whereHidden('0')
				->orderBy('updated_at', 'DESC')
				->get();

		$response = "";

		if (count($messages) > 0) {
			$response .= formatMessages($messages);
		} else {
			$response .= "There were no results";
		}
		return $_SERVER["REQUEST_URI"];
		return $response;
*/

	}

}

?>
