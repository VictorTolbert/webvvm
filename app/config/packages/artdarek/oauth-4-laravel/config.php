<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * AT&T
		 */
		'ATT' => array(
			'client_id'     => 'rau6ahmdiy8m8vj2bttvqqm7qbaxgftm',
			'client_secret' => 'ucgf1ln36orpxllw6dfahz2l4xmj7ica',
			'scope'         => array('voicemail')
		),
	)

);
