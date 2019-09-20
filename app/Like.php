<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

	protected $fillable = [
        'user_id','likeable_id'
    ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function likeable()
    {
        return $this->belongsTo('App\User', 'likeable_id');
    }
}
