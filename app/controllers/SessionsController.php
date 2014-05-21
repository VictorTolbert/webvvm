<?php

use Artdarek\OAuth\OAuth;

class SessionsController extends BaseController {

	protected $oauth;

	public function __construct(OAuth $oauth)
	{
		$this->oauth = $oauth;
	}

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
		Session::forget('access_token');
		return Redirect::to('login')->with('flash_message', 'You have been logged out!');
	}

	public function oauth()
	{
		$code = Input::get('code');

		$att = $this->oauth->consumer('ATT');

		if (!empty($code)) {

			$token = $att->requestAccessToken($code)->getAccessToken();
			Session::put('access_token', $token);

			// dd($att);
			return Redirect::to( '/messages' );

			// Validate

			$result = json_decode( $att->request('me.json'), true);
			dd($result);

			$message = 'Your unique att user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			echo $message. "<br/>";

			//Var_dump
			//display whole array().
//			dd($result);

//			$user = User::firstOrCreate(array('gh_id' => $result['id']));
//			$user->name = $result['login'];
//			$user->save();

//			Auth::login($user);
//			return Redirect::to('/');

		} else {
			$url = $att->getAuthorizationUri();
//			dd($url);
			return Redirect::to( (string)$url );

		}
	}

	public function oauth2()
	{
		$provider = $this->oauth->consumer('ATT');

		if(! Input::has('code') and ! Session::has('oauth_token'))
		{
			return Redirect::to( (string) $provider->getAuthorizationUri());
		}

		if(Input::has('code'))
		{
			Session::put('oauth_token', $provider->requestAccessToken(Input::get('code'))->getAccessToken());
			return Redirect::back();
		}
		elseif (Session::has('oauth_token'))
		{
			$token = Session::get('oauth_token');
		}
		else
		{
			throw new Exception('No access token found.');
		}

//		if(\Carbon\Carbon::createFromTimeStamp($token->expires)->isPast())
//		{
//			$newToken           = $provider->refreshAccessToken($token);
//			$token->accessToken = $newToken->accessToken;
//			$token->expires     = $newToken->expires;
//			Session::put('oauth_token', $token);
//		}

	}

}
