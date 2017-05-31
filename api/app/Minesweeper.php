<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Minesweeper extends Model
{
	protected $fillable = ['x', 'y', 'grid', 'token'];

	public function setGridAttribute($value){
        $this->attributes['grid'] = json_encode($value);
	}

	public function setTokenAttribute($value){
        $this->attributes['token'] = empty($value) ? \Hash::make('').time() : $value;
	}
}
