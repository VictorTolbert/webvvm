<?php

class SessionsController extends BaseController {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('sessions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		// Validate
		$input = Input::all();

		$attempt = Auth::attempt([
//            'email' => $input['email'],
			'username' => $input['username'],
			'password' => $input['password']
		]);

		if ($attempt) {
			return Redirect::intended('/messages')->with('flash_message', 'You have been logged in!');
			Log::info(Auth::user()->username);
		}

		return Redirect::back()->with('flash_message', 'Invalid credentials')->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy()
	{
		Auth::logout();

		return Redirect::to('login')->with('flash_message', 'You have been logged out!');
	}

}
