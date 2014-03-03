<?php namespace ATT;

class Message extends \Eloquent {
	protected $guarded = array();
	public $timestamps = false;
	public $incrementing = false;

	public function user()
	{
		return $this->belongsTo('ATT\User', 'username');
	}

	public function contact()
	{
		return $this->belongsTo('ATT\Contact', 'from');
	}

//	public function getDates()
//	{
//		return array('timestamp');
//	}

}