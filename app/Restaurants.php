<?php

namespace App;

class Restaurants extends CommonModel
{
	protected $table = 'restaurants';
	protected $guarded = [];
	static $model = 'restaurants';

    static function details($id)
	{
		 $data = static::get($id);
		 
		 if($data)
		 {
		 	return $data;
		 }
		 else
		 	return false;
	}

	public function email()
    {
        return $this->belongsTo('App\User');
    }
}
