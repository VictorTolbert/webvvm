<?php

use ATT\Contact;

class ContactsTableSeeder extends Seeder {

	public function run()
	{
		Contact::truncate();
		Contact::create([
			'first_name' => 'Victor',
			'last_name' => 'Tolbert',
			'phone' => '6786133400',
			'email' => 'victor.tolbert@gmail.com'
		]);

		Contact::create([
			'first_name' => 'Kip',
			'last_name' => 'Fergusson',
			'phone' => '4049869093',
			'email' => 'kf8696@att.com'
		]);

		Contact::create([
			'first_name' => 'Kip',
			'last_name' => 'Fergusson',
			'phone' => '4042422705',
			'email' => 'kip.ferusson@att.com'
		]);

		Contact::create([
			'first_name' => 'Dawson',
			'last_name' => 'Blackhouse',
			'phone' => '4045478761',
			'email' => 'dawson@angryshakingfist.com'
		]);

		Contact::create([
			'first_name' => 'Amanuel',
			'last_name' => 'Tewodros',
			'phone' => '6782967389',
			'email' => 'at0772@att.com'
		]);

		Contact::create([
			'first_name' => 'Chris',
			'last_name' => 'Hambrick',
			'phone' => '6784294574',
			'email' => 'ch7788@att.com'
		]);

		Contact::create([
			'first_name' => 'Andy',
			'last_name' => 'Schmidt',
			'phone' => '4044550821',
			'email' => 'andy@splashpark.com'
		]);


		Contact::create([
			'first_name' => 'Derek',
			'last_name' => 'Jones',
			'phone' => '2318318407',
			'email' => 'demouser@att.net'
		]);

		Contact::create([
			'first_name' => 'Susan',
			'last_name' => 'Smith',
			'phone' => '2318318405',
			'email' => 'demouser2@att.net'
		]);

	}

}