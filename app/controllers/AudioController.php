<?php

use ATT\Message;
use \Guzzle\Service\Client;

class AudioController extends \BaseController {

	private $client;
	private $message_ids;
	private $ctn_id;
	private $access_token;

	public function __construct()
	{
		$this->access_token = Session::get('access_token');
		$this->ctn_id = (is_object(Auth::user())) ? Auth::user()->username : "";
		$this->client = new Client(Config::get('webvvm.api.server'));
//		$this->client = new Client(Config::get('webvvm.api.server'), ['request.options' => ['proxy' => 'http://127.0.0.1:8888']]);
		$this->client->setDefaultOption('headers', ['ctn_id' => $this->ctn_id]);
		$this->client->setDefaultOption('headers', ['Authorization' => "Bearer " . $this->access_token]);
		// $this->beforeFilter('auth');

		$this->afterFilter('@convertAmrToMp3', ['only' => 'show']);
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

		if (!File::exists(public_path() . '/audio/' . $id . '.amr')) {
			try {
				$this->client
					->get("voicemail/v0/messages/" . $id . "/parts/0",
						['Accept' => 'audio/amr'],
						['save_to' => public_path() . '/audio/' . $id . '.amr'])
					->send();
			} catch (\Guzzle\Http\Exception\BadResponseException $e) {
				File::delete(public_path() . '/audio/' . $id . '.amr');
				Log::info($e->getResponse()->getStatusCode());
			}

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
