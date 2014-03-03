<?php namespace ATT;

class Contact extends \Eloquent {
	protected $guarded = array();
	public static $rules = array();
	protected $primaryKey = 'phone';
	public $incrementing = false;

	public function message()
	{
		return $this->hasMany('ATT\Message');
	}

	public function getFullNameAttribute()
	{
		return $this->first_name . " " . $this->last_name;
	}
}
