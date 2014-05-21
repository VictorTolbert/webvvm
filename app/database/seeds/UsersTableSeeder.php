<?php

use ATT\User;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		User::truncate();

		User::create([
			'username' => '2138220139',
			'email' => 'victor.tolbert@gmail.com',
			'password' => 'uplift'
		]);

		User::create([
			'username' => '2138220138',
			'email' => 'demo2@example.com',
			'password' => '1234'
		]);

		User::create([
			'username' => '2138220137',
			'email' => 'demo3@example.com',
			'password' => '1234'
		]);

		User::create([
			'username' => '2138220136',
			'email' => 'demo4@example.com',
			'password' => '1234'
		]);

		User::create([
			'username' => '4255050501',
			'email' => '4255050501@example.com',
			'password' => '1234'
		]);

		User::create([
			'username' => '4255050502',
			'email' => '4255050502@example.com',
			'password' => '1234'
		]);

		User::create([
			'username' => '2318318408',
			'email' => 'vt124m@att.com',
			'password' => 'uplift'
		]);

		User::create([
			'username' => '2138430364',
			'email' => 'vtolbert@vticonsulting.com',
			'password' => 'uplift'
		]);

	}

}
