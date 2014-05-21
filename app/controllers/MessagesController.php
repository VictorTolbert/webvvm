<?php

use ATT\Message;
use \Guzzle\Service\Client;

class MessagesController extends \BaseController {

	private $client;
	private $message_ids;
	private $ctn_id;
	private $access_token;

	public function __construct()
	{
		$this->access_token = Session::get('access_token');
		$this->ctn_id = (is_object(Auth::user())) ? Auth::user()->username : "";
//		$this->client = new Client(Config::get('webvvm.api.server'), ['request.options' => ['proxy' => 'http://127.0.0.1:8888']]);
		$this->client = new Client(Config::get('webvvm.api.server'));
		$this->client->setDefaultOption('headers', ['ctn_id' => $this->ctn_id]);
		$this->client->setDefaultOption('headers', ['Authorization' => "Bearer " . $this->access_token]);
		$this->beforeFilter('oauth');
		$this->afterFilter('@convertAmrToMp3', ['only' => 'show']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try {
			$messages_response = $this->client->get("voicemail/v0/messages")->send();
			$messages_json = $messages_response->json()['messageList']['messages'];

			$configuration_response = $this->client->get("voicemail/v0/notification/configuration")->send();
			$configuration_json = $configuration_response->json();

			$gmtTimezone = new DateTimeZone('GMT');
			$userTimezone = new DateTimeZone('America/New_York');

			Message::truncate();
			foreach ($messages_json as $key => $message) {
				$myDateTime = new DateTime($messages_json[$key]['timestamp'], $gmtTimezone);
				$offset = $userTimezone->getOffset($myDateTime);
				Message::create([
					'id' => $messages_json[$key]['messageId'],
					'from' => $messages_json[$key]['from']['value'],
					'duration' => $messages_json[$key]['content'][0]['duration'],
					'user_id' => $this->ctn_id,
					'is_unread' => $messages_json[$key]['isUnread'] == 1 ? 1 : 0,
					'timestamp' => date('Y-m-d H:i', $myDateTime->format('U') + $offset),
					'is_urgent' => $messages_json[$key]['isUrgent'] == 1 ? 1 : 0,
				]);
				$this->message_ids[] = $messages_json[$key]['messageId'];

			}

			$ctn_id = $this->ctn_id;
			$messages = Message::with('user', 'contact')->orderBy('timestamp', 'desc')->get();

			return View::make('messages.index', compact('messages', 'ctn_id', 'configuration_json'));
		} catch (\Guzzle\Http\Exception\CurlException $e) {
			return $e->getMessage();
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $e) {
			// return $e->getMessage();
			return $e->getResponse();
			// return Redirect::back()->with('flash_message', 'Server Error')->withInput();
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$message_ids = Input::get('message_ids');

		foreach ($message_ids as $message_id) {
			if (!File::exists(public_path() . '/audio/' . $message_id . '.amr')) {
				try {
					$this->client
						->get("voicemail/v0/messages/" . $message_id . "/parts/0",
							['Accept' => 'audio/amr'],
							['save_to' => public_path() . '/audio/' . $message_id . '.amr'])
						->send();
				} catch (\Guzzle\Http\Exception\BadResponseException $e) {
					return $e->getMessage();
				}

			}
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function show($id)
	{
		$message = Message::find($id);

		if (!File::exists(public_path() . '/audio/' . $message->id . '.amr')) {
			try {
				$this->client
					->get("voicemail/v0/messages/" . $message->id . "/parts/0",
						['Accept' => 'audio/amr'],
						['save_to' => public_path() . '/audio/' . $message->id . '.amr'])
					->send();
			} catch (\Guzzle\Http\Exception\BadResponseException $e) {
				File::delete(public_path() . '/audio/' . $message->id . '.amr');
				Log::info($e->getResponse()->getStatusCode());
			}

		}

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function update($id)
	{

		try {
			$this->client->put("voicemail/v0/messages/" . $id,
				['Content-Type' => 'application/json'], "{\"isUnread\": false}")->send();

		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			Log::info($e->getResponse());
		}

	}

	/**
	 * Update multiple specified resources in storage.
	 *
	 * @return Response
	 */
	public function updateMultiple()
	{
		$messages = Input::get('messages');
		$body = "{ \"messages\": [";
		foreach ($messages as $key => $message) {
			$body .= "{\"messageId\": \"" . $message . "\", \"isUnread\": false }";

			if ($key < sizeof($messages) - 1) {
				$body .= ", ";
			} else {
				$body .= "]}";
			}
		}
		Log::info($body);
		try {
			$this->client->put("voicemail/v0/messages",
				['Content-Type' => 'application/json'], $body)->send();

		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			Log::info($e->getResponse());
		}

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->client->delete("voicemail/v0/messages/" . $id)->send();
		File::delete(public_path() . '/audio/' . $id . '.amr');
		File::delete(public_path() . '/audio/' . $id . '.mp3');
	}

	/**
	 * Remove multiple specified resources from storage.
	 *
	 * @return Response
	 */
	public function destroyMultiple()
	{
		$messages = implode(",", Input::get('messages'));
		try {
			$this->client->delete("voicemail/v0/messages?messageIds=" . $messages)->send();

		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			Log::info($e->getResponse());
		}
	}

	public function convertAmrToMp3($route, $request)
	{
		$ffmpeg = \FFMpeg\FFMpeg::create();

		try {
			if (!File::exists(public_path() . '/audio/' . $route->parameter('id') . '.mp3')) {
				$audio = $ffmpeg->open(public_path() . "/audio/" . $route->parameter('id') . ".amr");
				$audio->save(new \FFMpeg\Format\Audio\Mp3(), public_path() . "/audio/" . $route->parameter('id') . ".mp3");
			}
		} catch (FFMpeg\Exception\RuntimeException $e) {
			Log::info($e->getMessage());
		}
	}

}
