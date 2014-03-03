<?php

class UsersController extends \BaseController {

	private $client;
	private $ctn_id;
	private $post_body;

	public function __construct()
	{
		$this->ctn_id = (is_object(Auth::user())) ? Auth::user()->username : "";
		$this->post_body = '{"createServiceAccountRequest": {}}';
		$this->client = new Client(Config::get('http://asgldlenc22:8080'));
		$this->client->setDefaultOption('headers', [
			'Content-Type' => 'application/json',
			'ctn_id' => $this->ctn_id
		]);

		$this->client->post("nimbus/rest/svcaccount/v1/webvvm", [], $this->post_body)->send();

//		json_decode(json_encode($array))

	}

}