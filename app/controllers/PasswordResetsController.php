<?php

class PasswordResetsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('passwordresets.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('password_resets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Password::remind(['email' => Input::get('email')], function ($message) {
			$message->subject('Your Password Reminder');
		});

		return Redirect::route('password_resets.create')->withSuccess(true);
	}

	public function reset($token)
	{
		return View::make('password_resets.reset')->withToken($token);
	}

	public function postReset()
	{
		$creds = [
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'password_confirmation' => Input::get('password_confirmation')
		];

		return Password::reset($creds, function ($user, $password) {
			$user->password = Hash::make($password);

			$user->save();

			return Redirect::route('sessions.create');
		});
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function show($id)
	{
		return View::make('passwordresets.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('passwordresets.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
